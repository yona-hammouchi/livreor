<?php
session_start();

if ($role === 'admin' && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    return ['error' => "Vous n'avez pas l'autorisation de créer un administrateur."];
}
require_once '../includes/navbar.php';
require_once '../class/Database.php';
require_once '../class/Inscription.php';

$inscription = new Inscription();
$result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $inscription->handleInscription($_POST);
    if (isset($result['success'])) {
        $_SESSION['username'] = $_POST['username'];
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styleConnexion.css">
    <link rel="stylesheet" href="../styles/styleNavbar.css">
    <title>Inscription</title>
</head>

<body>
    <section class="containers_head_forms">
        <section class="container_forms">
            <p>Inscrivez-vous et partagez un message pour célébrer ce moment spécial.</p>
            <form action="inscription.php" method="POST">
                <label>Rôle :</label>
                <select name="role">
                    <option value="user">Utilisateur</option>
                    <option value="admin">Administrateur</option>
                </select>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit">S'inscrire</button>
            </form>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <?php endif; ?>
        </section>
    </section>
</body>

</html>