<?php

require_once __DIR__ . '/controller/AuthController.php';
require_once __DIR__ . '/controller/QuizController.php';
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/utils/helpers.php';

startSessionIfNotStarted();

$authController = new AuthController();
$userController = new UserController();
$quizController = new QuizController();

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
    require_once __DIR__ . '/views/pageNotFound.php';
}