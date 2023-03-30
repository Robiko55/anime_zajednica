<?php

include "functions/init.php" // includovacu header na ostalim stranicama, sa njim ce biti includovan i init.php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Anime zajednica</title>
    
</head>
<body>
    

<div class="container"> <?php// kontejner za sajt ?>

    <ul>
        <li><a href="index.php"> Home </a></li>

        <?php if(!isset($_SESSION['email'])): ?>
        <li><a href="login.php"> Uloguj se </a></li>
        <li><a href="register.php"> Registruj se </a></li>
        <?php else : ?>
        <li><a href="logout.php"> Izloguj se </a></li>
        <li><a href="profile.php"> Profil </a></li>
        <li class="welcome-message"><h3><?php $user = get_user(); echo $user['first_name']; ?>, dobrodosao! </h3></li>
        <?php endif; ?>
</ul>
<hr>