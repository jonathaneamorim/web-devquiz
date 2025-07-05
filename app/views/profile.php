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
        <p>Nome: <?php echo get_session()['nome'] ?></p>
        <p>Email: <?php echo get_session()['email'] ?></p>
    </div>

    <div id="message"></div>
    <div id="quizList"></div>

    <script>

        <?php if(is_admin(get_session()['id'])) { ?>
            $(document).ready(function() {
                listarQuizzesUsuario();
            });

            function listarQuizzesUsuario() {
                let list = '';
                $.ajax({
                    url: '/quiz/show',
                    type: 'GET',
                    dataType: 'json',
                    success: (data, status) => {
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
                        $('#quizList').html(list);
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
</body>
</html>