<?php
require_once '../includes/navbar.php';
session_start();
if (!isset($_SESSION)) {
    die("Les sessions ne fonctionnent pas !");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1><?php echo "Bienvenue, " . $_SESSION['username'] . " ! Ceci est le tableau de bord Admin."; ?></h1>
</body>

</html>