<?php
declare(strict_types=1);

/**
 * Shared helpers: session, escaping, login guard, CSRF token.
 * No database access here, so static pages can include it cheaply.
 */

date_default_timezone_set('Asia/Dhaka');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

/** Escape text for safe output in HTML. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Character count that works even if the mbstring extension is not enabled. */
function text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

/** Redirect to the login page unless a user is logged in. */
function require_login(): void
{
    if (empty($_SESSION['usermail'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $page = basename($_SERVER['SCRIPT_NAME']);
            if (in_array($page, ['book.php', 'header.php'], true)) {
                $_SESSION['after_login'] = $page;
            }
        }
        header('Location: login_form.php');
        exit;
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valid(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $token);
}
