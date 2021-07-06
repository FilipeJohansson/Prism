<?php

require_once("config.ini.php");

class Database extends PDO {

	protected $connection;
	private $host, $name, $username, $password;
	private static $instance;

	private function __construct($host = DB_HOST, $name = DB_NAME, $username = DB_USER, $password = DB_PASS) {
		$this->host = $host;
		$this->name = $name;
		$this->username = $username;
		$this->password = $password;
		$this->connect();
	}

	private function connect() {
		try {
			$conn = new PDO("mysql:host=".$this->host.";dbname=".$this->name, $this->username, $this->password);
			$conn->exec('SET CHARACTER SET utf8');
			$this->connection = $conn;
		} catch (PDOException $e) {
			die("Failed to connect to database: " . $e->getMessage());
		}
	}

	public static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

	public function close() {
		self::$instance = null;
		return $this->connection = null;
	}

}
