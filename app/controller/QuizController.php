<?php

require_once __DIR__ . '/../model/portal/UserModel.php';
require_once __DIR__ . '/../model/portal/QuizModel.php';
require_once __DIR__ . '/../utils/helpers.php';

// Criar um método para retornar o http_response_code e o codigo
// Esse método vai receber o codigo e o que vai retornar e vai aplicar as duas e dar um exit no final
// Aproitando que ele ja vai estar ali e colocar um log junto
// Fazer validações caso ele seja 200, 201, 400 ou 500

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
                http_response_code(200);
                echo json_encode($quizzes);
            } else {
                http_response_code(204);
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar quizzes!: ' . $e);
        }
    }

    public function getQuizById($quizId) {
        try {
            $quiz = $this->quiz->getQuizById($quizId);
            if($quiz) {
                header('Content-Type: application/json');
                http_response_code(200);
                echo json_encode($quiz);
            } else {
                http_response_code(204);
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar quizzes!: ' . $e);
        }
    }

    // public function getlistquizByUser() {
    //     try {
    //         $quizzes = $this->quiz->getQuizzesByUser(get_session()->id);
    //         if($quizzes) {
    //             header('Content-Type: application/json');
    //             echo json_encode($quizzes);
    //         }
    //     } catch(Exception $e) {
    //         error_log('Erro ao capturar quizzes desse administrador! ' . $e);
    //     }
    // }

    public function editQuestion($quizId) {
        
        $perguntas = $this->quiz->getQuizQuestions($quizId);
        $respostas = [];
        foreach ($perguntas as $pergunta) {
            $respostas[] = $this->quiz->getAnswerByQuestionId($pergunta->id);
        }
        $session = get_session();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    // https://www.php.net/manual/pt_BR/function.strtolower.php
                    // Garantir que a letra será minuscula
                    $letra = strtolower($correctAnswerId);
                    $map = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3];
                    
                    if (isset($map[$letra])) {
                        // Captura o id da resposta correta a partir do mapeamento
                        $respostaCorretaId = $insertedAnswersIds[$map[$letra]];
                        $setCorrectAnswer = $this->quiz->setCorrectAnswer($newQuestionId, $respostaCorretaId);
                        
                        if($respostaCorretaId) {
                            http_response_code(201);
                            echo 'Pergunta criada com sucesso!';
                            exit;
                        } else {
                            http_response_code(400);
                            echo 'Erro ao inserir resposta correta!';
                            exit;
                        }
                    } else {
                        http_response_code(201);
                        echo 'Erro no mapeamento de letras! - Verifique o envio das letras!';
                        exit;
                    }              

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

                    if(!($currentQuestion->resposta_certa_id === $correctAnswerId)) {
                        $correctAnswerUpdate = $this->quiz->setCorrectAnswer($currentQuestion->id, $correctAnswerId);
                        if($correctAnswerUpdate) {
                            $response[] = ['correct_answer' => 'Updated'];
                        }
                    }

                    http_response_code(200);
                    echo json_encode($response);
                    exit;

                } else {
                    http_response_code(400);
                    echo 'Essa pergunta não existe!';
                    exit;
                }
            } catch(Exception $e) {
                error_log('Erro ao atualizar pergunta! - ' . $e->getMessage());
                http_response_code(500);
                echo 'Erro no servidor ao atualizar pergunta!';
                exit;
            }
        }
    }

    public function edit($quizId) {
        $session = get_session();
        $quiz = $this->quiz->getQuizById($quizId);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!is_logged()) {
                header('Location: /login');
                exit;
            }
            if(!is_admin($session['id'])) {
                header('Location: /quiz');
                exit;
            }
    
            require_once __DIR__ . '/../views/editQuiz.php';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"),$put_vars);
            try {
                $titulo = $put_vars['titulo'];
                $descricao = $put_vars['descricao'];
                $currentQuiz = $this->quiz->getQuizById($quizId);
                if(
                    !($titulo === $currentQuiz->titulo) ||
                    !($descricao === $currentQuiz->descricao)
                ) {
                    $updateQuiz = $this->quiz->putQuiz($currentQuiz->id, $titulo, $descricao);
                    if($updateQuiz) {
                        http_response_code(200);
                        echo 'Quiz atualizado com sucesso!';
                        exit;
                    } else {
                        http_response_code(400);
                        echo 'Erro ao atualizar quiz!';
                    }
                }
            } catch(Exception $e) {
                error_log('Erro ao atualizar quiz! - ' . $e->getMessage());
                http_response_code(500);
                echo 'Erro no servidor ao atualizar pergunta!';
                exit;
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


    public function answerQuiz($quizId) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!is_logged()) {
                header('Location: /login');
                exit;
            }

            $quiz = $this->quiz->getQuizById($quizId);

            if($quiz) {
                require_once __DIR__ . '/../views/answerQuiz.php';
            } else {
                header('Location: /quiz');
                http_response_code(400);
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // https://www.php.net/manual/en/function.file-get-contents.php
                $data = json_decode(file_get_contents("php://input"));
                // Verificar integridade dos dados
                if (!$data || !isset($data->quizId) || !isset($data->respostas)) {
                    http_response_code(400);
                    echo 'Dados inválidos';
                    exit;
                }
                $quiz = $this->quiz->getQuizById($data->quizId);
                $questions = $this->quiz->getQuizQuestions($quiz->id);

                $totalQuestions = count($questions);
                $userScore = 0;
                
                foreach($data->respostas as $resposta) {
                    $pergunta = $this->quiz->getQuestionById($resposta->pergunta_id);
                    if ($pergunta && $pergunta->resposta_certa_id == $resposta->resposta_selecionada_id) {
                        $userScore++;
                    }
                }
                
                $percentage = ($userScore / $totalQuestions) * 100;
                
                $userId = get_session()['id'];

                $insertedScore = $this->quiz->newScore($userId, $quiz->id, $userScore, $totalQuestions);
                
                if($insertedScore) {
                    http_response_code(200);
                    echo 'Você acertou ' . $percentage . '% do quiz!';
                    exit;
                } else {
                    http_response_code(400);
                    echo 'Erro inserir pontuação!';
                    exit;
                }

            } catch(Exception $e) {
                error_log('Erro ao enviar quiz! - ' . $e->getMessage());
                http_response_code(500);
                echo 'Erro no servidor ao submeter quiz!';
                exit;
            }
        }
    }
}