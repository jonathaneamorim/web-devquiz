<?php

require_once __DIR__ . '/../model/portal/UserModel.php';
require_once __DIR__ . '/../utils/helpers.php';

class AuthController {

    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(is_logged()) {
                header('Location: /quiz');
                exit;
            }
    
            require_once __DIR__ . '/../views/login.php';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $userFound = $this->model->findUserByEmail($email);
    
            if ($userFound) {
                // https://www.php.net/manual/en/function.password-verify.php
                if(password_verify($senha, $userFound->senha)) {
                    $_SESSION['usuario'] = [
                        'id' => $userFound->id,
                        'nome' => $userFound->nome,
                        'email' => $userFound->email,
                        'isAdmin' => $userFound->isAdmin
                    ];
    
                    http_response_code(200);
                    echo 'Usuário Logado!';
                    exit;
                } 
    
                http_response_code(401);
                echo 'Senha Incorreta!';
                exit;
            }
    
            http_response_code(404);
            echo 'Email de usuário não cadastrado!';
            exit;
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(is_logged()) {
                header('Location: /quiz');
                exit;
            }
    
            require_once __DIR__ . '/../views/cadastro.php';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            $userExists = $this->model->findUserByEmail($email);

            if($userExists) {
                http_response_code(400);
                echo 'Esse e-mail já está registrado!';
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email inválido!";
                http_response_code(400);
                exit;
            }

            if($this->model->addNewUser($nome, $email, $senha)) {
                $new_user = $this->model->findUserByEmail($email);
                set_session($new_user->id, $new_user->nome, $new_user->email);
                http_response_code(201);
                echo 'Cadastro realizado com sucesso!';
                exit;
            } else {
                http_response_code(400);
                echo 'Erro ao cadastrar usuário!';
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}