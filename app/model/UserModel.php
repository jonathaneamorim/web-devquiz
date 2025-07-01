<?php

namespace app\model;

use config\Database;

class UserModel {
    private $db;
    private $table = 'usuario';

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Retorna todas as tarefas
    public function addNewUser($nome, $email, $senha) {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->table (id, nome, email, senha) VALUES (UUID(), :nome, :email, :senha)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Exibe ou registra o erro
            echo "Erro ao inserir usuário: " . $e->getMessage();
        }
    }

    // Adiciona uma nova tarefa
    public function findUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erro ao encontrar usuário por email: '. $e->getMessage());
        }
    }

    // // Atualiza uma tarefa
    // public function updateTask($id, $title, $description) {
    //     $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
    //     return $stmt->execute([$title, $description, $id]);
    // }

    // // Deleta uma tarefa
    // public function deleteTask($id) {
    //     $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
    //     return $stmt->execute([$id]);
    // }
}