<?php
declare(strict_types=1);
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/db.php';

$loginErrors = [];
$registerErrors = [];
$loginEmail = '';
$registerName = '';
$registerEmail = '';
$activePanel = (isset($_GET['view']) && $_GET['view'] === 'register') ? 'register' : 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? 'login';

    if ($formType === 'register') {
        $activePanel = 'register';
        $registerName = trim($_POST['name'] ?? '');
        $registerEmail = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($registerName === '' || mb_strlen($registerName) < 2) {
            $registerErrors[] = 'Please enter your name (at least 2 characters).';
        }

        if (!$registerEmail || !filter_var($registerEmail, FILTER_VALIDATE_EMAIL)) {
            $registerErrors[] = 'Please provide a valid email address.';
        }

        if (strlen($password) < 8) {
            $registerErrors[] = 'Password must be at least 8 characters.';
        }

        if ($password !== $confirmPassword) {
            $registerErrors[] = 'Passwords do not match.';
        }

        if (!$registerErrors) {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $registerEmail]);

            if ($stmt->fetch()) {
                $registerErrors[] = 'An account with that email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
                $insert->execute([
                    'name' => $registerName,
                    'email' => $registerEmail,
                    'password' => $hash,
                ]);

                $_SESSION['flash'] = 'Account created! You can now sign in.';
                header('Location: login.php');
                exit;
            }
        }
    } else {
        $activePanel = 'login';
        $loginEmail = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$loginEmail || !filter_var($loginEmail, FILTER_VALIDATE_EMAIL)) {
            $loginErrors[] = 'Please provide a valid email address.';
        }

        if ($password === '') {
            $loginErrors[] = 'Please enter your password.';
        }

        if (!$loginErrors) {
            $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $loginEmail]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: dashboard.php');
                exit;
            }

            $loginErrors[] = 'Incorrect email or password.';
        }
    }
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auth System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body <?php echo $activePanel === 'register' ? 'show-register' : ''; ?>">
    <div class="auth-card">
        <div class="form-column">
            <section class="auth-panel login-panel">
                <h1>Welcome back</h1>
                <p>Sign in to access your dashboard.</p>

                <?php if ($flash): ?>
                    <div class="alert success"><?php echo htmlspecialchars($flash, ENT_QUOTES); ?></div>
                <?php endif; ?>

                <?php if ($loginErrors): ?>
                    <div class="alert error">
                        <?php foreach ($loginErrors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <input type="hidden" name="form_type" value="login">
                    <label>
                        <span>Email address</span>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($loginEmail, ENT_QUOTES); ?>" required>
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="password" required>
                    </label>
                    <button type="submit" class="pill-button primary-btn">Sign in</button>
                </form>
                <p class="auth-switch">
                    Don't have an account?
                    <button type="button" class="link-button" data-panel="register">Create one</button>
                </p>
            </section>

            <section class="auth-panel register-panel">
                <h1>Create account</h1>
                <p>Join the platform and access the dashboard.</p>

                <?php if ($registerErrors): ?>
                    <div class="alert error">
                        <?php foreach ($registerErrors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <input type="hidden" name="form_type" value="register">
                    <label>
                        <span>Name</span>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($registerName, ENT_QUOTES); ?>" required>
                    </label>
                    <label>
                        <span>Email address</span>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($registerEmail, ENT_QUOTES); ?>" required>
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="password" required>
                    </label>
                    <label>
                        <span>Confirm password</span>
                        <input type="password" name="confirm_password" required>
                    </label>
                    <button type="submit" class="pill-button primary-btn">Create account</button>
                </form>
                <p class="auth-switch">
                    Already registered?
                    <button type="button" class="link-button" data-panel="login">Sign in</button>
                </p>
            </section>
        </div>

        <aside class="auth-aside">
            <div class="aside-state aside-login">
                <h2>New here?</h2>
                <p>Create a free account to get started.</p>
                <button type="button" class="pill-button outline-button" data-panel="register">Sign up</button>
            </div>
            <div class="aside-state aside-register">
                <h2>Already have an account?</h2>
                <p>Sign back in to continue where you left off.</p>
                <button type="button" class="pill-button outline-button" data-panel="login">Sign in</button>
            </div>
        </aside>
    </div>
    <script>
        const body = document.body;
        document.querySelectorAll('[data-panel]').forEach((el) => {
            el.addEventListener('click', (event) => {
                event.preventDefault();
                const target = el.getAttribute('data-panel');
                if (target === 'register') {
                    body.classList.add('show-register');
                } else {
                    body.classList.remove('show-register');
                }
            });
        });
    </script>
</body>
</html>
