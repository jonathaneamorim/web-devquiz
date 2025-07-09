<?php

require_once __DIR__ . '/../Database.php';

class UserModel {
    private $userTable = 'usuario';
    private $scoreTable = 'tabelaPontuacao';
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function addNewUser($nome, $email, $senha) {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->userTable (id, nome, email, senha) VALUES (UUID(), :nome, :email, :senha)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir usuário: " . $e->getMessage();
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->userTable WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log('Erro em ao encontrar usuário: ' . $e->getMessage());
            return false;
        }
    }

    public function findUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->userTable WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erro ao encontrar usuário por email: '. $e->getMessage());
        }
    }

    public function updateUserData($userId, $newName, $newEmail) {
        try {
            $stmt = $this->db->prepare("UPDATE $this->userTable SET nome = :newName, email = :newEmail WHERE id = :userId");
            $stmt->bindParam(':newName', $newName);
            $stmt->bindParam(':newEmail', $newEmail);
            $stmt->bindParam(':userId', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar usuario: '. $e->getMessage());
            return false;
        }
    }

    public function updatePassword($userId, $newPassword) {
        try {
            $stmt = $this->db->prepare("UPDATE $this->userTable SET senha = :newPassword WHERE id = :userId");
            $stmt->bindParam(':newPassword', $newPassword);
            $stmt->bindParam(':userId', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar senha: '. $e->getMessage());
            return false;
        }
    }

    public function getUserScore($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->scoreTable WHERE usuarioId = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erro ao capturar informações de pontuação de usuário: '. $e->getMessage());
        }
    }
}