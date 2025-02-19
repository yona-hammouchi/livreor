<?php
require_once '../class/comments.php';
$commentObj = new Comment();
$comments = $commentObj->getComments();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'OR</title>
</head>

<body>
    <h1>Livre d'OR</h1>

    <?php
    if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
            <p><strong><?= htmlspecialchars($comment['username']) ?></strong> (<?= htmlspecialchars($comment['date_comment']) ?>) :</p>
            <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun commentaire pour le moment.</p>
    <?php endif; ?>
</body>

</html>