<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel = "stylesheet" href="../css/stylechat.css">
    <title>ChatReindersSébastien</title>
</head>
<body>
    <?php
    session_start();

        include("../modeles/modele.php");

        include("../vues/header.php");
        include("../vues/arriveeChat.php");

        $nbre = chatCount();

        // On calcul le nombre de page maximum à afficher.
        $pagesTotal = 1;
       
        if ($nbre % 20 == 0)
        {
            $pagesTotal = (int)($nbre / 20);
        }
        else
        {
            $pagesTotal = (int)($nbre / 20 + 1);
        }


        //si on passe ici pour la premiere fois (pas encore fait suivant) alors 
        //on initialise les boutons a zéro.
        if (!isset($_POST['suivant']))
        {
           $_POST['suivant'] = 0; 
        }
        
        if (!isset($_POST['precedent']))
        {
            $_POST['precedent'] = 0;
        }

        if (!isset($_SESSION['debut']))
        {
            $_SESSION['debut'] = 0;
        }


        //si on passe ici pour la premiere fois (pas encore fait suivant) alors on est 
        //d'office sur la première page.
        if (!isset($_SESSION['pageencours']))
        {
            $_SESSION['pageencours'] = 1;
        }
        else
        {
            // Si on appuie sur suivant et qu on est pas a la dernière page
            if($_POST['suivant'] == 1 && $_SESSION['pageencours'] != $pagesTotal)
            {
                $_SESSION['pageencours']++;
                $_SESSION['debut'] = ($_SESSION['pageencours'] - 1) * 20;
            }

            if($_POST['precedent'] == 1 && $_SESSION['pageencours'] != 1)
            {
                $_SESSION['pageencours']--;
                $_SESSION['debut'] = ($_SESSION['pageencours'] - 1) * 20;
            }
            
        }


        if(!empty($_POST['message']))
        {
            echo '</br>';
            $_POST['date1'] = date('Y-m-d H:i:s');
            echo '</br>';
            encoderChat();
            $_POST['message'] = NULL;
            echo '</br>';

            $_SESSION['debut'] = 0;
            $_SESSION['pageencours'] = 1;
        }

        $tableau = chat1($_SESSION['debut']);

        foreach ($tableau as $row)
        {
            include("../vues/chat1.php");
        }

        include("../vues/chatForm.php");


        // Appel de la fonction selectUtilisateur()
        $resultat = selectUtilisateur();


    echo "Séléctionnez un utilisateur avec lequel communiquer :";

    echo "<br>";
    echo "<br>";
    // Affichage des résultats
    foreach ($resultat as $row) {
        echo '<form action="controllerPrive.php" method="post">';
        echo '<button type="submit" name="pseudo" value="' . $row['pseudo'] . '">' . $row['pseudo'] . '</button>';
        echo '</form>';
        echo "<br>";
    }
    




/*bouton suivant, précédent et envoyer message*/
        include("../vues/chat2.php");

        include("../vues/footer.php");
    ?>
</body>
</html>