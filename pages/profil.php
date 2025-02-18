<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: profil.php");
    exit;
}
require_once '../includes/navbar.php';

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styleNavbar.css">
    <link rel="stylesheet" href="../styles/style_profil.css">
    
    <title>Profil</title>
</head>

<body>
    <section class="pageProfil">
        
        <h1>Bienvenue sur votre profil, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>

        <p>Merci à tous pour vos mots et vos pensées qui viendront 
        enrichir ce joli moment.</p>

        <form method="POST">
                <div class="item">
                    <label>Nom d'utilisateur :</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                </div>

                <div class = "item">
                <label>Nouveau mot de passe :</label>
                <input type="password" name="password" placeholder="Entrer le nouveau mot de passe">
                </div>

            <button type="submit" name="update">Mettre à jour</button>
            
        </form>
        <a href="deconnexion.php">Se déconnecter</a>

        <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
            <button type="submit" name="delete" style="background-color: red; color: white;">Supprimer mon compte</button>
        </form>

        <form action="post_comment.php" method="POST">
            <textarea name="comment" required></textarea>
            <button type="submit">Poster</button>
        </form>
    </section>

    
    </body>

</html>

?>