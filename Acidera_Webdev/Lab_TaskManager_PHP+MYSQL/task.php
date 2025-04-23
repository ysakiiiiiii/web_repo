<?php
$conn = new mysqli("localhost", "root", "root", "dbtasks");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert task
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["task"])) {
    $stmt = $conn->prepare("INSERT INTO task (task) VALUES (?)");
    $stmt->bind_param("s", $_POST["task"]);
    $stmt->execute();
    $stmt->close();
    header("Location: task.php");
    exit;
}

// Toggle task status
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $conn->prepare("UPDATE task SET status = NOT status WHERE task_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: task.php");
    exit;
}

// Delete task
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM task WHERE task_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: task.php");
    exit;
}

// Fetch tasks from the Databse
$result = $conn->query("SELECT * FROM task ORDER BY task_ID DESC");
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$result->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>TO-DO LIST</h1>

        <form method="post">
            <input type="text" name="task" placeholder="Enter Task" required>
            <button type="submit">Add Task</button>
        </form>

        <div class="task-list">
            <?php foreach ($tasks as $task): ?>
                <div class="task-item <?= $task['status'] == 0 ? 'done' : '' ?>">
                    <div class="task-text"><?= htmlspecialchars($task['task']) ?></div>
                    <div class="actions">
                        <form method="get">
                            <input type="hidden" name="toggle" value="<?= $task['task_ID'] ?>">
                            <button type="submit" class="done-btn">
                                <?= $task['status'] == 0 ? 'Undo' : 'Mark as Done' ?>
                            </button>
                        </form>
                        <form method="get" onsubmit="return confirm('Delete this task?');">
                            <input type="hidden" name="delete" value="<?= $task['task_ID'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
