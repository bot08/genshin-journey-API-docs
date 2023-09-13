<?php
// Подключаемся к базе данных SQLite (создаст файл, если он не существует)
$db = new SQLite3('comments.db');

// Создаем таблицу, если она еще не существует
$query = "CREATE TABLE IF NOT EXISTS comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT,
    avatar_id INTEGER,
    comment TEXT,
    branch TEXT
)";
$db->exec($query);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получаем данные из GET-запроса & проверка
    $usernameRaw = isset($_GET['username']) ? $_GET['username'] : '';
    $avatar_idRaw = isset($_GET['avatar_id']) ? (int)$_GET['avatar_id'] : 0;
    $commentRaw = isset($_GET['comment']) ? $_GET['comment'] : '';
    $branchRaw = isset($_GET['branch']) ? $_GET['branch'] : '';
    
    // Фильтр
    $username = filter_var($usernameRaw, FILTER_SANITIZE_STRING);
    $avatar_id = filter_var($avatar_idRaw, FILTER_SANITIZE_STRING);
    $comment = filter_var($commentRaw, FILTER_SANITIZE_STRING);
    $branch = filter_var($branchRaw, FILTER_SANITIZE_STRING);
    
    
    // Проверяем длину данных и типы
    if (strlen($username) > 255 || 
        strlen($username) < 5 ||
        strlen($comment) > 1000 || 
        strlen($comment) < 8 || 
        !is_string($branch) ||
        !is_numeric($avatar_id)) 
    {
        $db->close();
        http_response_code(400);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        die('Bad data');
    }

    // Вставляем данные в таблицу
    $query = "INSERT INTO comments (username, avatar_id, comment, branch) 
              VALUES ('$username', $avatar_id, '$comment', '$branch')";
    $db->exec($query);

    // Получаем ID новой записи
    $comment_id = $db->lastInsertRowID();

    // Заголовки
    http_response_code(200);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    // Возвращаем ID комментария в виде JSON
    echo json_encode(['comment_id' => $comment_id]);
}

// Закрываем соединение с базой данных
$db->close();
?>
