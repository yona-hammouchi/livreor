<?php
session_start();
require_once '../class/Database.php';
require_once '../class/comments.php';
require_once '../includes/navbar.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Instancier les classes
$database = new Database();
$comment = new Comment($database);

// Gérer l'ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $comment_text = trim($_POST['comment']);

    if (!empty($comment_text)) {
        $comment->addComment($user_id, $comment_text);
        header('Location: profil.php'); // Rediriger pour éviter de renvoyer le formulaire
        exit;
    } else {
        $error_message = "Le commentaire ne peut pas être vide.";
    }
}
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
        <p>Merci à tous pour vos mots et vos pensées qui viendront enrichir ce joli moment.</p>

        <!-- Formulaire de mise à jour du profil -->
        <form method="POST">
            <div class="item">
                <label>Nom d'utilisateur :</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
            </div>

            <div class="item">
                <label>Nouveau mot de passe :</label>
                <input type="password" name="password" placeholder="Entrer le nouveau mot de passe">
            </div>

            <button type="submit" name="update">Mettre à jour</button>
        </form>

        <!-- Bouton de déconnexion -->
        <a href="deconnexion.php">Se déconnecter</a>

        <!-- Bouton de suppression de compte -->
        <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
            <button type="submit" name="delete" style="background-color: red; color: white;">Supprimer mon compte</button>
        </form>

        <!-- Formulaire d'ajout de commentaire -->
        <h2>Ajouter un commentaire</h2>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <textarea name="comment" required></textarea>
            <button type="submit">Poster</button>
        </form>
    </section>
</body>

</html>