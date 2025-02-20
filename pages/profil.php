<?php
session_start();
require_once '../includes/navbar.php';
require_once '../class/Database.php';
require_once '../class/Comments.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Instancier les classes
$database = new Database();
$comment = new Comment($database);
$messageManager = new MessageManager($database);
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


// Gérer la modification ou la suppression d'un message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_message'])) {
        $message_id = (int)$_POST['message_id'];
        $new_message = trim($_POST['new_message']);
        $user_id = $_SESSION['user_id'];

        if ($messageManager->updateMessage($message_id, $user_id, $new_message)) {
            $success_message = "Message modifié avec succès !";
        } else {
            $error_message = "Vous n'êtes pas autorisé à modifier ce message.";
        }
    } elseif (isset($_POST['delete_message'])) {
        $message_id = (int)$_POST['message_id'];
        $user_id = $_SESSION['user_id'];

        if ($messageManager->deleteMessage($message_id, $user_id)) {
            $success_message = "Message supprimé avec succès !";
        } else {
            $error_message = "Vous n'êtes pas autorisé à supprimer ce message.";
        }
    }
}

// Récupérer les messages de l'utilisateur
$user_id = $_SESSION['user_id'];
$messages = $comment->getUserMessages($user_id); // À implémenter dans la classe Comment

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

        
        <!-- Afficher les messages de l'utilisateur -->
        <?php if (!empty($messages)) : ?>
            <h2>Vos messages</h2>
            <?php foreach ($messages as $message) : ?>
                <div class="message">
                    <p><?= htmlspecialchars($message['comment']) ?></p>
                    <small>Posté le <?= date('d/m/Y H:i', strtotime($message['date_comment'])) ?></small>

                    <!-- Formulaire pour modifier un message -->
                <form method="POST" style="display:inline;">
                    <div class="styleform">
                            <div>
                                <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                            </div>
                            <div>
                                <textarea name="new_message" placeholder="Modifier votre message..." required><?= htmlspecialchars($message['comment']) ?></textarea>
                            </div>
                            <div>
                                <button type="submit" name="update_message">Modifier</button>
                            
                    
                </form>

                    <!-- Formulaire pour supprimer un message -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                        <button type="submit" name="delete_message" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">Supprimer</button>
                    </div>
                    </div>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Vous n'avez posté aucun message.</p>
        <?php endif; ?>

        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if (isset($success_message)) : ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

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