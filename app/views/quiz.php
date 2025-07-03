<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Quizzes'); ?>
<body>
    <?php echo get_header(); ?>

    <div id="listQuiz"></div>

    <script>
        $(document).ready(function() {
            getQuizzes();
            setInterval(getQuizzes, 5000);
            function getQuizzes() {
                $.ajax({
                    url: '/quiz/show',
                    type: 'GET',
                    dataType: 'json',
                    success: (data) => {
                        if(Array.isArray(data)) {
                            const items = data.map(quiz => `<li>${quiz.titulo}</li>`).join('');
                            $('#listQuiz').html(items);
                        } else {
                            $('#listQuiz').html('<li>Nenhum quiz encontrado.</li>');
                        }
                    },
                    error: (xhr, status, error) => {
                        $('#listQuiz').html('<p>Erro ao carregar quizzes!</p>');
                        console.error('Erro na requisição:', status, error);
                    }
            })
            }
        }) 
    </script>
</body>
</html>