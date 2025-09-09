<?php
require 'UserController.php';


header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$data = json_decode(file_get_contents('php://input'), true);

// // print_r($uri);ы
// // print_r($data);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$controller = new UserController();

if ($uri[0] === 'users') {
    $id = $uri[1] ?? null;

    switch ($method) {
        case 'POST':
            if (isset($uri[1]) && $uri[1] === 'login') {
                echo json_encode($controller->login($data));
            } else {
                echo json_encode($controller->create($data));
            }
            break;
        case 'GET':
            if ($id) {
                echo json_encode($controller->get($id));
            } else {
                echo json_encode(['error' => 'ID пользователя обязателен']);
            }
            break;
        case 'PUT':
            if ($id) {
                echo json_encode($controller->update($id, $data));
            }
            else {
                echo json_encode(['error' => 'ID пользователя обязателен']);
            }
            break;
        case 'DELETE':
            if ($id) {
                echo json_encode($controller->delete($id));
            }
            break;
        default:
            echo json_encode(['error' => 'Метод не поддерживается']);
    }
}
