<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
require_login();

// One-time messages left by book_form.php
$errors = $_SESSION['book_errors'] ?? [];
$old    = $_SESSION['book_old'] ?? [];
$ref    = $_SESSION['book_ok'] ?? null;
unset($_SESSION['book_errors'], $_SESSION['book_old'], $_SESSION['book_ok']);

$val = static fn (string $key, string $default = ''): string => e($old[$key] ?? $default);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Book tickets | The Dacca Automobile Museum</title>
   <link rel="stylesheet" href="style1.css">
</head>
<body>

<!-- header section starts  -->
<section class="header">
   <a href="home.php"><img src="images/white2.png" alt="The Dacca Automobile Museum" class="brand"></a>
   <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="header.php">My Account</a>
      <a href="logout.php">Log out</a>
   </nav>
</section>
<!-- header section ends -->

<div class="heading" style="background:url(images/auto8.png) no-repeat">
   <h1>Book Now</h1>
</div>

<!-- booking section starts  -->
<section class="booking">

   <h1 class="heading-title">The Dacca Automobile Museum</h1>

   <?php if ($ref !== null): ?>
      <div class="alert alert-success">
         Booking confirmed. Your reference number is <strong>MUS-<?= str_pad((string) (int) $ref, 6, '0', STR_PAD_LEFT) ?></strong>. Please keep it for your visit.
      </div>
   <?php endif; ?>

   <?php foreach ($errors as $msg): ?>
      <div class="alert alert-error"><?= e($msg) ?></div>
   <?php endforeach; ?>

   <form action="book_form.php" method="post" class="book-form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <div class="flex">
         <div class="inputBox">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required maxlength="100" value="<?= $val('name') ?>">
         </div>

         <div class="inputBox">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required maxlength="255" value="<?= $val('email', $_SESSION['usermail']) ?>">
         </div>

         <div class="inputBox">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your number" required maxlength="20" pattern="[0-9+\-\s]{7,20}" value="<?= $val('phone') ?>">
         </div>

         <div class="inputBox">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Enter your address" required maxlength="255" value="<?= $val('address') ?>">
         </div>

         <div class="inputBox">
            <label for="location">Where to</label>
            <input type="text" id="location" name="location" placeholder="Place you want to visit" required maxlength="100" value="<?= $val('location') ?>">
         </div>

         <div class="inputBox">
            <label for="visitors">How many</label>
            <input type="number" id="visitors" name="visitors" placeholder="Number of visitors" required min="1" max="50" value="<?= $val('visitors') ?>">
         </div>

         <div class="inputBox">
            <label for="arrivals">Arrival date</label>
            <input type="date" id="arrivals" name="arrivals" required min="<?= date('Y-m-d') ?>" value="<?= $val('arrivals') ?>">
         </div>
      </div>

      <div class="form-actions">
         <input type="reset" name="reset" value="Reset" class="btn btn-secondary">
         <input type="submit" value="Book Ticket" name="submit" class="btn">
      </div>

   </form>

</section>
<!-- booking section ends -->

</body>
</html>
