<?php
declare(strict_types=1);

require_once __DIR__ . '/../AuthenticationSystem/auth_guard.php';
require_once __DIR__ . '/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

$errors = [];
$formValues = [
    'last_name' => '',
    'first_name' => '',
    'address' => '',
    'email' => '',
    'birth_date' => '',
    'gender' => 'm',
    'bac_average' => '',
];

$noticeMap = [
    'created' => 'Student has been added to the registry.',
    'updated' => 'Student details updated successfully.',
    'deleted' => 'Student removed from the registry.',
];
$noticeKey = $_GET['notice'] ?? null;
$notice = $noticeMap[$noticeKey] ?? null;

$today = (new DateTime())->format('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (in_array($action, ['create', 'update'], true)) {
        $formValues = [
            'last_name' => trim((string)($_POST['last_name'] ?? '')),
            'first_name' => trim((string)($_POST['first_name'] ?? '')),
            'address' => trim((string)($_POST['address'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'birth_date' => trim((string)($_POST['birth_date'] ?? '')),
            'gender' => strtolower(trim((string)($_POST['gender'] ?? 'm')) ?: 'm'),
            'bac_average' => trim((string)($_POST['bac_average'] ?? '')),
        ];
    }

    if (!hash_equals($csrfToken, $_POST['csrf'] ?? '')) {
        $errors[] = 'Session expired. Reload the page and try again.';
    }

    if (!$errors) {
        if ($action === 'delete') {
            $studentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($studentId <= 0) {
                $errors[] = 'Invalid student.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
                $stmt->execute(['id' => $studentId]);
                header('Location: index.php?notice=deleted');
                exit;
            }
        } elseif (in_array($action, ['create', 'update'], true)) {
            $payload = $formValues;
            $payload['gender'] = in_array($payload['gender'], ['m', 'f'], true) ? $payload['gender'] : 'm';

            if ($payload['last_name'] === '') {
                $errors[] = 'Enter the student last name.';
            }
            if ($payload['first_name'] === '') {
                $errors[] = 'Enter the student first name.';
            }
            if ($payload['address'] === '') {
                $errors[] = 'Enter the address.';
            }
            if ($payload['email'] === '' || !filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Provide a valid email address.';
            }
            if ($payload['birth_date'] === '') {
                $errors[] = 'Select the birth date.';
            } else {
                $birthDate = DateTime::createFromFormat('Y-m-d', $payload['birth_date']);
                if (!$birthDate || $birthDate->format('Y-m-d') !== $payload['birth_date']) {
                    $errors[] = 'Date must use the YYYY-MM-DD format.';
                }
            }

            $bacRaw = str_replace(',', '.', $formValues['bac_average']);
            if ($bacRaw === '' || !is_numeric($bacRaw)) {
                $errors[] = 'BAC average must be a number (e.g. 8.35).';
            } else {
                $bacValue = round((float)$bacRaw, 2);
                if ($bacValue < 1 || $bacValue > 10) {
                    $errors[] = 'BAC average must be between 1 and 10.';
                } else {
                    $payload['bac_average'] = number_format($bacValue, 2, '.', '');
                }
            }

            if (!$errors) {
                try {
                    if ($action === 'create') {
                        $stmt = $pdo->prepare('INSERT INTO students (last_name, first_name, address, email, birth_date, gender, bac_average) VALUES (:last_name, :first_name, :address, :email, :birth_date, :gender, :bac_average)');
                        $stmt->execute($payload);
                        header('Location: index.php?notice=created');
                        exit;
                    }

                    $studentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                    if ($studentId <= 0) {
                        $errors[] = 'The student you are trying to edit does not exist.';
                    } else {
                        $payload['id'] = $studentId;
                        $stmt = $pdo->prepare('UPDATE students SET last_name = :last_name, first_name = :first_name, address = :address, email = :email, birth_date = :birth_date, gender = :gender, bac_average = :bac_average WHERE id = :id');
                        $stmt->execute($payload);
                        header('Location: index.php?notice=updated');
                        exit;
                    }
                } catch (PDOException $exception) {
                    $errors[] = 'Unable to save data: ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES);
                }
            }
        } else {
            $errors[] = 'Unknown action.';
        }
    }
}

$studentsStmt = $pdo->query('SELECT * FROM students ORDER BY last_name, first_name');
$students = $studentsStmt->fetchAll();
$totalStudents = count($students);

$editingStudent = null;
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
if ($editId && $editId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmt->execute(['id' => $editId]);
    $editingStudent = $stmt->fetch();
    if ($editingStudent && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $formValues = [
            'last_name' => $editingStudent['last_name'],
            'first_name' => $editingStudent['first_name'],
            'address' => $editingStudent['address'],
            'email' => $editingStudent['email'],
            'birth_date' => $editingStudent['birth_date'],
            'gender' => $editingStudent['gender'],
            'bac_average' => rtrim(rtrim((string)$editingStudent['bac_average'], '0'), '.'),
        ];
    }
    if (!$editingStudent) {
        $errors[] = 'Selected student could not be found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppCRUD Â· Student registry</title>
    <link rel="stylesheet" href="style.css?v=1">
</head>
<body>
    <header class="navtop">
        <div>
            <h1>AppCRUD</h1>
            <a href="#student-form" class="nav-action">+ Add student</a>
        </div>
    </header>
    <main class="content">
        <section class="page-header">
            <div>
                <p class="eyebrow">Digital catalog</p>
                <h2>Student registry</h2>
                <p>Manage every profile with the pastel VotingSystem styling. Add new records in seconds, update outdated info, or remove a student entirely.</p>
            </div>
            <a href="#student-form" class="pill-button create">Add student</a>
        </section>

        <?php if ($notice): ?>
        <div class="alert success">
            <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.2l-3.5-3.5L4 14.2 9 19l12-12-1.5-1.5z"/></svg>
            <span><?=htmlspecialchars($notice, ENT_QUOTES)?></span>
        </div>
        <?php endif; ?>

        <?php if ($errors): ?>
        <div class="alert danger">
            <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 21h22L12 2 1 21zm12-3h-2v2h2v-2zm0-6h-2v5h2v-5z"/></svg>
            <div>
                <strong>Please check the following:</strong>
                <ul>
                    <?php foreach ($errors as $message): ?>
                    <li><?=htmlspecialchars($message, ENT_QUOTES)?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <section id="student-form" class="form-card">
            <form method="post" class="student-form">
                    <input type="hidden" name="csrf" value="<?=$csrfToken?>">
                    <input type="hidden" name="action" value="<?=$editingStudent ? 'update' : 'create'?>">
                    <?php if ($editingStudent): ?>
                    <input type="hidden" name="id" value="<?=$editingStudent['id']?>">
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="last_name">Last name</label>
                            <input type="text" id="last_name" name="last_name" value="<?=htmlspecialchars($formValues['last_name'], ENT_QUOTES)?>" placeholder="e.g. Popescu" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First name</label>
                            <input type="text" id="first_name" name="first_name" value="<?=htmlspecialchars($formValues['first_name'], ENT_QUOTES)?>" placeholder="e.g. Andreea" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?=htmlspecialchars($formValues['address'], ENT_QUOTES)?>" placeholder="123 Example St, Chisinau" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?=htmlspecialchars($formValues['email'], ENT_QUOTES)?>" placeholder="student@example.com" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="birth_date">Birth date</label>
                            <input type="date" id="birth_date" name="birth_date" value="<?=htmlspecialchars($formValues['birth_date'], ENT_QUOTES)?>" max="<?=$today?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="m" <?=$formValues['gender'] === 'm' ? 'selected' : ''?>>M</option>
                                <option value="f" <?=$formValues['gender'] === 'f' ? 'selected' : ''?>>F</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bac_average">BAC average</label>
                            <input type="number" step="0.01" min="1" max="10" id="bac_average" name="bac_average" value="<?=htmlspecialchars($formValues['bac_average'], ENT_QUOTES)?>" placeholder="e.g. 8.75" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="pill-button create"><?=$editingStudent ? 'Update student' : 'Save student'?></button>
                        <?php if (!$editingStudent): ?>
                        <button type="reset" class="pill-button outline">Clear fields</button>
                        <?php else: ?>
                        <a href="index.php" class="pill-button outline">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>

        <section class="table-card">
            <div class="table-heading">
                <div>
                    <p class="eyebrow">Updated catalog</p>
                    <h3>Registered students</h3>
                </div>
                <div class="table-actions">
                    <span class="count-pill"><?=$totalStudents?> student<?=$totalStudents === 1 ? '' : 's'?> stored</span>
                    <a href="#student-form" class="pill-button outline">Add student</a>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Last name</th>
                            <th>First name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Birth date</th>
                            <th>Gender</th>
                            <th>BAC average</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$students): ?>
                        <tr class="empty-state">
                            <td colspan="8">
                                <strong>No students yet.</strong>
                                <p>Fill out the form to add the first student.</p>
                                <a href="#student-form">Open the form</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach ($students as $student): ?>
                        <?php
                            $genderLetter = strtoupper((string)$student['gender']);
                            $bacScore = number_format((float)$student['bac_average'], 2, '.', '');
                            $studentFullName = htmlspecialchars(trim($student['first_name'] . ' ' . $student['last_name']), ENT_QUOTES);
                        ?>
                        <tr>
                            <td data-label="Last name"><?=htmlspecialchars($student['last_name'], ENT_QUOTES)?></td>
                            <td data-label="First name"><?=htmlspecialchars($student['first_name'], ENT_QUOTES)?></td>
                            <td data-label="Address"><?=htmlspecialchars($student['address'], ENT_QUOTES)?></td>
                            <td data-label="Email"><a href="mailto:<?=htmlspecialchars($student['email'], ENT_QUOTES)?>"><?=htmlspecialchars($student['email'], ENT_QUOTES)?></a></td>
                            <td data-label="Birth date"><?=htmlspecialchars($student['birth_date'], ENT_QUOTES)?></td>
                            <td data-label="Gender">
                                <span class="gender-badge gender-<?=htmlspecialchars($student['gender'], ENT_QUOTES)?>"><?=$genderLetter?></span>
                            </td>
                            <td data-label="BAC average">
                                <span class="score-chip"><?=$bacScore?></span>
                            </td>
                            <td data-label="Action" class="actions">
                                <a href="index.php?edit=<?=$student['id']?>#student-form" class="icon-button edit" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                </a>
                                <form method="post" class="inline-form" onsubmit="return confirm('Are you sure you want to delete student <?=$studentFullName?>?');">
                                    <input type="hidden" name="csrf" value="<?=$csrfToken?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?=$student['id']?>">
                                    <button type="submit" class="icon-button delete" title="Delete">
                                        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 3v1H4v2h16V4h-5V3H9zm-2 6v10c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V9H7z"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>






