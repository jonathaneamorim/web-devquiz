<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
<?php echo get_head('DevQuiz - Editar Quiz'); ?>
<body>
<?php echo get_header(); ?>

<div id="mensagem"></div>

<h2>Editar Quiz: <?php echo htmlspecialchars($quiz->titulo) ?></h2>

<form id="formEditQuiz">
    <label for="titulo">Título do quiz:</label><br>
    <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($quiz->titulo); ?>"><br>

    <label for="descricao">Descrição do quiz:</label><br>
    <input type="text" name="descricao" id="descricao" value="<?php echo htmlspecialchars($quiz->descricao); ?>"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>

<h3>Perguntas do Quiz</h3>

<div id="perguntas-container">
    <div id="lista-de-perguntas"></div>
    <button type="button" id="btn-nova-pergunta">Nova pergunta +</button>
</div>

<script>
    let contadorPerguntas = 0;

    $(document).ready(() => {
        getAllQuestions();
    });

    $("#btn-nova-pergunta").on('click', () => {
        addNewQuestion();
    });

    function addNewQuestion() {
        const novoFormulario = `
        <form>
            <div class="pergunta-bloco" style="border: 1px solid black; padding: 15px; margin-top: 10px; border-radius: 5px;">
                <h4>Nova Pergunta</h4>

                <label><b>Enunciado:</b></label><br>
                <input type="text" name="pergunta" placeholder="Digite o enunciado" size="50"><br><br>

                <label><b>Respostas:</b></label><br>
                <input type="text" name="a" placeholder="Resposta A" size="25"><br>
                <input type="text" name="b" placeholder="Resposta B" size="25"><br>
                <input type="text" name="c" placeholder="Resposta C" size="25"><br>
                <input type="text" name="d" placeholder="Resposta D" size="25"><br><br>

                <label><b>Resposta Correta:</b></label>
                <select name="resposta_correta">
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select><br><br>

                <button type="button" class="btn-save-question">Salvar pergunta</button>
            </div>
        </form>
        `;
        $('#lista-de-perguntas').append(novoFormulario);
        $('#btn-nova-pergunta').hide();
    }

    async function getAllQuestions() {
        try {
            $('#lista-de-perguntas').html('');

            const questions = await $.get(`/quiz/questions/<?php echo $quiz->id ?>`);
            let content = '';

            for (const question of questions) {
                const answers = await getAnswers(question.id);
                let answersContent = '';
                let selectOptions = '';
                const letters = ['a', 'b', 'c', 'd'];

                for (let i = 0; i < 4; i++) {
                    const answer = answers[i] || {};
                    answersContent += `
                        <input id="${answer.id}" type="text" name="${letters[i]}" value="${answer.texto || ''}" size="25"><br>
                    `;
                    selectOptions += `
                        <option value="${answer.id || ''}">${letters[i].toUpperCase()}</option>
                    `;
                }

                content += `
                    <form>
                        <div class="pergunta-bloco" style="border: 1px solid black; padding: 15px; margin-top: 10px; border-radius: 5px;">
                            <h4>Pergunta</h4>

                            <label><b>Enunciado:</b></label><br>
                            <input type="text" name="pergunta" size="50" value="${question.texto || ''}">
                            <input type="hidden" name="question_id" value="${question.id}"><br><br>

                            <label><b>Respostas:</b></label><br>
                            ${answersContent}<br>

                            <label><b>Resposta Correta:</b></label>
                            <select name="resposta_correta">${selectOptions}</select><br><br>

                            <button type="button" class="btn-save-question">Salvar pergunta</button>
                            <button type="button" class="btn-delete-question">Deletar pergunta</button>
                        </div>
                    </form>
                `;
            }

            $('#lista-de-perguntas').html(content);
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
        // Pesquisar o que é o closest
        const form = $(this).closest('form');
        const questionId = form.find('input[name="question_id"]').val() || '';
        const isEdit = questionId ? true : false;

        const postData = {
            quizId: <?php echo $quiz->id ?>,
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
            url: '/quiz/edit/<?php echo $quiz->id ?>',
            type: isEdit ? 'PUT' : 'POST',
            data: isEdit ? putData : postData,
            success: (data, textStatus, xhr) => {
                if (xhr.status === 201 || xhr.status === 200) {
                    $('#mensagem').html(`<p style="color: green">Pergunta salva com sucesso!</p>`);
                    getAllQuestions();
                    $('#btn-nova-pergunta').show();
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
        const form = $(this).closest('form');
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
</script>
</body>
</html>
