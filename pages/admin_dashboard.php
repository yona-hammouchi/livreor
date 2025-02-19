<?php
require_once '../includes/navbar.php';
require_once '../class/Database.php';
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
    <title>admin</title>
</head>

<body>
    <h1><?php echo "Bienvenue, " . $_SESSION['username'] . " ! "; ?></h1>
    <section class="containers_dash">

        <section class="containers_inform">
            <h2>Informations Personnelle</h2>
            <form method="POST">
                <label>Nom d'utilisateur :</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>

                <label>Nouveau mot de passe :</label>
                <input type="password" name="password" placeholder="Entrer le nouveau mot de passe">
                <button type="submit" name="update">Mettre à jour</button>
            </form>
            <a href="deconnexion.php">Se déconnecter</a>
            <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
                <button type="submit" name="delete">Supprimer mon compte</button>
            </form>
        </section>

    </section class="containers_users">
    <h2>Liste des utilisateurs</h2>

    </section class="containers_comments">
    <h2>Historiques des commentaires</h2>
    </section>


</body>

</html>