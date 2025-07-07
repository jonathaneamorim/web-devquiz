<?php 
    include_once __DIR__ . '/commons/default.php';
    include_once __DIR__ . '/../utils/helpers.php';

    // https://pt.stackoverflow.com/questions/103157/qual-%C3%A9-a-diferen%C3%A7a-entre-x-www-form-urlencoded-e-form-data
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/POST
    // Tipos de corpos de requisição application/x-www-form-urlencoded | multipart/form-data
    // application/x-www-form-urlencoded = campo1=valor1&campo2=valor2
    // multipart/form-data = Enviar arquivos + dados
    // Pesquisar

     // Criar um update pro usuario
    /*
        Fontes: https://api.jquery.com/serialize/
    */

    // https://stackoverflow.com/questions/16493280/close-bootstrap-modal
    // https://stackoverflow.com/questions/6653556/jquery-javascript-function-to-clear-all-the-fields-of-a-form
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Perfil'); ?>
<body>
    <?php echo get_header(); ?>

    <div class="w-100 d-flex flex-column align-items-center mt-5">
        <div class="border border-dark rounded-5 p-4">
            <div class="mb-3">
                <h2>Informações de usuário </h2>
            </div>
            <div>
                <p>Nome: <span id="userName"></span></p>
                <p>Email: <span id="userEmail"></span></p>
            </div>
            <div class="d-flex gap-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditUser">Editar</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAlterPassword">Alterar senha</button>
            </div>
        </div>
    </div>


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
        
    <div id="message"></div>
    <div id="quizList"></div>

    <script>
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
                        $('#modalEditUser').modal('toggle'); 
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

    </script>
    <?php echo get_scripts(); ?>
</body>
</html>