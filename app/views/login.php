<?php 
    include_once __DIR__ . '/commons/default.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Login'); ?>
<body>
    <?php echo get_header(); ?>
    
    
    <div class="w-100 d-flex flex-column align-items-center mt-5">
        <div id="mensagem" class="m-4 text-danger"></div>
        <h2>Login</h2>
        <form id="formLogin" class="w-25 border border-dark rounded-5 p-4">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" autocomplete="email" required>
                <label for="email">Email:</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Password" autocomplete="current-password" required>
                <label for="senha">Senha:</label>
            </div>

            <a href="/register" class="text-decoration-none text-black-50">Ainda não possui cadastro?</a><br>
            <button type="submit" class="btn btn-secondary mb-2 mt-2">Entrar</button>
        </form>
    </div>

<script>
    $("#formLogin").on('submit', function(e) {
        e.preventDefault();
        const form = $(this);

        const data = {
            email: form.find('input[name="email"]').val().toLowerCase(),
            senha: form.find('input[name="senha"]').val()
        }

        $.ajax({
            url: '/login',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: (data, textStatus, xhr) => {
                const message = data.message;
                if(xhr.status === 200) {
                    alert(message);
                    window.location.href = '/quiz';
                } else {
                    $('#mensagem').html(message);
                }
            },
            error: (xhr) => {
                let resposta = xhr.responseText || 'Erro inesperado.';
                $('#mensagem').html('<p>' + resposta + '</p>');
            }
        })
    })

    $('#formLogin').on('focusin', function(e) {
        $('#mensagem').html('');
    })
</script>

<?php echo get_scripts(); ?>
</body>
</html>