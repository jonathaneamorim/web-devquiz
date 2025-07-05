<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/app/controller/AuthController.php';
require_once __DIR__ . '/app/controller/QuizController.php';
require_once __DIR__ . '/app/controller/UserController.php';
require_once __DIR__ . '/app/utils/helpers.php';

startSessionIfNotStarted();

$authController = new AuthController();
$userController = new UserController();
$quizController = new QuizController();

// fazer tratamento para remover a '/' do final caso houver
// Pega a URI sem a query string (ex: /quiz/show de /quiz/show?page=2)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/') {
    header('Location: /login');

} elseif ($uri === '/login') {
    $authController->login();

} elseif ($uri === '/register') {
    $authController->register();

} elseif ($uri === '/quiz') {
    $quizController->index();

} elseif ($uri === '/quiz/show') {
    $quizController->getAllQuizzes();

} elseif (preg_match('/^\/quiz\/edit\/(\d+)$/', $uri, $matches)) {
    // O ID capturado estará em $matches[1]
    $id = $matches[1];
    // Chama a função no controller, passando o ID
    $quizController->edit($id);

} elseif ($uri === '/quiz/new') {
    $quizController->new();

} elseif ($uri === '/quiz/questions') {
    // $quizController->new();

} elseif (preg_match('/^\/quiz\/delete\/(\d+)$/', $uri, $matches)) {
    $quizId = $matches[1];
    $quizController->delete($quizId);

} elseif (preg_match('/^\/quiz\/answer\/(\d+)$/', $uri, $matches)) {
    $perguntaId = $matches[1];
    $quizController->getAnswer($perguntaId);

} elseif (preg_match('/^\/quiz\/questions\/(\d+)$/', $uri, $matches)) {
    $quizId = $matches[1];
    $quizController->getQuestions($quizId);

} elseif (preg_match('/^\/quiz\/question\/delete\/(\d+)$/', $uri, $matches)) {
    $questionId = $matches[1];
    $quizController->deleteQuestion($questionId);

} elseif ($uri === '/perfil') {
    $userController->index();

} elseif ($uri === '/logout') {
    $authController->logout();

} else {
    http_response_code(404);
    echo "404 Not Found";
}