<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/app/controller/AuthController.php';
require_once __DIR__ . '/app/controller/QuizController.php';
require_once __DIR__ . '/app/controller/UserController.php';
require_once __DIR__ . '/app/utils/helpers.php';

startSessionIfNotStarted();

$uri = $_SERVER['REQUEST_URI'];

$authController = new AuthController();
$userController = new UserController();
$quizController = new QuizController();

switch ($uri) {
    case '/':
        header('Location: /login');
        break;
    case '/login':
        $authController->login();
        break;
    case '/register':
        $authController->register();
        break;
    case '/quiz':
        $quizController->index();
        break;
    case '/quiz/show':
        $quizController->show();
        break;
    case '/quiz/new':
        $quizController->new();
        break;
    case '/perfil':
        $userController->index();
        break;
    case '/logout':
        $authController->logout();
    break;

    default:
      http_response_code(404);
      echo "404 Not Found";
}