<?php 
    include_once __DIR__ . '/commons/default.php';
    include_once __DIR__ . '/../utils/helpers.php';


    /*
        Fontes: 
            https://stackoverflow.com/questions/7638847/understanding-jquerys-jqxhr
            https://www.sitepoint.com/jqxhr-object/
            https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/isArray
        data: resposta do servidor
        textStatus: string com o status ("success" ou "error")
        xhr: objeto XMLHttpRequest com status, headers, etc.
    */

    /*
        beforeSend: function (xhr) {
        console.log('Loading more posts...')
        button.text('Loading');
    }
    */
    
    /*
        xhr: objeto XMLHttpRequest
        textStatus: string com o tipo do erro ("timeout", "error", "abort", etc)
        errorThrown: mensagem do erro (string ou null) - Exceção tratada
    */

    // Adicionar verificação para deletar quiz (tem certeza que deseja deletar quiz?)
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Quizzes'); ?>
<body>
    <?php echo get_header(); ?>

    <?php 
        if(is_admin()) {
            echo '
                <section class="w-100 p-5">
                    <div class="border border-info rounded-5 p-4">
                        <div class="w-100 text-center">
                            <h2>Seção de administrador</h2>
                        </div>
                        <div id="admin-session-quiz" class="d-flex gap-3 flex-wrap justify-content-center"></div>
                    </div>
                </section>
            ';
        }
    ?>

    <section class="mb-5 w-100 p-5">
        <div class="border border-info rounded-5 p-4">
            <div class="w-100 text-center">
                <h2>Quizzes para responder</h2>
            </div>
            <div class="d-flex justify-content-center gap-3 flex-wrap" id="listQuiz"></div>
        </div>
    </section>

    <script>
        $(document).ready(async function() {
            await listQuizzes();
            setInterval(await getQuizzes, 20000);
        });

        async function listQuizzes() {
            try {
                const quizzes = await getQuizzes();
                if(quizzes) {
                    const items = quizzes.map(quiz => `
                        <div class="card" style="width: 18rem;">
                            <img src="data:image/jpeg;base64,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" class="card-img-top" alt="avestruz">
                            <div class="card-body">
                                <h5 class="card-title">${quiz.titulo}</h5>
                                <p class="card-text">${quiz.descricao}</p>
                                <a href="quiz/responder/${quiz.id}" class="btn btn-primary">Responder agora!</a>
                            </div>
                        </div>
                    `).join('');
                    $('#listQuiz').html(items);
                } else {
                    $('#listQuiz').html(`<p>Não existem quizzes para responder!</p>`);
                }
            } catch(error) {
                $('#listQuiz').html('<p>Erro interno do servidor ao carregar quizzes!</p>');
                console.error('Erro na requisição:', error);
            }
        }

        async function getQuizzes() {
            try {   
                const response = await $.get('/quiz/show');
                return response || '';
            } catch(error) {
                console.error('Erro ao capturar quizzes: ', error);
                return '';
            }
        }

        <?php if(is_admin()) { ?>

           $(document).ready(async function() {
               await listQuizzesAdmin();
           });

            async function listQuizzesAdmin() {
                try {
                    $("#admin-session-quiz").html('');
                    const quizzes = await getQuizzes();
                    let list = '';
                    if(quizzes) {
                        quizzes.forEach(quiz => {
                        let item = `
                            <div class="card" style="width: 18rem;">
                                <img src="data:image/jpeg;base64,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" class="card-img-top" alt="avestruz">
                                <div class="card-body">
                                    <h5 class="card-title">${quiz.titulo}</h5>
                                    <p class="card-text">${quiz.descricao}</p>
                                    <a href="/quiz/edit/${quiz.id}" class="btn btn-primary">Editar</a>
                                    <a data-id="${quiz.id}" class="btn-delete btn btn-primary">Deletar</a>
                                </div>
                            </div>
                            `;
                        list += item;
                        });
                        $('#admin-session-quiz').append(list);
                    } else {
                        $('#admin-session-quiz').html(`<div><p>Sem quizzes cadastrados!</p></div>`);
                    }
                } catch(error) {
                    $('#admin-session-quiz').html(`${error}`);
                }
            }

            // https://www.w3schools.com/tags/att_data-.asp 
            $(document).on('click', '.btn-delete', function () {
                const id = $(this).attr('data-id');
                $.ajax({
                    url: `/quiz/edit/${id}`,
                    type: 'DELETE',
                    success: async function(data, status) {
                        alert(data);
                        await listQuizzes();
                        await listQuizzesAdmin();
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