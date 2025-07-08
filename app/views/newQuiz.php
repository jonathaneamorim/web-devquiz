<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Novo Quiz'); ?>
<body>
    <?php echo get_header(); ?>

    <div id=mensagem-erro></div>

    <div class="w-100 p-5 d-flex flex-column align-items-center mt-5">
        <div class="border border-dark rounded-5 p-4">
            <h2>Adicionar um novo quiz</h2>
            <form id="formNewQuiz">
                <label for="titulo">Titulo do quiz: </label>
                <input type="text" name="titulo" id="titulo" class="form-control mb-3">
                <label for="descricao">Descrição do quiz: </label>
                <input type="text" name="descricao" id="descricao" class="form-control">
                <button type="submit" class="btn btn-secondary mt-3">Criar novo quiz</button>
            </form>
        </div>
    </div>

    <script>
        $("#formNewQuiz").on('submit', (e) => {
            e.preventDefault();
            const form = $(e.currentTarget);
            $.ajax({
                url: '/quiz/new',
                type: 'POST',
                data: form.serialize(),
                success: (data, textStatus, xhr) => {
                    if(xhr.status === 201) {
                        data = JSON.parse(data);
                        alert(data.message);
                        window.location.href = `/quiz/edit/${data.quizId}`;
                    } else {
                        $('#mensagem-erro').html(data);
                    }
                },
                error: (xhr) => {
                    alert('Erro: ' + xhr.responseText);
                }
            })
        });
    </script>
</body>
</html>