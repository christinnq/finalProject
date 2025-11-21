<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$stmt = $pdo->prepare('SELECT name, email, created_at FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Auth System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navtop">
        <div>
            <strong>Auth System</strong>
            <span class="nav-user">Logged in as <?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?></span>
            <a href="logout.php" class="pill-button outline-button">Log out</a>
        </div>
    </nav>

    <main class="content dashboard">
        <section class="card">
            <h1>Welcome, <?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>!</h1>
            <p>You are authenticated. Use the button below to access the voting area.</p>

           <div class="dashboard-grid">
                <div class="card stat-card account-card">
                    <h2>Account</h2>
                    <p>Email: <strong><?=htmlspecialchars($user['email'], ENT_QUOTES)?></strong></p>
                    <p>Member since: <strong><?=date('F j, Y', strtotime($user['created_at']))?></strong></p>
                </div>
                <div class="card stat-card voting-card">
                    <h2>Voting System</h2>
                    <p>Ready to vote? Continue to the polls.</p>
                    <a class="pill-button primary-btn" href="/VotingSystem/index.php">Go to Voting System</a>
                </div>
                <div class="card stat-card crud-card">
                    <h2>App CRUD</h2>
                    <p>Manage the CRUD demo from here.</p>
                    <a class="pill-button outline-button" href="/AppCRUD/index.php">Go to App CRUD</a>
                </div>
            </div>

            </div>
        </section>
    </main>
</body>
</html>
