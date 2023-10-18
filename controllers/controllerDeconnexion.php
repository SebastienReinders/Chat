<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel = "stylesheet" href="../css/styles.css">
    <title>ChatReindersSébastien</title>
</head>
<body>
    <?php
        session_destroy();    
        header('Location: http://localhost/gits/projet-fin-d-annee-web2-2023-sebastien018/index.php');
        exit(1);
    ?>
</body>
</html>