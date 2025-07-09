<?php

require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../model/portal/UserModel.php';

class UserController {
    protected $user;
    protected $quiz;

    public function __construct() {
        $this->user = new UserModel();
        $this->quiz = new QuizModel();
    }

    public function index() {
        $quizzes = $this->quiz->getQuizzesByUser(get_session()['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(!is_logged()) {
                header('Location: /login');
                http_response_code(403);
                exit;
            }

            require_once __DIR__ . '/../views/profile.php';
        }
    }

    public function getUserData() {
        try {
            $userId = get_session()['id'];
            $user = $this->user->findById($userId);
            if($user) {
                $userData = [
                    'nome' => $user->nome,
                    'email' => $user->email
                ];
                header('Content-Type: application/json');
                http_response_code(200);
                echo json_encode($userData);
                exit;
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar dados do usuário! - ' . $e->getMessage());
            http_response_code(500);
            echo 'Erro ao capturar dados do usuário!';
            exit;
        }
    }

    public function getScoreByUser() {
        try {
            $userId = get_session()['id'];
            $userScores = $this->user->getUserScore($userId);
    
            if ($userScores && count($userScores) > 0) {
                $quizIds = [];
                $quizzes = [];
    
                foreach ($userScores as $score) {
                    if (!in_array($score->quizId, $quizIds)) {
                        $quiz = $this->quiz->getQuizById($score->quizId);
                        if ($quiz) {
                            $quizzes[] = $quiz;
                            $quizIds[] = $score->quizId;
                        }
                    }
                }
    
                $response = [
                    'userScore' => $userScores,
                    'quiz' => $quizzes
                ];
    
                header('Content-Type: application/json');
                http_response_code(200);
                echo json_encode($response);
                exit;
            } else {
                http_response_code(204);
                exit;
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar score do usuário! - ' . $e->getMessage());
            http_response_code(500);
            echo 'Erro ao capturar dados do usuário!';
            exit;
        }
    }

    public function edit() {
        try {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = get_session()['id'];
                $nome = $_POST['nome'];
                $email = $_POST['email'];

                $nameRegex = '/^.{5,}$/';

                if(!preg_match($nameRegex, $nome)) {
                    echo 'O nome precisa ter 5 caracteres ou mais!';
                    http_response_code(400);
                    exit;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo 'Email com formato inválido!';
                    http_response_code(400);
                    exit;
                }
                
                $currentUserData = $this->user->findById($userId);
    
                if(
                    !($nome === $currentUserData->nome) ||
                    !($email === $currentUserData->email)
                ) {
                    $updateUser = $this->user->updateUserData($userId, $nome, $email);

                    if($updateUser) {
                        set_session($userId, $nome, $email);
                        http_response_code(200);
                        echo 'Dados atualizados com sucesso!';
                        exit;
                    } else {
                        http_response_code(200);
                        echo 'Erro ao atualizar dados!';
                        exit;
                    }
                } else {
                    http_response_code(200);
                    echo 'Nenhuma ação necessária!';
                    exit;
                }
            }
        } catch(Exception $e) {
            error_log('Erro ao atualizar dados: ', $e->getMessage());
            http_response_code(400);
            echo 'Erro ao atualizar dados!';
            exit;
        }
    }

    public function updatePassword() {
        try {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = get_session()['id'];
                $currentPassword = trim($_POST['currentPassword']);
                $newPassword = trim($_POST['newPassword']);
                
                $userFound = $this->user->findById($userId);

                if(!password_verify($currentPassword, $userFound->senha))  {
                    http_response_code(403);
                    echo 'Senha atual incorreta!';
                    exit;
                }
                
                if(password_verify($newPassword, $userFound->senha)) {
                    http_response_code(403);
                    echo 'A nova senha não pode ser igual a senha antiga!';
                    exit;
                }
                
                $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
                // (?=.*[a-z]) - pelo menos uma letra minúscula
                // (?=.*[A-Z]) - pelo menos uma letra maiúscula
                // (?=.*\d) - pelo menos um número
                // (?=.*[\W_]) - pelo menos um caractere especial (qualquer símbolo que não seja letra ou número)
                // .{8,} - mínimo de 8 caracteres
                
                if(!preg_match($passwordRegex, $newPassword)) {
                    http_response_code(403);
                    echo 'A senha deve conter letra minúscula e maiúscula, número, caractere especial e deve conter 8 caracteres ou mais';
                    exit;
                }

                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $updatePassword = $this->user->updatePassword($userId, $newPassword);
                if($updatePassword) {
                    http_response_code(200);
                    echo 'Senha atualizada!';
                    exit;
                } else {
                    http_response_code(400);
                    echo 'Erro ao atualizar senha!';
                    exit;
                }
            }
        } catch(Exception $e) {
            error_log('Erro ao atualizar senha: ' . $e->getMessage());
            http_response_code(500);
            echo 'Erro interno do servidor!';
            exit;
        }
    }
}
?>