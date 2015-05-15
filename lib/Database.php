<?php
class Database extends PDO
{
    private $host;
    private $port;

    public function __construct($params, array $options = array())
    {
        extract($params);

        if(defined('PERSISTENT_CONNECTION') && PERSISTENT_CONNECTION) {
            $options = array_merge($options, array(PDO::ATTR_PERSISTENT => true));
        }

        $this->host = $host;
        $this->port = $port;

        parent::__construct("mysql:host=$host;port=$port;dbname=$name", $user, $pass, $options);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_PREFETCH, 0);

        $this->exec('SET NAMES UTF8MB4');
    }
}
