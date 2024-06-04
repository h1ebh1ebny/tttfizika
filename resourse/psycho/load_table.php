<?php
// load_table.php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['message' => 'Ошибка подключения к базе данных: ' . $conn->connect_error]));
}

$sql = "SELECT date, topic, hours, completed FROM uch_plan";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'date' => $row['date'],
            'topic' => $row['topic'],
            'hours' => $row['hours'],
            'completed' => $row['completed'] == 1
        ];
    }
}

$conn->close();
echo json_encode($data);
?>
