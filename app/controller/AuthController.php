<?php

namespace app\controller;

use app\model\User;

class AuthController {

    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    // Lista todas as tarefas
    public function index() {
        $tasks = $this->model->getAllTasks();
        require_once __DIR__ . '/../views/tasks/index.php';
    }

    // Exibe formulário de criação
    public function create() {
        require_once __DIR__ . '/../views/login.php';
    }

    // Salva uma nova tarefa
    public function store() {
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

            if($this->model->addNewUser($nome, $email, $senha)) {
                setUserSession();
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

    // Exibe formulário de edição
    public function edit($id) {
        // (Implemente a busca da tarefa por ID)
        require_once __DIR__ . '/../views/tasks/edit.php';
    }

    // Atualiza uma tarefa
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateTask($id, $_POST['title'], $_POST['description']);
            header('Location: /');
        }
    }

    // Deleta uma tarefa
    public function delete($id) {
        $this->model->deleteTask($id);
        header('Location: /');
    }
}
?>