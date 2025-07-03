<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Novo Quiz'); ?>
<body>
    <?php echo get_header(); ?>

    <h2>Adicionar um novo quiz</h2>
    <form id="formNewQuiz">
        <label for="titulo">Titulo do quiz: </label><br>
        <input type="text" name="titulo" id="titulo"><br>
        <label for="descricao">Descrição do quiz: </label><br>
        <input type="text" name="descricao" id="descricao"><br><br>
        <button type="submit">Criar novo quiz</button>
    </form>

    <script>
        $("#formNewQuiz").on('submit', (e) => {
            e.preventDefault();
            const form = $(e.currentTarget);
            $.ajax({
                url: '/quiz/new',
                type: 'POST',
                data: form.serialize(),
                success: (data, xhr) => {
                    if(xhr.status === 201) {
                        alert(data);
                        window.location('/')
                    } else {
                        alert(xhr.status);
                        alert('Erro ao inserir: ', data);
                    }
                },
                error: (data) => {
                    alert('Erro: ' . data);
                }
            })
        });
    </script>
</body>
</html>