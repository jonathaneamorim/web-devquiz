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
                if(password_verify($senha, $userFound->senha)) {
                    $_SESSION['usuario'] = [
                        'id' => $userFound->id,
                        'nome' => $userFound->nome,
                        'email' => $userFound->email,
                        'isAdmin' => $userFound->isAdmin
                    ];
    
                    http_response_code(200);
                    echo json_encode(['message' => 'Usuario logado!']);
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

            $userExists = $this->model->findUserByEmail($email);

            if($userExists) {
                http_response_code(400);
                echo 'Esse e-mail já está registrado!';
                exit;
            }

            $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
            // (?=.*[a-z]) - pelo menos uma letra minúscula
            // (?=.*[A-Z]) - pelo menos uma letra maiúscula
            // (?=.*\d) - pelo menos um número
            // (?=.*[\W_]) - pelo menos um caractere especial (qualquer símbolo que não seja letra ou número)
            // .{8,} - mínimo de 8 caracteres

            if(!preg_match($passwordRegex, $_POST['senha'])) {
                http_response_code(400);
                echo 'A senha deve conter letra minúscula e maiúscula, número, caractere especial e deve conter 8 caracteres ou mais';
                exit;
            }

            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);


            $addNewUser = $this->model->addNewUser($nome, $email, $senha);

            if($addNewUser) {
                $new_user = $this->model->findUserByEmail($email);
                set_session($new_user->id, $new_user->nome, $new_user->email, $new_user->isAdmin);
                http_response_code(201);
                echo json_encode(['message' => 'Cadastro realizado com sucesso!']);
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