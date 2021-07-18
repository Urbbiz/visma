<?php

namespace Syllable\Database;

use PDO;
use PDOException;

class Database
{
    private string $userName = "syllable";
    private string  $serverName = "localhost";
    private string  $password = "Syllable_53258";
    private string  $database = "syllable";
    private string  $charset = "utf8mb4";   //default set

    public function connect():PDO
    {
        try {
            $dsn = "mysql:host=$this->serverName;dbname=$this->database;charset=$this->charset;";  //data source name

            $pdo = new PDO(
                $dsn,
                $this->userName,
                $this->password
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            echo "Connection failed: ". $e->getMessage();
        }
//        return $pdo;
    }
}