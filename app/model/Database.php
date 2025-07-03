<?php

class Database {
    private $host = 'localhost';
    private $dbname = 'devquiz';
    private $username = 'root';
    private $password = 'admin';
    private $charset = 'utf8';
    private $conn;

    public function connect() {
        try {
            $pdo = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $this->conn = new PDO(
                $pdo,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            return $this->conn;
        } catch (PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }
        return $this->conn;
    }
}