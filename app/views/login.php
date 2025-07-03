<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Login'); ?>
<body>
    <?php echo get_header(); ?>
    
    <div id="mensagem-erro" style="color: red;"></div>

    <form id="formLogin">
        <input type="email" name="email" placeholder="Email" autocomplete="email" required><br><br>
        <input type="password" name="senha" placeholder="Senha" autocomplete="current-password" required><br><br>
        <a href="/register">Ainda não possui cadastro?</a><br>
        <button type="submit">Entrar</button>
    </form>

<script>
    $("#formLogin").on('submit', (e) => {
        e.preventDefault();
        const form = $(e.currentTarget);
        $.ajax({
            url: '/login',
            type: 'POST',
            data: form.serialize(),
            success: (data, textStatus, xhr) => {
                if(xhr.status === 200) {
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