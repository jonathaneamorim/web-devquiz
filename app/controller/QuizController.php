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
    
                $newQuizId = $this->quiz->newQuiz($titulo, $descricao, $userId);
                if($newQuizId) {
                    http_response_code(201);
                    $response = [
                        'message' => 'Quiz cadastrado com sucesso!',
                        'quizId' => $newQuizId
                    ];
                    echo json_encode($response);
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

    public function getAllQuizzes() {
        try {
            $quizzes = $this->quiz->getAllQuizzes();
            if($quizzes) {
                header('Content-Type: application/json');
                echo json_encode($quizzes);
            } else {
                echo 'Sem quizzes cadastrados!';
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar quizzes!: ' . $e);
        }
    }

    public function getlistquizByUser() {
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

    public function edit($id) {
        $quiz = $this->quiz->getQuizById($id);
        $perguntas = $this->quiz->getQuizQuestions($id);
        $respostas = [];
        foreach ($perguntas as $pergunta) {
            array_push($respostas, $this->quiz->getAnswerByQuestionId($pergunta->id));
        }
        $session = get_session();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!is_logged()) {
                header('Location: /login');
                exit;
            }
            if(!is_admin($session['id']) || $quiz->criadoPor != $session['id']) {
                header('Location: /quiz');
                exit;
            }
    
            require_once __DIR__ . '/../views/editQuiz.php';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quizId = $_POST['quizId'];
            $questionText = $_POST['questionText'];
            $answer1 = $_POST['answer1'];
            $answer2 = $_POST['answer2'];
            $answer3 = $_POST['answer3'];
            $answer4 = $_POST['answer4'];
            $correctAnswerId = $_POST['correctAnswer'];

            $newQuestionId = $this->quiz->newQuestion($quizId, $questionText);

            if($newQuestionId) {
                $answers = [
                    ['texto' => $answer1],
                    ['texto' => $answer2],
                    ['texto' => $answer3],
                    ['texto' => $answer4]
                ];

                $insertedAnswersIds = $this->quiz->newAnswers($answers, $newQuestionId);

                if($insertedAnswersIds)  {
                    http_response_code(201);
                    echo 'Pergunta criada com sucesso!';
                    exit;
                } else {
                    http_response_code(400);
                    echo 'Erro ao criar respostas!';
                    exit;
                }
            } else {
                http_response_code(400);
                echo 'Erro ao criar pergunta!';
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            try {
                // Get data put method: https://www.sitepoint.com/community/t/put-method/41476/4
                parse_str(file_get_contents("php://input"),$put_vars);
                $questId = $put_vars['questionId'];
                $quizId = $put_vars['quizId'];
                $questionText = $put_vars['questionText'];
                $answer1 = $put_vars['answer1'];
                $answer2 = $put_vars['answer2'];
                $answer3 = $put_vars['answer3'];
                $answer4 = $put_vars['answer4'];
                $answers = [
                    $answer1 ,
                    $answer2 ,
                    $answer3 ,
                    $answer4 
                ];

                $correctAnswerId = $put_vars['correctAnswer'];
                $currentQuestion = $this->quiz->getQuestionById($questId);

                $response = [];
                if($currentQuestion) {
                    if(!($currentQuestion->texto === $questionText) || !($currentQuestion->resposta_certa_id === $correctAnswerId)) {
                        $updateQuestion = $this->quiz->putQuestion($questId, $questionText, $correctAnswerId);
                        if($updateQuestion) {
                            $response[] = ['question' => 'Updated'];
                        }
                    }

                    $updateAnswers = $this->quiz->putAnswer($questId, $answers);

                    if($updateAnswers) {
                        $response[] = ['answer' => 'Updated'];
                    }

                    http_response_code(200);
                    echo json_encode($response);
                    exit;

                } else {
                    echo 'Essa pergunta não existe!';
                }
            } catch(Exception $e) {
                error_log('Erro ao atualizar pergunta! - ' . $e->getMessage());
            }
        }
    }

    // Mudar o nome disso kkkk
    public function getQuestions($quizId) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $questions = $this->quiz->getQuizQuestions($quizId);
                if($questions) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode($questions);
                    exit;
                } else {
                    http_response_code(204);
                    echo 'Sem perguntas para esse quiz!';
                    exit;
                }
            } catch(Exception $e) {
                error_log('Erro ao capturar questões do quiz! ' . $e);
                return false;
            }
        }
    }

    public function deleteQuestion($questionId) {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            try {
                $answers = $this->quiz->deleteQuestion($questionId);
                if($answers) {
                    http_response_code(200);
                    echo 'Pergunta deletada com sucesso!';
                    exit;
                } else {
                    http_response_code(400);
                    echo 'Erro ao deletar pergunta!';
                    exit;
                }
            } catch(Exception $e) {
                error_log('Erro ao deletar pergunta! ' . $e);
            }
        }
    }

    public function getAnswer($questionId) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $answers = $this->quiz->getAnswerByQuestionId($questionId);
                if($answers) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode($answers);
                    exit;
                } else {
                    http_response_code(204);
                    echo 'Sem respostas para essa pergunta!';
                    exit;
                }
            } catch(Exception $e) {
                error_log('Erro ao capturar respostas da pergunta! ' . $e);
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            try {
                $deleted = $this->quiz->deleteQuiz($id);
                if($deleted) {
                    http_response_code(200);
                    echo 'Quiz deletado com sucesso!';
                }
            } catch(Exception $e) {
                error_log('Erro ao remover quiz!: ' . $e);
            }
        }
    }
}