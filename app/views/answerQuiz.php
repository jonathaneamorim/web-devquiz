<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - '. $quiz->titulo); ?>
<body>
    <?php echo get_header(); ?>

    <section class="w-100 d-flex flex-column align-items-center mt-5 mb-5">
        <div>
            <h2 id="quizTitle"></h2>
            <p id="quizDescription"></p>
        </div>
    </section>

    <div id="mensagem"></div>

    <div id="questions" class="d-flex flex-column w-100 align-items-center">

    </div>

    <div class="d-flex m-3 justify-content-center">
        <button id="finish" class="btn btn-primary">Finalizar e enviar</button>
    </div>
    
    <script> 

        $(document).ready(() => {
            renderQuizData();
        });


        // async functions
        async function renderQuizData() {
            const quizData = await getQuizData();
            const questions = await getQuestions();
            let content = '';

            // Render quiz data
            if(quizData) {
                $('#quizTitle').html(quizData.titulo);
                $('#quizDescription').html(quizData.descricao);
            } else {
                $('#mensagem').html('<p style="color: red">Erro ao capturar informações do quiz!</p>')
            }
            
            // Render quiz Questions
            if(questions) {
                for (const question of questions) {
                    const answers = await getAnswers(question.id);
                    let answersContent = '';
                    let selectContent = '';
                    selectContent += `
                        <option value="" selected></option>
                    `;
                    const letters = ['a', 'b', 'c', 'd'];
                    const shuffledAnswers = shuffle(answers);

                    for(let i = 0; i < shuffledAnswers.length; i++) {
                        const answer = shuffledAnswers[i] || {};
                        answersContent += `
                            <label for="${letters[i]}">${letters[i].toUpperCase()}: </label>
                            <input class="form-control" id="${answer.id || ''}" type="text" name="${letters[i]}" value="${answer.texto || ''}" size="25" disabled><br>
                        `;
                        selectContent += `
                            <option value="${answer.id || ''}">${letters[i].toUpperCase()}</option>
                        `;
                    }

                    content += `
                        <div class="w-50 p-3 border border-dark rounded-5 p-3">
                            <h3 id="${question.id}">${question.texto}</h3>

                            <div>
                                ${answersContent}
                            </div>

                            <label>Resposta:</label>
                            <select name="resposta" class="form-select w-25 border border-dark">${selectContent}</select><br>

                        </div>
                    `;
                }
            }

            $('#questions').html(content);
        }

        $('#finish').on('click', async function(e) {
            const quizData = await getQuizData();
            const data = {
                quizId: quizData.id,
                respostas: []
            };

            $('#questions > div').each(function () {
                // https://api.jquery.com/find/ 
                const questionId = $(this).find('h3').attr('id');
                const selectedAnswerId = $(this).find('select[name="resposta"]').val();

                data.respostas.push({
                    pergunta_id : questionId,
                    resposta_selecionada_id: selectedAnswerId
                })

            })

            if (data.respostas.length === 0) {
                alert('Responda pelo menos uma questão!');
                return;
            }
            
            $.ajax({
                url: '/quiz/responder/<?php echo $quiz->id ?>',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: (data, statusText, xhr) => {
                    if(xhr.status === 200) {
                        alert(data);
                        window.location.href = '/quiz';
                    } else {
                        $('#mensagem').html('Erro ao submeter quiz: ', data);
                    }
                },
                error: (xhr) => {
                    $('#mensagem').html(`<p style="color: red">Erro ao enviar quiz: ${xhr.responseText}</p>`);
                }
            });
            
        })

        // Embaralhador de array
        // Fonte: https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
        function shuffle(array) {
            // Docs spread - Espalhar itens de um array ou objeto (combinar, copiar, passar valores individualmente)
            // https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Reference/Operators/Spread_syntax
            const newArray = [...array];
            // o -1 se dá ao fato do length nao considerar o 0
            for (let i = newArray.length - 1; i > 0; i--) {
                // Acha um numero aleatorio dentro do intervalo do array
                const j = Math.floor(Math.random() * (i + 1));
                // Posição inicial i recebe uma nova posição J (random) 
                // Se cair no mesmo número ou repetir numero vai continar a mesma coisa
                [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
            }
            return newArray;
        }
            

        // Requests
        async function getQuizData() {
            try {
                const response = await $.get(`/quiz/show/<?php echo $quiz->id; ?>`);
                return response || {}; 
            } catch (error) {
                console.error('Erro ao capturar dados do quiz: ', error);
                return [];
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

        async function getQuestions() {
            try {
                const response = await await $.get(`/quiz/questions/<?php echo $quiz->id; ?>`);
                return response || [];
            } catch (error) {
                console.error('Erro ao capturar respostas: ', error);
                return [];
            }
        }

    </script>

    <?php echo get_scripts(); ?>
</body>
</html>