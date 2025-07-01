<?php

function setUserSession($userId, $nome, $email) {
    $_SESSION['usuario'] = [
        'id' => $userId, 
        'nome' => $nome,
        'email' => $email
    ];
}

function startSessionIfNotStarted() {
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}