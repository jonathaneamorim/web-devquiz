<?php

require_once __DIR__ . '/../../utils/helpers.php';

function get_head($title) {
    return '
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.  $title . '</title>

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        </head>
    ';
}

function get_scripts() {
    return '
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    ';
}

function get_header() {
    $notLogged = '
            <li>
                <a href="/login" class="text-decoration-none text-black fs-5">Login</a>
            </li>
            <li>
                <a href="/register" class="text-decoration-none text-black fs-5">Cadastro</a>
            </li>
    ';

    $is_admin = '
            <li>
                <a href="/quiz" class="text-decoration-none text-black fs-5">Lobby</a>
            </li>
            <li>
                <a href="/perfil" class="text-decoration-none text-black fs-5">Perfil</a>
            </li>
            <li>
                <a href="/quiz/new" class="text-decoration-none text-black fs-5">Criar um quiz</a>
            </li>
            <li>
                <a href="/logout" class="text-decoration-none text-black fs-5">Sair</a>
            </li>
    ';

    $defaultUser = '
            <li>
                <a href="/quiz" class="text-decoration-none text-black fs-5">Lobby</a>
            </li>
            <li>
                <a href="/perfil" class="text-decoration-none text-black fs-5">Perfil</a>
            </li>
            <li>
                <a href="/logout" class="text-decoration-none text-black fs-5">Sair</a>
            </li>
    ';

    $content = '
        <header style="height: 8vh; width: 100vw;">
            <nav style="display: flex; justify-content: space-between;">
                <div style="margin-left: 60px ;">
                    <h1>DevQuiz</h1>
                </div>
            </nav>
        </header>
    ';

    if(!is_logged()) {
        $navContent = $notLogged;
    }  else {
        $navContent = is_admin() ? $is_admin : $defaultUser;
    }

    $content = '
        <header style="height: 10vh;" class="w-100 border-bottom border-dark">
            <nav class="d-flex justify-content-around align-items-center h-100">
                <div class="">
                    <a href="/quiz" class="text-decoration-none text-black">
                        <h1>DevQuiz</h1>
                    </a>
                </div>

                <div class="">
                    <ul class="list-unstyled p-0 m-0 d-flex gap-4">
                        ' . $navContent . '
                    </ul>
                </div>
            </nav>
        </header>
    ';

    return $content;
}