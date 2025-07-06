<?php 
    include_once __DIR__ . '/commons/default.php';
    include_once __DIR__ . '/../utils/helpers.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Perfil'); ?>
<body>
    <?php echo get_header(); ?>



    <div>
        <p>Nome: <span id="userName"></span></p>
        <p>Email: <span id="userEmail"></span></p>
    </div>

            <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditUser">
        Editar
        </button>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAlterPassword">
        Alterar senha
        </button>

        <div class="modal fade" id="modalEditUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditUserLabel">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditUserLabel">Editar usuário</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editUserMessage"></div>
                    <form id="formEditUser">
                        <label for="email">Email: </label>
                        <input id="editUserEmailInput" type="email" name="email" placeholder="Email" autocomplete="email" required><br><br>
                        <label for="nome">Nome: </label>
                        <input id="editUserNameInput"  type="text" name="nome" placeholder="Nome" required><br><br>
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAlterPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAlterPasswordLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAlterPasswordLabel">Alterar senha</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="editPasswordMessage"></div>
                <form id="formAlterPassword">
                    <label for="currentPassword">Senha atual: </label><br>
                    <input type="password" name="currentPassword" placeholder="Senha atual" autocomplete="current-password" required><br><br>
                    
                    <label for="newPassword">Nova senha: </label><br>
                    <input type="password" name="newPassword" placeholder="Nova senha" required><br><br>
                    
                    <label for="confirmNewPassword">Confirmar nova senha: </label><br>
                    <input type="password" name="confirmNewPassword" placeholder="Confirme a nova senha" required><br><br>
                    
                    <button type="submit" class="btn btn-primary">Alterar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
        </div>

        <hr>
        
    <div id="message"></div>
    <div id="quizList"></div>

    <script>
        // Criar um update pro usuario
        /*
            Fontes: https://api.jquery.com/serialize/
        */

        $(document).ready(function() {
            updateUserData();
        });

        async function updateUserData() {
            const userData = await getUserData();
            $('#userName').html(userData.nome);
            $('#userEmail').html(userData.email);
            
            $('#editUserEmailInput').val(userData.email);
            $('#editUserNameInput').val(userData.nome);
        }

        async function getUserData() {
            try {
                const response = await $.get(`/perfil/data`);
                return response || {};
            } catch (error) {
                console.error('Erro ao capturar os dados do usuário: ', error);
                return {};
            }
        }

        // https://pt.stackoverflow.com/questions/103157/qual-%C3%A9-a-diferen%C3%A7a-entre-x-www-form-urlencoded-e-form-data
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/POST
        // Tipos de corpos de requisição application/x-www-form-urlencoded | multipart/form-data
        // application/x-www-form-urlencoded = campo1=valor1&campo2=valor2
        // multipart/form-data = Enviar arquivos + dados
        // Pesquisar
        $('#formAlterPassword').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const newPassword = form.find('input[name="newPassword"]').val();
            const confirmNewPassword = form.find('input[name="confirmNewPassword"]').val();

            if(newPassword === confirmNewPassword) {      
                const data = form.serialize();   
                $.ajax({
                    url: `/perfil/edit/password`,
                    type: 'POST',
                    data: data,
                    success: (data, textStatus, xhr) => {
                        if(xhr.status === 200) {
                            $('#modalAlterPassword').modal('toggle'); 
                            // https://stackoverflow.com/questions/6653556/jquery-javascript-function-to-clear-all-the-fields-of-a-form
                            $(this).trigger("reset");
                        } else {
                            $('#editPasswordMessage').html(`<p style="color: red">${data}</p>`);
                        }
                    },
                    error: (xhr, textStatus, errorThrown) => {
                        $('#editPasswordMessage').html(`<p style="color: red">Error: ${xhr.responseText}</p>`);
                    },
                });   
            } else {
                $('#editPasswordMessage').html(`<p style="color: red">Nova senha é diferente da confirmação!</p>`);
            }
        });

        $('#formEditUser').on('submit', function(e) {
            e.preventDefault();
            const data = $(this).serialize();
            $.ajax({
                url: `/perfil/edit/userdata`,
                type: 'POST',
                data: data,
                success: (data, textStatus, xhr) => {
                    if(xhr.status === 200) {
                        updateUserData();
                        // https://stackoverflow.com/questions/16493280/close-bootstrap-modal
                        $('#modalEditUser').modal('toggle'); 
                        // https://stackoverflow.com/questions/6653556/jquery-javascript-function-to-clear-all-the-fields-of-a-form
                        $(this).trigger("reset");
                    } else {
                        $('#editUserMessage').html(`<p style="color: red">${data}</p>`);
                    }
                },
                error: (xhr, textStatus, errorThrown) => {
                    $('#editUserMessage').html(`Error: ${textStatus}`);
                },
            });
        });


        <?php if(is_admin()) { ?>
            /*
             beforeSend: function (xhr) {
                console.log('Loading more posts...')
                button.text('Loading');
            }
            */

            $(document).ready(function() {
                listarQuizzesUsuario();
            });

            function listarQuizzesUsuario() {
                $("#quizList").html('');
                let list = '';
                $.ajax({
                    url: '/quiz/show',
                    type: 'GET',
                    dataType: 'json',
                    /*
                        Fontes: 
                            https://stackoverflow.com/questions/7638847/understanding-jquerys-jqxhr
                            https://www.sitepoint.com/jqxhr-object/
                        data: resposta do servidor
                        textStatus: string com o status ("success" ou "error")
                        xhr: objeto XMLHttpRequest com status, headers, etc.
                    */
                    success: (data, textStatus, xhr) => {
                        if(xhr.status === 200) {
                            $('#quizList').append('<div><h2>Lista de quizzes cadastrados</h2></div>');
                            data.forEach(quiz => {
                            let item = `
                                <div style="border: 1px solid black">
                                    <h2>${quiz.titulo}</h2>
                                    <p>${quiz.descricao}</p>
                                    <button><a href="/quiz/edit/${quiz.id}">Editar Quiz</a></button>
                                    <button class="btn-delete" value="${quiz.id}">Deletar</button>
                                </div>
                                `;
                            list += item;
                            });
                            $('#quizList').append(list);
                        } else if(xhr.status === 204) {
                            // 204 não retorna data
                            $('#quizList').html(`<div><h2>Sem quizzes cadastrados!</h2></div>`);
                        }
                    },
                    /*
                        xhr: objeto XMLHttpRequest
                        textStatus: string com o tipo do erro ("timeout", "error", "abort", etc)
                        errorThrown: mensagem do erro (string ou null) - Exceção tratada
                    */
                    error: (xhr, textStatus, errorThrown) => {
                        $('#quizList').html(`Error: ${errorThrown}`);
                    }
                })
            };

            $(document).on('click', '.btn-delete', function () {
                const id = $(this).val();
                $.ajax({
                    url: `/quiz/delete/${id}`,
                    type: 'DELETE',
                    success: (data, status) => {
                        alert(data);
                        listarQuizzesUsuario();
                    },  
                    error: (status, error) => {
                        $('#message').html('<p style="color: red">Erro ao deletar quiz!</p>');
                        console.error('Erro na requisição:', status, error);
                    }
                });
            });

           <?php } ?>

    </script>
    <?php echo get_scripts(); ?>
</body>
</html>