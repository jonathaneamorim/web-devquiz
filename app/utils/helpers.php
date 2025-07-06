<?php

require_once __DIR__ . '/../model/portal/UserModel.php';

function set_session($userId, $nome, $email, $isAdmin) {
    $_SESSION['usuario'] = [
        'id' => $userId, 
        'nome' => $nome,
        'email' => $email,
        'isAdmin' => $isAdmin
    ];
}

function startSessionIfNotStarted() {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function get_session() {
    return is_logged() ? $_SESSION['usuario'] : '';
}

function is_logged() {
    return isset($_SESSION['usuario']);
}


function dd($var) {
    var_dump($var);
    die();
}

function is_admin() {
    return $_SESSION['usuario']['isAdmin'];
}

// spl_autoload_register(function ($class) {
//     // Define o diretório base onde estão os arquivos das classes
//     $baseDir = __DIR__ . '/app/';
    
//     // Remove o prefixo 'app\' do namespace para gerar o caminho relativo
//     $class = str_replace('app\\', '', $class);
    
//     // Converte separadores de namespace '\' em separadores de diretório '/'
//     $path = $baseDir . str_replace('\\', '/', $class) . '.php';

//     // Se o arquivo existir, inclui ele no script
//     if (file_exists($path)) {
//         require_once $path;
//     }
// });