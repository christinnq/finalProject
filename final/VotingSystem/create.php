<?php
require_once __DIR__ . '/../AuthenticationSystem/auth_guard.php';
include 'functions.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = db_connect_mysql();

$errors = [];
$title = '';
$description = '';
$answers_input = ['', '', ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $answers_input = array_map('trim', $_POST['answers'] ?? []);

    $answers_clean = array_values(array_filter($answers_input, fn($answer) => $answer !== ''));

    if ($title === '') {
        $errors[] = 'Please provide a title for the poll.';
    }
    if (count($answers_clean) < 2) {
        $errors[] = 'Add at least two answer options.';
    }

    if (!$errors) {
        $mysqli->begin_transaction();
        try {
            $stmt = $mysqli->prepare('INSERT INTO polls (title, description) VALUES (?, ?)');
            $stmt->bind_param('ss', $title, $description);
            $stmt->execute();
            $poll_id = $stmt->insert_id;
            $stmt->close();

            $stmt = $mysqli->prepare('INSERT INTO poll_answers (poll_id, title) VALUES (?, ?)');
            foreach ($answers_clean as $answer) {
                $stmt->bind_param('is', $poll_id, $answer);
                $stmt->execute();
            }
            $stmt->close();

            $mysqli->commit();
            header('Location: index.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            $mysqli->rollback();
            $errors[] = 'Something went wrong while saving your poll. Please try again.';
        }
    }
}

while (count($answers_input) < 3) {
    $answers_input[] = '';
}
?>

<?=template_header('Create Poll')?>

<div class="content create">
    <h2>Create Poll</h2>
    <p>Define your poll and add as many answer options as you need.</p>

    <?php if ($errors): ?>
    <div class="card error">
        <strong>We couldn&rsquo;t save your poll:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?=htmlspecialchars($error, ENT_QUOTES)?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="post" class="card poll-form" autocomplete="off" novalidate>
        <div class="form-group">
            <label for="title">Poll title</label>
            <input type="text" id="title" name="title" placeholder="e.g. What stupid idea you've got?" value="<?=htmlspecialchars($title, ENT_QUOTES)?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description <span class="muted">(optional)</span></label>
            <textarea id="description" name="description" rows="4" placeholder="Give voters more context..."><?=htmlspecialchars($description, ENT_QUOTES)?></textarea>
        </div>

        <div class="form-group">
            <label>Answer options</label>
            <div id="answer-container">
                <?php foreach ($answers_input as $answer): ?>
                <div class="answer-row">
                    <input type="text" name="answers[]" value="<?=htmlspecialchars($answer, ENT_QUOTES)?>" placeholder="Add an answer option">
                    <button type="button" class="remove-answer" title="Remove option">&times;</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="secondary" id="add-answer">Add another option</button>
        </div>

        <div class="form-actions">
            <a class="secondary" href="index.php">Cancel</a>
            <input type="submit" value="Create poll">
        </div>
    </form>
</div>

<script>
(function () {
    const container = document.getElementById('answer-container');
    const addButton = document.getElementById('add-answer');

    const createRow = (value = '') => {
        const row = document.createElement('div');
        row.className = 'answer-row';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'answers[]';
        input.placeholder = 'Add an answer option';
        input.value = value;

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'remove-answer';
        removeButton.innerHTML = '&times;';
        removeButton.title = 'Remove option';
        removeButton.addEventListener('click', () => {
            if (container.children.length > 2) {
                row.remove();
            }
        });

        row.appendChild(input);
        row.appendChild(removeButton);
        return row;
    };

    const wireRemoveButtons = () => {
        container.querySelectorAll('.remove-answer').forEach((button) => {
            button.addEventListener('click', (event) => {
                const row = event.target.closest('.answer-row');
                if (row && container.children.length > 2) {
                    row.remove();
                }
            });
        });
    };

    addButton?.addEventListener('click', () => {
        container.appendChild(createRow());
    });

    wireRemoveButtons();
})();
</script>

<?=template_footer()?>
