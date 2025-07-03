<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Cadastro'); ?>
<body>
    <?php echo get_header(); ?>

    <div id="mensagem-erro" style="color: red;"></div>

    <form id="formCadastro">
        <input type="text" name="nome" placeholder="Nome completo" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="senha" placeholder="Senha" required><br><br>
        <a href="/login">Já possui cadastro?</a><br>
        <button type="submit">Cadastrar</button>
    </form>
    
    <script>
        $("#formCadastro").on('submit', (e) => {
            e.preventDefault();
            const form = $(e.currentTarget);
            $.ajax({
                url: '/register',
                type: 'POST',
                data: form.serialize(),
                success: (data, textStatus, xhr) => {
                    if(xhr.status === 201) {
                        alert(data);
                        window.location.href = '/quiz';
                    } else {
                        $('#mensagem-erro').html(data);
                    }
                },
                error: (xhr) => {
                    let resposta = xhr.responseText || 'Erro inesperado.';
                    $('#mensagem-erro').html('<p>' + resposta + '</p>');
                }
            })
        })
    </script>
</body>
</html>