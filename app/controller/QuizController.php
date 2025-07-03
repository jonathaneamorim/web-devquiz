<?php

require_once __DIR__ . '/../model/portal/UserModel.php';
require_once __DIR__ . '/../model/portal/QuizModel.php';
require_once __DIR__ . '/../utils/helpers.php';

class QuizController {

    private $quiz;

    public function __construct() {
        $this->quiz = new QuizModel();
    }

    public function index() {
        if(!is_logged()) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/quiz.php';
    }

    public function new() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!is_admin(get_session()['id'])) {
                header('Location: /quiz');
                exit;
            }
            
            require_once __DIR__ . '/../views/newQuiz.php';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $titulo = $_POST['titulo'];
                $descricao = $_POST['descricao'];
                $userId = get_session()['id'];
    
                if($this->quiz->newQuiz($titulo, $descricao, $userId)) {
                    http_response_code(201);
                    echo 'Quiz cadastrado com sucesso!';
                    exit;
                } else {
                    http_response_code(400);
                    echo 'Erro ao cadastrar quiz';
                    exit;
                }
            } catch(Exception $e) {
                error_log('Erro ao criar novo quiz: ', $e);
                http_response_code(400);
                echo 'Erro ao cadastrar quiz';
                exit;
            }
        }
    }

    public function getlistquiz() {
        try {
            $quizzes = $this->quiz->getQuizzesByUser(get_session()->id);
            if($quizzes) {
                header('Content-Type: application/json');
                echo json_encode($quizzes);
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar quizzes desse administrador! ' . $e);
        }
    }

    public function show() {
        $quizzes = $this->quiz->all();
        if($quizzes) {
            header('Content-Type: application/json');
            echo json_encode($quizzes);
        } else {
            echo 'Sem quizzes cadastrados!';
        }
    }

    public function edit($id) {
        
    }
}