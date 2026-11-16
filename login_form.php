<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$errors = [];
$notice = isset($_GET['registered']) ? 'Account created. Please log in.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['usermail'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT password FROM user_form WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row && password_verify($pass, $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['usermail'] = $email;
        $next = $_SESSION['after_login'] ?? 'header.php';
        unset($_SESSION['after_login']);
        header('Location: ' . $next);
        exit;
    }
    $errors[] = 'Incorrect email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | The Dacca Automobile Museum</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth auth-login">

<div class="form-container">

    <form action="" method="post" novalidate>
        <h3 class="title">Log in</h3>
        <p class="subtitle">Sign in to book your museum tickets.</p>
        <?php if ($notice): ?>
            <span class="success-msg"><?= e($notice) ?></span>
        <?php endif; ?>
        <?php foreach ($errors as $msg): ?>
            <span class="error-msg"><?= e($msg) ?></span>
        <?php endforeach; ?>
        <input type="email" name="usermail" placeholder="Email address" class="box" required autocomplete="email">
        <input type="password" name="password" placeholder="Password" class="box" required autocomplete="current-password">
        <input type="submit" value="Log in" class="form-btn" name="submit">
        <p class="switch">Don't have an account? <a href="register_form.php">Register now</a></p>
    </form>

</div>

</body>
</html>
