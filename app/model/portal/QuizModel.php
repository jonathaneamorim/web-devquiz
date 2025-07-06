<?php

require_once __DIR__ . '/../Database.php';

// stmt = statement ou declaração

class QuizModel {
    
    protected $quizTable = 'quiz';
    protected $questionTable = 'pergunta';
    protected $answerTable = 'resposta';
    protected $scoreTable = 'tabelaPontuacao';
    protected $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function newQuiz($titulo, $descricao, $userId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->quizTable (titulo, descricao, criadoPor, criadoEm) VALUES (:titulo, :descricao, :userId, NOW())");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':userId', $userId);
            
            // verifica se o retorno da criação é true ou false
            // se for true retorna o ultimo id adicionado, nesse caso o valor adicionado
            // caso contrario retorna false
            // https://www.php.net/manual/pt_BR/pdo.lastinsertid.php
            return $stmt->execute() ? $this->db->lastInsertId() : false;

        } catch(PDOException $e) {
            error_log('Erro ao inserir novo quiz: '. $e->getMessage());
        }
    }
    
    public function newQuestion($quizId, $questionText) {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->questionTable (texto, quiz_id) VALUES (:texto, :quizId)");
            $stmt->bindParam(':texto', $questionText);
            $stmt->bindParam(':quizId', $quizId);
            return $stmt->execute() ? $this->db->lastInsertId() : false;
        } catch(PDOException $e) {
            error_log('Erro ao inserir nova pergunta: '. $e->getMessage());
            return false;
        }
    }

    public function newAnswers($listAnswers, $questionId) {
        $insertedIds = [];
        try {
            foreach($listAnswers as $answer) {
                $stmt = $this->db->prepare("INSERT INTO $this->answerTable (texto, pergunta_id) VALUES (:texto, :question_id)");
                $stmt->bindParam(':texto', $answer['texto']);
                $stmt->bindParam(':question_id', $questionId);
                $stmt->execute();
                array_push($insertedIds, $this->db->lastInsertId());
            }
            return $insertedIds;
        } catch(PDOException $e) {
            error_log('Erro ao inserir novas respostas: '. $e->getMessage());
            // De acordo com pesquisas, o return false evitará ambiguidade no caso de retornar null
            // Null a lista ou null a execução?
            // https://www.reddit.com/r/PHPhelp/comments/p1by7y/best_practice_for_returning_a_false_value_from_a/
            return false;
        }
    }

    public function putQuiz($quizId, $titulo, $descricao) {
        try {
            $stmt = $this->db->prepare("UPDATE $this->quizTable SET titulo = :newTitle, descricao = :newDescription WHERE id = :quizId");
            $stmt->bindParam(':newTitle', $titulo);
            $stmt->bindParam(':newDescription', $descricao);
            $stmt->bindParam(':quizId', $quizId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar quiz: '. $e->getMessage());
            return false;
        }
    }

    public function putQuestion($questionId, $textQuestion, $correctAnswerId) {
        try {
            $stmt = $this->db->prepare("UPDATE $this->questionTable SET texto = :textQuestion, resposta_certa_id = :correctAnswerId WHERE id = :questionId");
            $stmt->bindParam(':questionId', $questionId);
            $stmt->bindParam(':textQuestion', $textQuestion);
            $stmt->bindParam(':correctAnswerId', $correctAnswerId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar pergunta: '. $e->getMessage());
            return false;
        }
    }

    public function putAnswer($questionId, $listAnswers) {
        try {
            foreach($listAnswers as $answer) {
                $stmt = $this->db->prepare("UPDATE $this->answerTable SET texto = :textQuestion WHERE id = :answerId");
                $stmt->bindParam(':textQuestion', $answer['text']);
                $stmt->bindParam(':answerId', $answer['id']);
                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            error_log('Erro ao atualizar respostas!: '. $e->getMessage());
            return false;
        }
    }

    public function deleteQuiz($quizId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM $this->quizTable WHERE id = :id");
            $stmt->bindParam(':id', $quizId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao deletar quiz: '. $e->getMessage());
        }
    }

    public function deleteQuestion($questionId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM $this->questionTable WHERE id = :id");
            $stmt->bindParam(':id', $questionId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao deletar quiz: '. $e->getMessage());
            return false;
        }
    }

    public function getQuizById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->quizTable WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log('Erro ao receber quiz: '. $e->getMessage());
        }
    } 

    public function getQuizzesByUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->quizTable WHERE criadoPor = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log('Erro ao receber quizzes: '. $e->getMessage());
        }
    }

    public function getAllQuizzes() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->quizTable");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log('Erro ao receber quizzes: '. $e->getMessage());
        }
    }   

    public function getQuizQuestions($quizId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->questionTable WHERE quiz_id = :quizId");
            $stmt->bindParam(':quizId', $quizId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log('Erro ao receber perguntas do quiz: '. $e->getMessage());
        } 
    }

    public function getQuestionById($questionId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->questionTable WHERE id = :questionId");
            $stmt->bindParam(':questionId', $questionId);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log('Erro ao receber pergunta!: '. $e->getMessage());
        } 
    }

    public function getAnswerByQuestionId($questionId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->answerTable WHERE pergunta_id = :questionId");
            $stmt->bindParam(':questionId', $questionId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log('Erro ao receber respostas do quizzes: '. $e->getMessage());
        }
    }

    public function setCorrectAnswer($questionId, $correctAnswerId) {
        try {
            $stmt = $this->db->prepare("UPDATE $this->questionTable SET resposta_certa_id = :correctAnswerId WHERE id = :questionId");
            $stmt->bindParam(':correctAnswerId', $correctAnswerId);
            $stmt->bindParam(':questionId', $questionId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar quiz: '. $e->getMessage());
            return false;
        }
    }

    public function newScore($userId, $quizId, $hits, $total) {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->scoreTable (usuarioId, quizId, acertos, total, ultimaVezRespondido) VALUES (:userId, :quizId, :hits, :total, NOW())");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':quizId', $quizId);
            $stmt->bindParam(':hits', $hits);
            $stmt->bindParam(':total', $total);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log('Erro ao inserir pontuação: '. print_r($stmt->errorInfo(), true));
            return false;
        }
    }
}