<?php
require_once __DIR__ . '/../AuthenticationSystem/auth_guard.php';
include 'functions.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = db_connect_mysql();

$poll = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';

if ($id) {
    $stmt = $mysqli->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $poll = $result->fetch_assoc();
    $stmt->close();
}

if (!$poll) {
    $error = 'Poll not found.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $stmt = $mysqli->prepare('DELETE FROM polls WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        header('Location: index.php');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}
?>

<?=template_header('Delete Poll')?>

<div class="content delete">
    <div class="card">
        <h2>Delete Poll</h2>
        <?php if ($error): ?>
            <p><?=htmlspecialchars($error, ENT_QUOTES)?></p>
            <a href="index.php" class="secondary">Back to Polls</a>
        <?php else: ?>
            <p>Are you sure you want to delete the poll titled <strong><?=htmlspecialchars($poll['title'], ENT_QUOTES)?></strong>?</p>
            <form method="post" class="form-actions">
                <button type="submit" name="confirm" value="yes">Yes, delete</button>
                <a href="index.php" class="secondary">Cancel</a>
            </form>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
