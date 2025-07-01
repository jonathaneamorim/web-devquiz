<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tarefas</title>
</head>
<body>
    <h1>Tarefas</h1>
    <a href="/create">Nova Tarefa</a>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <?= htmlspecialchars($task['title']) ?>
                <a href="/edit/<?= $task['id'] ?>">Editar</a>
                <a href="/delete/<?= $task['id'] ?>">Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>