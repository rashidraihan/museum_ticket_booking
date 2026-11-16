<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['usermail'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $cpass = $_POST['cpassword'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (strlen($pass) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    } elseif ($pass !== $cpass) {
        $errors[] = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM user_form WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $errors[] = 'An account with this email already exists.';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            try {
                $stmt = $conn->prepare('INSERT INTO user_form (email, password) VALUES (?, ?)');
                $stmt->bind_param('ss', $email, $hash);
                $stmt->execute();
                $stmt->close();
                header('Location: login_form.php?registered=1');
                exit;
            } catch (mysqli_sql_exception $ex) {
                // 1062 = duplicate entry (two people registering the same email at once)
                $errors[] = $ex->getCode() === 1062
                    ? 'An account with this email already exists.'
                    : 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Create account | The Dacca Automobile Museum</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth auth-register">

<div class="form-container">

   <form action="" method="post" novalidate>
      <h3 class="title">Create account</h3>
      <p class="subtitle">Register to book your museum tickets.</p>
      <?php foreach ($errors as $msg): ?>
         <span class="error-msg"><?= e($msg) ?></span>
      <?php endforeach; ?>
      <input type="email" name="usermail" placeholder="Email address" class="box" required maxlength="255" value="<?= e($email) ?>" autocomplete="email">
      <input type="password" name="password" placeholder="Password (min. 8 characters)" class="box" required minlength="8" autocomplete="new-password">
      <input type="password" name="cpassword" placeholder="Confirm password" class="box" required minlength="8" autocomplete="new-password">
      <input type="submit" value="Register" class="form-btn" name="submit">
      <p class="switch">Already have an account? <a href="login_form.php">Log in</a></p>
   </form>

</div>

</body>
</html>
