<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Welcome | The Dacca Automobile Museum</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth welcome">

<div class="container">

   <div class="content">
      <a href="home.php"><img src="images/white2.png" alt="The Dacca Automobile Museum" class="brand"></a>
      <h3>Welcome!</h3>
      <p class="quote">No action hero is more closely associated with cars than James Bond</p>
      <p class="account">Signed in as <span><?= e($_SESSION['usermail']) ?></span></p>
      <div class="actions">
         <a href="book.php" class="primary">Book Ticket</a>
         <a href="home.php" class="secondary">Home</a>
         <a href="logout.php" class="secondary">Log out</a>
      </div>
   </div>

</div>

</body>
</html>
