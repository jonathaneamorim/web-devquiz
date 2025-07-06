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
                echo json_encode($userData);
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar dados do usuário! - ' . $e->getMessage());
        }
    }

    public function edit() {
        try {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = get_session()['id'];
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                
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
                $userFound = $this->user->findById($userId);
                $currentPassword = $_POST['currentPassword'];
                $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                if(password_verify($currentPassword, $userFound->senha))  {
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
                } else {
                    http_response_code(403);
                    echo 'Senha atual incorreta!';
                    exit;
                }
            }
        } catch(Exception $e) {
            error_log('Erro ao capturar quizzes desse administrador! ' . $e->getMessage());
            http_response_code(400);
            echo 'Erro interno do servidor! - ' . $e->getMessage();
            exit;
        }
    }
}
?>