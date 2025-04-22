<?php
$conn = new mysqli("localhost", "root", "root", "dbtasks");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["insert_task"])) {
    $task = $_POST["task"];
    $stmt = $conn->prepare("INSERT INTO task (task) VALUES (?)");
    $stmt->bind_param("s", $task);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $conn->query("UPDATE task SET status = NOT status WHERE task_ID = $id");
    header("Location: task.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM task WHERE task_ID = $id");
    header("Location: task.php");
    exit;
}

$result = $conn->query("SELECT * FROM task ORDER BY task_ID DESC");
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS file -->

</head>
<body>
    <div">
    <h1>TO-DO LIST</h1>

    <form method="post">
        <input type="text" name="task" placeholder="Enter Task" required>
        <button type="submit" name="insert_task">Add Task</button>
    </form>
    </div>

   
    <div>
    <h2>Tasks</h2>
    <table >
        <tr>
            <th>Tasks</th>
            <th style="color: green;">Status</th>
            <th style="color: red;">Delete Task</th>
        </tr>
        <?php foreach ($tasks as $task): ?>
            <tr class="<?= $task['status'] ? 'done' : 'notdone' ?>">
                <td>
                <?php if ($task['status']){ ?>
                <?= htmlspecialchars($task['task']) ?>
                <?php } else { ?>
                <s> <?=htmlspecialchars($task['task']) ?> </s>
                <?php } ?>
                </td>
                
                <td>
                <?php if ($task['status']){ ?>
                <a id="burat" href="?toggle=<?= $task['task_ID'] ?>">Mark as Done</a>
                <?php } else { ?>
                <p> done </p>
                <?php } ?>
                </td>

                <td>
                <a href="?delete=<?= $task['task_ID'] ?>" onclick="return confirm('Confirm Yes to delete this task?');">Delete</a>
               </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>

</body>
</html>