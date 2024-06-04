<?php
// save_table.php
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['message' => 'Ошибка подключения к базе данных: ' . $conn->connect_error]));
}

// Очищаем таблицу перед добавлением новых данных
$sql = "DELETE FROM uch_plan";
$conn->query($sql);

foreach ($input as $row) {
    $date = $row['date'];
    $topic = $row['topic'];
    $hours = $row['hours'];
    $completed = $row['completed'] ? 1 : 0;

    $sql = "INSERT INTO uch_plan (date, topic, hours, completed) VALUES ('$date', '$topic', '$hours', '$completed')";

    if (!$conn->query($sql)) {
        echo json_encode(['message' => 'Ошибка: ' . $conn->error]);
        exit;
    }
}

$conn->close();
echo json_encode(['message' => 'Данные успешно сохранены']);
?>
