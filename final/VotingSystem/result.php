<?php
require_once __DIR__ . '/../AuthenticationSystem/auth_guard.php';
include 'functions.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = db_connect_mysql();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$poll = null;
$answers = [];
$total_votes = 0;
$error = '';

if ($id) {
    $stmt = $mysqli->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $poll = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($poll) {
        $stmt = $mysqli->prepare('SELECT id, title, votes FROM poll_answers WHERE poll_id = ? ORDER BY id');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $answers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $total_votes = array_sum(array_column($answers, 'votes'));
    }
}

if (!$poll) {
    $error = 'Poll not found.';
}
?>

<?=template_header('Results')?>

<div class="content result">
    <?php if ($error): ?>
    <div class="card error">
        <p><?=htmlspecialchars($error, ENT_QUOTES)?></p>
        <a href="index.php" class="pill-button outline-button">Back to polls</a>
    </div>
    <?php else: ?>
    <div class="card">
        <h2><?=htmlspecialchars($poll['title'], ENT_QUOTES)?></h2>
        <?php if ($poll['description']): ?>
        <p><?=nl2br(htmlspecialchars($poll['description'], ENT_QUOTES))?></p>
        <?php endif; ?>
        <p class="muted">Total votes: <?=$total_votes?></p>
        <div class="result-list">
            <?php foreach ($answers as $answer): 
                $percentage = $total_votes > 0 ? round(($answer['votes'] / $total_votes) * 100) : 0;
            ?>
            <div class="result-row">
                <div class="result-info">
                    <strong><?=htmlspecialchars($answer['title'], ENT_QUOTES)?></strong>
                    <span><?=$answer['votes']?> vote<?= $answer['votes'] === 1 ? '' : 's'?> (<?=$percentage?>%)</span>
                </div>
                <div class="progress-bar">
                    <span style="width: <?=$percentage?>%;"></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (!$answers): ?>
            <p class="muted">No answer options were found for this poll.</p>
            <?php endif; ?>
        </div>
        <div class="form-actions">
            <a href="index.php" class="pill-button outline-button">Back to polls</a>
            <a href="vote.php?id=<?=$poll['id']?>" class="pill-button create-poll">Vote again</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
