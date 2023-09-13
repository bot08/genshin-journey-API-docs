<?php
// Подключаемся к базе данных SQLite
$db = new SQLite3('comments.db');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получаем ветку из GET-запроса
    $branch = $_GET['branch'];

    // Выбираем все комментарии с заданной веткой
    $query = "SELECT * FROM comments WHERE branch = '$branch' ORDER BY id DESC";
    $result = $db->query($query);

    $comments = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $comments[] = $row;
    }

    // Заголовки
    http_response_code(200);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    // Возвращаем комментарии в виде JSON
    echo json_encode($comments);
}

// Закрываем соединение с базой данных
$db->close();
?>