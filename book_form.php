<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: book.php');
    exit;
}

$errors = [];

if (!csrf_valid($_POST['csrf'] ?? null)) {
    $errors[] = 'Your session expired. Please submit the form again.';
}

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');
$location = trim($_POST['location'] ?? '');
$arrivals = trim($_POST['arrivals'] ?? '');
$visitors = filter_var($_POST['visitors'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 50]]);

if (text_length($name) < 2 || text_length($name) > 100) {
    $errors[] = 'Please enter your name (2 to 100 characters).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    $errors[] = 'Please enter a valid email address.';
}
if (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
    $errors[] = 'Please enter a valid phone number.';
}
if ($address === '' || text_length($address) > 255) {
    $errors[] = 'Please enter your address.';
}
if ($location === '' || text_length($location) > 100) {
    $errors[] = 'Please tell us which place you want to visit.';
}
if ($visitors === false) {
    $errors[] = 'Number of visitors must be between 1 and 50.';
}

$date = DateTime::createFromFormat('Y-m-d', $arrivals);
if (!$date || $date->format('Y-m-d') !== $arrivals) {
    $errors[] = 'Please choose a valid arrival date.';
} elseif ($arrivals < date('Y-m-d')) {
    $errors[] = 'Arrival date cannot be in the past.';
}

if ($errors) {
    $_SESSION['book_errors'] = $errors;
    $_SESSION['book_old'] = compact('name', 'email', 'phone', 'address', 'location', 'arrivals') + ['visitors' => (string) ($_POST['visitors'] ?? '')];
    header('Location: book.php');
    exit;
}

$stmt = $conn->prepare(
    'INSERT INTO book_form (name, email, phone, address, location, visitors, arrivals) VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('sssssis', $name, $email, $phone, $address, $location, $visitors, $arrivals);
$stmt->execute();
$_SESSION['book_ok'] = $conn->insert_id;
$stmt->close();

header('Location: book.php');
exit;
