<?php
session_start(); 

$filename = 'todo.txt';

// dito mag aadd ng task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        
        file_put_contents($filename, $task . PHP_EOL, FILE_APPEND);

        $_SESSION['message'] = 'Task added successfully!';
        header("Location: gr5_masuelaklarenzglen_ex3.php");
        exit;
    }
}

// dito naman delete ng task
if (isset($_GET['delete'])) {
    $indexToDelete = $_GET['delete'];

    if (file_exists($filename)) {
        $tasks = file($filename, FILE_IGNORE_NEW_LINES);

        if (isset($tasks[$indexToDelete])) {
            unset($tasks[$indexToDelete]);

            file_put_contents($filename, implode(PHP_EOL, $tasks) . PHP_EOL);
        }
    }

    $_SESSION['message'] = 'Task deleted successfully!';
    header("Location: gr5_masuelaklarenzglen_ex3.php");
    exit;
}

// dito yung file exist :)
$tasks = [];
if (file_exists($filename)) {
    $tasks = file($filename, FILE_IGNORE_NEW_LINES);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <h1>To-Do List</h1>
    <form method="POST" action="">
        <input type="text" name="task" placeholder="Add a new task" required>
        <button type="submit">Add Task</button>
    </form>

    <h2>Your Tasks:</h2>
    <ul>
        <?php if (empty($tasks)): ?>
            <li>No tasks yet.</li>
        <?php else: ?>
            <?php foreach ($tasks as $index => $task): ?>
                <?php if (!empty(trim($task))): ?>
                    <li>
                        <?php echo htmlspecialchars($task); ?>
                        <button class="delete-btn" data-index="<?php echo $index; ?>">&#10060;</button>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- custom JavaScript -->
    <script src="script.js"></script>

    <?php
    // display SweetAlert message
    if (isset($_SESSION['message'])) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "success",
                    title: "' . htmlspecialchars($_SESSION['message']) . '",
                    confirmButtonText: "OK"
                });
            });
        </script>';
        unset($_SESSION['message']); 
    }
    ?>
</body>
</html>
