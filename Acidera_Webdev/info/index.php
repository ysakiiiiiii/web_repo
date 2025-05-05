<?php
$file = 'tasks.txt';

// Load tasks using fread()
$tasks = [];
if (file_exists($file)) {
    $handle = fopen($file, "r");
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);

    $lines = explode("\n", trim($contents));
    foreach ($lines as $line) {
        if (!empty($line)) {
            list($id, $text, $status) = explode('|', $line);
            $tasks[] = ['task_ID' => $id, 'task' => $text, 'status' => $status];
        }
    }
}

// Add task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $id = time();
    $text = str_replace(["\n", "|"], "", $_POST['task']);
    $status = 1;
    $line = "$id|$text|$status\n";

    $handle = fopen($file, "a");
    fwrite($handle, $line);
    fclose($handle);

    header("Location: index.php");
    exit;
}

// Toggle task
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    foreach ($tasks as &$task) {
        if ($task['task_ID'] == $id) {
            $task['status'] = $task['status'] == 1 ? 0 : 1;
            break;
        }
    }
    saveTasks($file, $tasks);
    header("Location: index.php");
    exit;
}

// Delete task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $tasks = array_filter($tasks, fn($t) => $t['task_ID'] != $id);
    saveTasks($file, $tasks);
    header("Location: index.php");
    exit;
}

// Save function using fwrite
function saveTasks($file, $tasks) {
    $handle = fopen($file, "w");
    foreach ($tasks as $task) {
        $line = "{$task['task_ID']}|{$task['task']}|{$task['status']}\n";
        fwrite($handle, $line);
    }
    fclose($handle);
}
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
