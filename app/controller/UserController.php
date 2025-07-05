<?php

require_once __DIR__ . '/../utils/helpers.php';

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

    // // Lista todas as tarefas
    // public function index() {
    //     $tasks = $this->model->getAllTasks();
    //     require_once __DIR__ . '/../views/tasks/index.php';
    // }

    // // Exibe formulário de criação
    // public function create() {
    //     require_once __DIR__ . '/../views/tasks/create.php';
    // }

    // // Salva uma nova tarefa
    // public function store() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $this->model->addTask($_POST['title'], $_POST['description']);
    //         header('Location: /');
    //     }
    // }

    // // Exibe formulário de edição
    // public function edit($id) {
    //     // (Implemente a busca da tarefa por ID)
    //     require_once __DIR__ . '/../views/tasks/edit.php';
    // }

    // // Atualiza uma tarefa
    // public function update($id) {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $this->model->updateTask($id, $_POST['title'], $_POST['description']);
    //         header('Location: /');
    //     }
    // }

    // // Deleta uma tarefa
    // public function delete($id) {
    //     $this->model->deleteTask($id);
    //     header('Location: /');
    // }
}
?>