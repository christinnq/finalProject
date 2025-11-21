<?php
require_once __DIR__ . '/../AuthenticationSystem/auth_guard.php';
include 'functions.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = db_connect_mysql();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$poll = null;
$answers = [];
$errors = [];
$success = false;

if ($id) {
    $stmt = $mysqli->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $poll = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($poll) {
        $stmt = $mysqli->prepare('SELECT id, title FROM poll_answers WHERE poll_id = ? ORDER BY id');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $answers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

if (!$poll) {
    $errors[] = 'Poll not found.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$errors) {
    $answer_id = isset($_POST['answer']) ? (int)$_POST['answer'] : 0;
    $valid_answer = array_filter($answers, fn($answer) => (int)$answer['id'] === $answer_id);

    if (!$valid_answer) {
        $errors[] = 'Select one of the available answers.';
    } else {
        $stmt = $mysqli->prepare('UPDATE poll_answers SET votes = votes + 1 WHERE id = ? AND poll_id = ?');
        $stmt->bind_param('ii', $answer_id, $id);
        $stmt->execute();
        $stmt->close();
        $success = true;
    }
}
?>

<?=template_header('Vote')?>

<div class="content vote">
    <div class="card">
        <?php if ($errors): ?>
            <div class="card error">
                <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=htmlspecialchars($error, ENT_QUOTES)?></li>
                <?php endforeach; ?>
                </ul>
                <a href="index.php" class="pill-button outline-button">Back to polls</a>
            </div>
        <?php elseif ($success): ?>
            <h2>Thanks for voting!</h2>
            <div class="form-actions">
                <a href="result.php?id=<?=$poll['id']?>" class="pill-button create-poll">View results</a>
                <a href="index.php" class="pill-button outline-button">Back to polls</a>
            </div>
        <?php else: ?>
            <h2><?=htmlspecialchars($poll['title'], ENT_QUOTES)?></h2>
            <?php if ($poll['description']): ?>
                <p><?=nl2br(htmlspecialchars($poll['description'], ENT_QUOTES))?></p>
            <?php endif; ?>
            <form method="post" class="poll-form">
                <div class="form-group">
                    <?php foreach ($answers as $answer): ?>
                    <label class="answer-option">
                        <input type="radio" name="answer" value="<?=$answer['id']?>">
                        <span><?=htmlspecialchars($answer['title'], ENT_QUOTES)?></span>
                    </label>
                    <?php endforeach; ?>
                    <?php if (!$answers): ?>
                    <p class="muted">No answer options defined for this poll.</p>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <a href="index.php" class="secondary">Cancel</a>
                    <input type="submit" value="Submit vote">
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
