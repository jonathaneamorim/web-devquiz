<?php

require_once __DIR__ . '/../../utils/helpers.php';

function get_head($title) {
    return '
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.  $title . '</title>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        </head>
    ';
}

function get_scripts() {
    return '
        
    ';
}

// Separar strings da lógica
function get_header() {
    if(!is_logged()) {
        return '
            <header style="height: 8vh; width: 100vw;">
                <nav style="display: flex; justify-content: space-between;">
                    <div style="margin-left: 60px ;">
                        <h1>DevQuiz</h1>
                    </div>
                    <div style="display: flex; margin-right: 60px; align-items: center; gap: 10px;">
                        <div>
                            <a href="/login">login</a>
                        </div>
                        <div>
                            <a href="/register">cadastro</a>
                        </div>
                    </div>
                </nav>
            </header>
        ';
        exit();
    } else {
        $id = get_session()['id'];
        $is_admin = is_admin($id);
        if($is_admin) {
            return '
                <header style="height: 8vh; width: 100vw;">
                    <nav style="display: flex; justify-content: space-between;">
                        <div style="margin-left: 60px ;">
                            <h1>DevQuiz</h1>
                        </div>
                        <div style="display: flex; margin-right: 60px; align-items: center; gap: 10px;">
                            <div>
                                <a href="/perfil">Perfil</a>
                            </div>
                            <div>
                                <a href="/quiz/new">Criar um quiz</a>
                            </div>
                            <div>
                                <a href="/logout">Sair</a>
                            </div>
                        </div>
                    </nav>
                </header>
            ';
        } else {
            return '
                <header style="height: 8vh; width: 100vw;">
                    <nav style="display: flex; justify-content: space-between;">
                        <div style="margin-left: 60px ;">
                            <h1>DevQuiz</h1>
                        </div>
                        <div style="display: flex; margin-right: 60px; align-items: center; gap: 10px;">
                            <div>
                                <a href="/perfil">Perfil</a>
                            </div>
                            <div>
                                <a href="/logout">Sair</a>
                            </div>
                        </div>
                    </nav>
                </header>
            ';
        }
    }
}