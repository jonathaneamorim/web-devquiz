<?php 
    include_once __DIR__ . '/commons/default.php';

    // Criar toasts de feedback pro usuário
    // Aparece e some depois de x segundos
?>

<!DOCTYPE html>
<html>
<?php echo get_head('DevQuiz - Editar Quiz'); ?>
<body class="w-100 h-100 p-0 m-0">
<?php echo get_header(); ?>

    <div class="w-100 d-flex flex-column align-items-center mt-5 mb-5">

        <div id="mensagem"></div>
        
        <div class="mb-3">
            <h2>Editar Quiz: <?php echo htmlspecialchars($quiz->titulo) ?></h2>
        </div>

        <div class="w-50 d-flex flex-column align-items-center">
            <div class="border border-dark rounded-5 p-4 w-75">
                <h3>Editar informações do quiz</h3>

                <form id="formEditQuiz">
                    <label for="titulo">Título do quiz:</label><br>
                    <input class="form-control" type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($quiz->titulo); ?>">

                    <label for="descricao" class="mt-3">Descrição do quiz:</label><br>
                    <input class="form-control" type="text" name="descricao" id="descricao" value="<?php echo htmlspecialchars($quiz->descricao); ?>">

                    <button type="submit" class="btn btn-secondary mt-3">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>

<div class="w-100 d-flex flex-column align-items-center">
    <h3>Perguntas do Quiz</h3>
</div>

<div id="perguntas-container" class="d-flex flex-column align-items-center mb-5">
    <div id="lista-de-perguntas" class="w-100 d-flex flex-column align-items-center"></div>
    <button type="button" id="btnNovaPergunta" class="btn btn-secondary mt-5">Nova pergunta +</button>
</div>

<script>
    let contadorPerguntas = 0;

    $(document).ready(() => {
        getAllQuestions();
    });

    $("#btnNovaPergunta").on('click', () => {
        addNewQuestion();
    });

    $("#formEditQuiz").on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serialize();
        $.ajax({
            url: `/quiz/edit/<?php echo htmlspecialchars($quiz->id) ?>`,
            type: 'PUT',
            data: data,
            success: (data, textStatus, xhr) => {
                if(xhr.status === 200) {
                    $('#mensagem').html(`<p style="color: green">Quiz editado com sucesso!</p>`);
                } else {
                    $('#mensagem').html(`<p style="color: red">${xhr.responseText}</p>`);
                }
            },
            error: (xhr) => {
                $('#mensagem').html(`<p style="color: red">${xhr.responseText}</p>`);
            }
        })
    })

    function addNewQuestion() {
        const novoFormulario = `
            <div class="pergunta-bloco w-50 border border-dark rounded-5 p-4">
                <h4>Nova Pergunta</h4>

                <label><b>Enunciado:</b></label>
                <input class="form-control border border-dark" type="text" name="pergunta" placeholder="Digite o enunciado">

                <div class="mt-5 p-3 border border-dark rounded-2">
                    <h3>Respostas</h3>
                    <label for="a">A: </label>
                    <input class="form-control border border-dark" type="text" name="a" placeholder="Resposta A">
                    <label for="b">B: </label>
                    <input class="form-control border border-dark" type="text" name="b" placeholder="Resposta B">
                    <label for="c">C: </label>
                    <input class="form-control border border-dark" type="text" name="c" placeholder="Resposta C">
                    <label for="d">D: </label>
                    <input class="form-control border border-dark" type="text" name="d" placeholder="Resposta D">
                </div>

                <div class="mb-3 mt-3">
                    <label><b>Resposta Correta:</b></label>
                    <select name="resposta_correta" class="form-select w-25 border border-dark">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>

                <button type="button" class="btn-save-question btn btn-secondary">Salvar pergunta</button>
            </div>
        `;
        $('#lista-de-perguntas').append(novoFormulario);
        $('#btnNovaPergunta').hide();
    }

    async function getAllQuestions() {
        try {
            $('#lista-de-perguntas').html('');

            const questions = await $.get(`/quiz/questions/<?php echo $quiz->id ?>`);
            if(questions) {
                let content = '';
                for (const question of questions) {
                    const answers = await getAnswers(question.id);
                    let answersContent = '';
                    let selectOptions = '';
                    const letters = ['a', 'b', 'c', 'd'];

                    for (let i = 0; i < 4; i++) {
                        const answer = answers[i] || {};
                        answersContent += `
                            <div class="form-group">
                                <label for="${letters[i]}">${letters[i].toUpperCase()}:</label>
                                <input class="form-control border border-dark" id="${answer.id}" type="text" name="${letters[i]}" value="${answer.texto || ''}" size="25">
                            </div>
                        `;

                        if(question.resposta_certa_id === answer.id) {
                            selectOptions += `
                                <option value="${answer.id || ''}" selected>${letters[i].toUpperCase()}</option>
                        `;
                        } else {
                            selectOptions += `
                                <option value="${answer.id || ''}">${letters[i].toUpperCase()}</option>
                            `;
                        }
                    }

                    content += `
                        <div class="pergunta-bloco w-50 border border-dark rounded-5 p-4 mb-3">
                            <h4>Pergunta</h4>

                            <label><b>Enunciado:</b></label>
                            <input class="form-control border border-dark" type="text" name="pergunta" value="${question.texto || ''}">
                            <input type="hidden" name="question_id" value="${question.id}">

                            <div class="mt-5 p-3 border border-dark rounded-2">
                                <h3>Respostas:</h3>
                                ${answersContent}
                            </div>

                            <div class="mb-3 mt-3">
                                <label><b>Resposta Correta:</b></label>
                                <select name="resposta_correta" class="form-select w-25 border border-dark" aria-label="respostas">${selectOptions}</select>
                            </div>

                            <button type="button" class="btn-save-question btn btn-secondary">Salvar pergunta</button>
                            <button type="button" class="btn-delete-question btn btn-secondary">Deletar pergunta</button>
                        </div>
                    `;
                }

                $('#lista-de-perguntas').html(content);
            }
        } catch (error) {
            console.error('Erro ao capturar questões: ', error);
        }
    }

    async function getAnswers(questionId) {
        try {
            const response = await $.get(`/quiz/answer/${questionId}`);
            return response || [];
        } catch (error) {
            console.error('Erro ao capturar respostas: ', error);
            return [];
        }
    }

    $(document).on('click', '.btn-save-question', function (e) {
        // https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
        // Método de Element que percorre o elemento e seus pais (indo em direção a raiz)
        // até encontrar uma correspondencia
        const form = $(this).closest('.pergunta-bloco');
        const questionId = form.find('input[name="question_id"]').val() || '';
        const isEdit = questionId ? true : false;

        const postData = {
            questionText: form.find('input[name="pergunta"]').val(),
            answer1: form.find('input[name="a"]').val(),
            answer2: form.find('input[name="b"]').val(),
            answer3: form.find('input[name="c"]').val(),
            answer4: form.find('input[name="d"]').val(),
            correctAnswer: form.find('select[name="resposta_correta"]').val()
        };

        const putData = {
            quizId: <?php echo $quiz->id ?>,
            questionId: questionId,
            questionText: form.find('input[name="pergunta"]').val(),
            answer1: {
                id: form.find('input[name="a"]').attr('id'),
                text: form.find('input[name="a"]').val(),
            },
            answer2: {
                id: form.find('input[name="b"]').attr('id'),
                text: form.find('input[name="b"]').val(),
            },
            answer3: {
                id: form.find('input[name="c"]').attr('id'),
                text: form.find('input[name="c"]').val(),
            },
            answer4: {
                id: form.find('input[name="d"]').attr('id'),
                text: form.find('input[name="d"]').val(),
            },
            correctAnswer: form.find('select[name="resposta_correta"]').val()
        }

        $.ajax({
            // https://www.php.net/manual/en/function.htmlspecialchars.php 
            url: '/quiz/edit/questions/<?php echo htmlspecialchars($quiz->id) ?>',
            type: isEdit ? 'PUT' : 'POST',
            data: isEdit ? putData : postData,
            success: (data, textStatus, xhr) => {
                if (xhr.status === 201 || xhr.status === 200) {
                    $('#mensagem').html(`<p style="color: green">Pergunta salva com sucesso!</p>`);
                    getAllQuestions();
                    $('#btnNovaPergunta').show();
                } else {
                    $('#mensagem').html(`<p style="color: red">Deu erro: ${data}</p>`);
                }
            },
            error: (xhr) => {
                $('#mensagem').html(`<p style="color: red">Erro: ${xhr.responseText}</p>`);
            }
        });
    });


    $(document).on('click', '.btn-delete-question', function (e) {
        const form = $(this).closest('.pergunta-bloco');
        const questionId = form.find('input[name="question_id"]').val();
        $.ajax({
            url: `/quiz/question/delete/${questionId}`,
            type: 'DELETE',
            success: (data, textStatus, xhr) => {
                if (xhr.status === 200) {
                    $('#mensagem').html(`<p style="color: green">${data}</p>`);
                    getAllQuestions();
                } else {
                    $('#mensagem').html(`<p style="color: red">${data}</p>`);
                }
            },
            error: (xhr) => {
                $('#mensagem').html(`<p style="color: red">Erro: ${xhr.responseText}</p>`);
            }
        });
    })

    $(document).on('focusin', 'input, select', function(e) {
        $('#mensagem').html('');
    })
</script>

<?php echo get_scripts(); ?>

</body>
</html>
