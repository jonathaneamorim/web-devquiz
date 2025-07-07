<?php 
    include_once __DIR__ . '/commons/default.php';

    // https://www.w3schools.com/jsref/event_onfocusin.asp 
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Cadastro'); ?>
<body>
    <?php echo get_header(); ?>

    
    <div class="w-100 d-flex flex-column align-items-center mt-5">
        <div id="mensagem"></div>
        <h2>Cadastre-se</h2>
        <form id="formCadastro" class="w-25 border border-dark rounded-5 p-4">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Joelson Neto" required>
                <label for="nome">Nome:</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="joelson@gmail.com" required>
                <label for="email">Email:</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                <label for="senha">Senha:</label>
            </div>

            <a href="/login" class="text-decoration-none text-black-50">Já possui cadastro?</a><br>
            <button type="submit" class="btn btn-secondary mb-2 mt-2">Cadastrar</button>
        </form>
    </div>
    
    <script>
        // Funções this não possuem o proprio this
        $("#formCadastro").on('submit', function (e) {
            e.preventDefault();
            const form = $(this);

            const data = {
                nome: form.find('input[name="nome"]').val(),
                email: form.find('input[name="email"]').val().toLowerCase(),
                senha: form.find('input[name="senha"]').val(),
            }

            $.ajax({
                url: '/register',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: (data, textStatus, xhr) => {
                    const message = data.message;
                    if(xhr.status === 201) {
                        alert(message);
                        window.location.href = '/quiz';
                    } else {
                        $('#mensagem').html(`<p class="text-danger">${message}</p>`);
                    }
                },
                // Sempre que a requisição voltar um 400 ou 500 cai aqui
                error: (xhr) => {
                    const message = xhr.responseText
                    let resposta = xhr.responseText || 'Erro inesperado.';
                    $('#mensagem').html('<p class="text-danger">' + resposta + '</p>');
                }
            })
        })

        $('#formCadastro').on('focusin', function(e) {
            $('#mensagem').html('');
        })
    </script>
</body>
</html>