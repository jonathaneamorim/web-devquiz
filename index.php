<?php
require __DIR__ . '/app/utils/helpers.php';
startSessionIfNotStarted();

use app\controller\AuthController;

$controller = new UserController();
// $action = $_GET['action'] ?? 'index';
// $id = $_GET['id'] ?? null;

// if ($action === 'index') {
//     $controller->index();
// } elseif ($action === 'create') {
//     $controller->create();
// } elseif ($action === 'store') {
//     $controller->store();
// } elseif ($action === 'edit' && $id) {
//     $controller->edit($id);
// } elseif ($action === 'update' && $id) {
//     $controller->update($id);
// } elseif ($action === 'delete' && $id) {
//     $controller->delete($id);
// }