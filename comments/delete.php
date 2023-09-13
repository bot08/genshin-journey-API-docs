<?php
// Подключаемся к базе данных SQLite
$db = new SQLite3('comments.db');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   // Обработка запроса на удаление комментария по его ID
    $comment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $secret = isset($_GET['secret']) ? (int)$_GET['secret'] : 0;

    if ($comment_id <= 0 || $secret != 1111) {
        $db->close();
        http_response_code(400);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        die('Bad ID or secret');
    }

    $query = "DELETE FROM comments WHERE id = $comment_id";
    $db->exec($query);
    
    // Заголовки
    http_response_code(200);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    // Ответ
    echo json_encode(['message' => 'Delete success']);
}

// Закрываем соединение с базой данных
$db->close();
?>