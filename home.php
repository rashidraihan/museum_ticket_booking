<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$loggedIn = !empty($_SESSION['usermail']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Visit | The Dacca Automobile Museum</title>
       <link rel="stylesheet" href="style.css">
    </head>
    <body>
         <div class="container">
            <nav>
              <a href="home.php"><img src="images/Dhaka.png" class="logo" alt="The Dacca Automobile Museum"></a>
              <ul>
                <li><a href="https://www.thoughtco.com/who-invented-the-car-4059932" target="_blank" rel="noopener">History</a></li>
                <?php if ($loggedIn): ?>
                <li><a href="header.php">My Account</a></li>
                <li><a href="logout.php">Log out</a></li>
                <?php else: ?>
                <li><a href="login_form.php">Log in</a></li>
                <?php endif; ?>
                <li><a href="book.php" class="nav-cta">Book Ticket</a></li>
              </ul>
            </nav>
            <div class="text-box">

                <h1>The cars we drive say a lot about us</h1>

                <div class="row">
                    <a href="https://www.zoox.com/" target="_blank" rel="noopener">Explore The Future of Automobile</a>
                    <a href="https://www.howacarworks.com/" target="_blank" rel="noopener">How A Car Works<span></span></a>
                </div>

            </div>
         </div>
    </body>
</html>
