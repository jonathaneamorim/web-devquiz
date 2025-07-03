<?php

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../ContainerModel.php';

class QuizModel extends ContainerModel {
    
    protected $table = 'quiz';
    protected $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function newQuiz($titulo, $descricao, $userId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->table (titulo, descricao, criadoPor, criadoEm) VALUES (:titulo, :descricao, :userId, NOW())");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            error_log('Erro ao inserir novo quiz: ', $e);
        }
    }

    public function getQuizzesByUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log('Erro ao inserir novo quiz: ', $e);
        }
    }
    
}