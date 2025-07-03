<?php 
    include_once __DIR__ . '/commons/default.php';
    include_once __DIR__ . '/../utils/helpers.php';
?>

<!DOCTYPE html>
<html>
    <?php echo get_head('DevQuiz - Login'); ?>
<body>
    <?php echo get_header(); ?>

    <div>
        <p>Nome: <?php echo get_session()['nome'] ?></p>
        <p>Email: <?php echo get_session()['email'] ?></p>
    </div>

    <?php
        if(is_admin(get_session()['id'])) {
            echo '<p>Quizzes criados: </p>';
        }
    ?>
</body>
</html>