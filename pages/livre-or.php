<?php
session_start();
require_once '../class/Database.php';
require_once '../class/Comments.php';

// Instancier les classes
$database = new Database();
$comment = new Comment($database);

// Gérer la recherche
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Nombre de commentaires par page

// Récupérer les commentaires
if (!empty($keyword)) {
    $comments = $comment->searchComments($keyword, $page, $perPage);
    $totalComments = $comment->getTotalSearchComments($keyword);
} else {
    $comments = $comment->getComments($page, $perPage);
    $totalComments = $comment->getTotalComments();
}

// Calculer le nombre total de pages
$totalPages = ceil($totalComments / $perPage);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="../styles/style_livreor.css">

</head>

<body>
    <header>
        <h1>Livre d'Or</h1>
        <a href="profil.php">Retour au profil</a>
    </header>

    <section class="container">
        <!-- Barre de recherche -->
        <form action="livre-or.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un commentaire..." value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <!-- Tableau des commentaires -->
        <table>
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $com) : ?>
                    <tr>
                        <td><?= htmlspecialchars($com['username']) ?></td>
                        <td><?= htmlspecialchars($com['comment']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($com['date_comment'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1) : ?>
                <a href="livre-or.php?page=<?= $page - 1 ?>&search=<?= urlencode($keyword) ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="livre-or.php?page=<?= $i ?>&search=<?= urlencode($keyword) ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages) : ?>
                <a href="livre-or.php?page=<?= $page + 1 ?>&search=<?= urlencode($keyword) ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>