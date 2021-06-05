<?php
namespace App;

/**
 * SQLite connnection
 */
class SQLiteConnection {
    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect($dbfile) {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . $dbfile);
            $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT,true);
        }
        return $this->pdo;
    }
}