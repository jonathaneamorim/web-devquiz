<?php

require_once __DIR__ . '/Database.php';

// Todos os models criados deverão ser estendidos de Model
// Aqui estarão itens que deverão ser utilizados em todos os arquivos model, ex: Conexão com o banco de dados para gerar tabelas
abstract class ContainerModel {

    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function all() {

        var_dump($this->db);
        die();

        try {
            if (!$this->db) {
                throw new Exception("Sem conexão com o banco de dados.");
            }
            $sql = $this->db->prepare("SELECT * FROM {$this->table}");
            $sql->execute();

            return $sql->fetchAll();
        } catch(PDOException $e) {
            error_log('Erro ao listar: ' . $e->getMessage());
            return [];
        }
    }

}