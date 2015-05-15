<?php

require_once('config/config.php');

class ImportJSON
{
    private $db;
    private $json;
    private $logfile;

    public function __construct($config)
    {
        $this->config = $config;
        $this->logfile = ROOT . 'logs/import.log';
        $this->db = new Database($this->config['db']);
    }

    public function doImport($jsonDataFile)
    {
        try {
            $this->json = json_decode(file_get_contents($jsonDataFile), true);
            $this->work($this->json);
        } catch (Exception $e) {
            $this->log('Import error: ' . $e->getMessage() . ', JSON: ' . var_export($this->json, 1));
        }
    }

    private function work(array $categories, $parentId = 0)
    {
        foreach ($categories as $category) {
            if (! is_array($category)) {
                $this->log('Invalid category ' . var_export($category, 1) . '. Skipping...');
                continue;
            }
            if (empty($category['active'])) {
                continue;
            }
            $sql = 'INSERT INTO `category` (id, parent_id, name) VALUES' .
                    '(' . (int)$category['id'] . ', ' . (int)$parentId . ', ' . $this->db->quote($category['name']) . ')';
            $insertResult = $this->db->exec($sql);
            if ($insertResult === false) {
                throw new Exception('Couldnt insert category to db. SQL: ' . $sql);
            }
            if (! empty($category['news'])) {
                $newsData = [];
                foreach ($category['news'] as $news) {
                    if (! is_array($news)) {
                        $this->log('Invalid news ' . var_export($news, 1) . '. Skipping...');
                        continue;
                    }
                    if (empty($news['active'])) {
                        continue;
                    }
                    $newsData[] = [
                        'id'            => (int)$news['id'],
                        'category_id'   => (int)$category['id'],
                        'title'         => $this->db->quote($news['title']),
                        'image'         => $this->db->quote($news['image']),
                        'description'   => $this->db->quote($news['description']),
                        'text'          => $this->db->quote($news['text']),
                        'date'          => $this->db->quote($news['date']),
                    ];
                }
                if (! empty($newsData)) {
                    $sqlNews = 'INSERT INTO `news` (id, category_id, title, image, description, text, date) VALUES ';
                    foreach ($newsData as $sqlData) {
                        $sqlNews .= '(' . implode(',', $sqlData) . '),';
                    }
                    $this->db->exec(rtrim($sqlNews, ','));
                }
            }

            if (! empty($category['subcategories'])) {
                $this->work($category['subcategories'], $category['id']);
            }
        }

    }

    private function log($message)
    {
        file_put_contents($this->logfile, sprintf("[%s] %s\n", date('M d Y H:i:s T'), trim($message)), FILE_APPEND);
    }
}

$obj = new ImportJSON($config);
$obj->doImport(ROOT . 'data.json');
