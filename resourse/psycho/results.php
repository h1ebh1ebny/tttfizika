<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка, аутентифицирован ли пользователь
if (!isset($_SESSION['user_id']) || !isset($_SESSION['num_grupp'])) {
/*    header("Location: login.php");*/
    exit;
}

$num_grupp = $_SESSION['num_grupp'];

// Извлечение результатов тестирования пользователей группы
$sql = "SELECT u.username, r.correct_answers, r.total_questions
        FROM results r
        JOIN users u ON r.user_id = u.id_user
        WHERE u.num_grupp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $num_grupp);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
} else {
    echo "No results found for this group.";
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты тестирования</title>
</head>
<body>
    <link rel="stylesheet" type="text/css" href="../css/test.css">
    <h1>Результаты тестирования для группы <?php echo htmlspecialchars($num_grupp); ?></h1>
    <?php if (!empty($results)): ?>
        <table border="1">
            <tr>
                <th>Имя пользователя</th>
                <th>Правильные ответы</th>
                <th>Всего вопросов</th>
            </tr>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['username']); ?></td>
                    <td><?php echo htmlspecialchars($result['correct_answers']); ?></td>
                    <td><?php echo htmlspecialchars($result['total_questions']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <a href="login.php">Logout</a>
</body>
</html>
