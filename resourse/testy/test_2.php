<?php
session_start();

define('TEST_ID', 2); // Задайте номер теста

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

// Получение идентификатора текущего пользователя
if (!isset($_SESSION['user']['id_user'])) {
    die("Пользователь не аутентифицирован.");
}

$user_id = $_SESSION['user']['id_user'];

// Извлечение вопросов и ответов из базы данных
$sql = "SELECT q.id as question_id, q.text as question_text, o.id as answer_id, o.text as answer_text, o.prav
        FROM question q
        JOIN otvety o ON q.id = o.question_id
        WHERE q.id > 20 and q.id < 41";
$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[$row['question_id']]['text'] = $row['question_text'];
        $questions[$row['question_id']]['answers'][] = [
            'id' => $row['answer_id'],
            'text' => $row['answer_text'],
            'prav' => $row['prav']
        ];
    }
} else {
    echo "Нет результатов";
    $conn->close();
    exit;
}
$conn->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $correctAnswers = $_POST['correct_answers'];
    $totalQuestions = $_POST['total_questions'];
    $testId = $_POST['test_id'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Подготовка SQL запроса
    $stmt = $conn->prepare("INSERT INTO results (user_id, correct_answers, total_questions, test_id) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Привязка параметров
    $stmt->bind_param("iiii", $userId, $correctAnswers, $totalQuestions, $testId);
    if ($stmt->execute()) {
        echo "Результат успешно сохранен.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="UTF-8">
    <title>Тест</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
  <script id="MathJax-script" async
          src="https://cdn.jsdelivr.net/npm/mathjax@3.0.1/es5/tex-mml-chtml.js">
  </script>
</head>
<body>
    <div class="card">
    <link rel="stylesheet" type="text/css" href="../css/test.css">
    <form id="test-form">

        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" id="test_id" name="test_id" value="<?php echo htmlspecialchars(TEST_ID); ?>">
        <?php foreach ($questions as $question_id => $question): ?>
            <div class="question">
                <p><?php echo htmlspecialchars($question['text']); ?></p>
                <?php foreach ($question['answers'] as $answer): ?>
                    <label>
                        <input type="radio" name="question_<?php echo htmlspecialchars($question_id); ?>" value="<?php echo htmlspecialchars($answer['id']); ?>">
                        <?php echo htmlspecialchars($answer['text']); ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <!-- <div > -->
        <button type="button" class="button" onclick="checkAnswers()">Проверить ответы</button>
    <!-- </div> -->
    </form>

    <div id="result"></div>

    <script>
        function checkAnswers() {
            let correctAnswers = <?php echo json_encode(array_reduce($questions, function($carry, $question) {
                foreach ($question['answers'] as $answer) {
                    if ($answer['prav']) {
                        $carry[$answer['id']] = true;
                    }
                }
                return $carry;
            }, [])); ?>;
            let form = document.getElementById('test-form');
            let resultDiv = document.getElementById('result');
            let formData = new FormData(form);
            let correctCount = 0;

            formData.forEach((value, key) => {
                if (correctAnswers[value]) {
                    correctCount++;
                }
            });

            let totalQuestions = Object.keys(<?php echo json_encode($questions); ?>).length;
            let userId = document.getElementById('user_id').value;
            let testId = document.getElementById('test_id').value;

            resultDiv.innerHTML = "Правильных ответов: " + correctCount + " из " + totalQuestions;

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send("user_id=" + encodeURIComponent(userId) + "&correct_answers=" + encodeURIComponent(correctCount) + "&total_questions=" + encodeURIComponent(totalQuestions) + "&test_id=" + encodeURIComponent(testId));
        }
    </script>
</div>
</body>
</html>
