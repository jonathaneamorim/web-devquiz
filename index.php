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

// Se tiver muita rota, usar if-else fica poluído. 
// switch-case com regex pode ajudar. 
// Mas como ja tem o preg_match pontualmente, tá aceitável por enquanto.

// Fontes:
//      https://www.php.net/manual/pt_BR/function.preg-match.php
//      https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Reference/Status 
//      https://stackoverflow.com/questions/9114565/jquery-appending-a-div-to-body-the-body-is-the-object 
//      https://www.php.net/manual/pt_BR/function.rtrim.php  - rtrim — Retira espaços em branco (ou outros caracteres) do final de uma string

// Remove a barra final com o rtrim
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') $uri = '/';

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

} elseif (preg_match('/^\/quiz\/show\/(\d+)$/', $uri, $matches)) {
    $quizId = $matches[1];
    $quizController->getQuizById($quizId);

} elseif (preg_match('/^\/quiz\/edit\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    $quizController->edit($id);

}elseif (preg_match('/^\/quiz\/edit\/questions\/(\d+)$/', $uri, $matches)) {
    $quizId = $matches[1];
    $quizController->editQuestion($quizId);

} elseif ($uri === '/quiz/new') {
    $quizController->new();

} elseif (preg_match('/^\/quiz\/answer\/(\d+)$/', $uri, $matches)) {
    $perguntaId = $matches[1];
    $quizController->getAnswer($perguntaId);

} elseif (preg_match('/^\/quiz\/questions\/(\d+)$/', $uri, $matches)) {
    $quizId = $matches[1];
    $quizController->getQuestions($quizId);

} elseif (preg_match('/^\/quiz\/question\/delete\/(\d+)$/', $uri, $matches)) {
    $questionId = $matches[1];
    $quizController->deleteQuestion($questionId);

} elseif (preg_match('/^\/quiz\/responder\/(\d+)$/', $uri, $matches)) {
    $quizId = $matches[1];
    $quizController->answerQuiz($quizId);

}elseif ($uri === '/perfil') {
    $userController->index();

} elseif ($uri === '/perfil/data') {
    $userController->getUserData();

} elseif ($uri === '/perfil/edit/userdata') {
    $userController->edit();

}elseif ($uri === '/perfil/edit/password') {
    $userController->updatePassword();

} elseif ($uri === '/perfil/userscore') {
    $userController->getScoreByUser();

} elseif ($uri === '/logout') {
    $authController->logout();

} else {
    http_response_code(404);
    require_once __DIR__ . '/app/views/pageNotFound.php';
}