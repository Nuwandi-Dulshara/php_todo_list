<?php
require 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
        $sql = "INSERT INTO tasks (user_id, task) VALUES ('$user_id', '$task')";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $task_id = $_POST['task_id'];
        $sql = "DELETE FROM tasks WHERE id='$task_id'";
        $conn->query($sql);
    } elseif (isset($_POST['complete'])) {
        $task_id = $_POST['task_id'];
        $sql = "UPDATE tasks SET is_completed=1 WHERE id='$task_id'";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $task_id = $_POST['task_id'];
        $task = $_POST['task'];
        $sql = "UPDATE tasks SET task='$task' WHERE id='$task_id'";
        $conn->query($sql);
    }
}

$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>To-Do List</h2>
    <form action="tasks.php" method="POST">
        <input type="text" name="task" required placeholder="New Task">
        <button type="submit">Add Task</button>
    </form>
    <ul>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <li data-task-id="<?php echo $row['id']; ?>">
                <?php if ($row['is_completed']) : ?>
                    <s class="task-text"><?php echo $row['task']; ?></s>
                <?php else : ?>
                    <span class="task-text"><?php echo $row['task']; ?></span>
                <?php endif; ?>
                <form action="tasks.php" method="POST" style="display:inline;">
                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                    <?php if (!$row['is_completed']) : ?>
                        <button type="submit" name="complete">Complete</button>
                        <button type="button" class="edit">Edit</button>
                    <?php endif; ?>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
    <script src="js/scripts.js"></script>
</body>
</html>
