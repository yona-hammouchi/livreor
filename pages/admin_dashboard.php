<?php
require_once '../includes/navbar.php';
require_once '../includes/footer.php';
require_once '../class/Database.php';
require_once '../class/Comments.php';
session_start();

// Vérifie que l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Instancie la classe AdminDashboard
$dashboard = new AdminDashboard();

// Gère la pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Récupère les commentaires
$comments = $dashboard->getComments($page, $search);
$totalComments = $dashboard->getTotalCommentsCount($search);
$totalPages = ceil($totalComments / 10);

// Gère la suppression d'un commentaire
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if ($dashboard->deleteComment($id)) {
        echo "<script>alert('Commentaire supprimé avec succès!');</script>";
        header("Location: admin_dashboard.php");
    } else {
        echo "<script>alert('Erreur lors de la suppression du commentaire.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styleNavbar.css">
    <link rel="stylesheet" href="../styles/style_admin.css">
    <link rel="stylesheet" href="../styles/style_profil.css">

    <title>Dashboard Admin</title>
</head>

<body>
    <main>
        <h1>Bienvenue, <?php echo $_SESSION['username']; ?> !</h1>
        <a href="deconnexion.php">Se déconnecter</a>
        <h2>Gestion des commentaires</h2>

        <!-- Formulaire de recherche -->
        <form method="GET" action="admin_dashboard.php">
            <input type="text" name="search" placeholder="Rechercher des commentaires" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Rechercher</button>
        </form>

        <!-- Table des commentaires -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo $comment['id']; ?></td>
                        <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                        <td><?php echo $comment['date_comment']; ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" name="delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="admin_dashboard.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Précédent</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="admin_dashboard.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="admin_dashboard.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>