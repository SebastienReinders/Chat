<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel = "stylesheet" href="../css/styles.css">
    <title>ChatReindersSébastien</title>
</head>
<body>
    <?php
    $bon = 2;
        include("../modeles/modele.php");
        #si POST vide :
        if(empty($_POST['pseudoLog']))
        {
            include("../vues/connexion.php");

        }
        else
        {
            #pour afficher la vue du chat, il faut que le mot de passe et pseudo soient correct donc il faut
            #ici appeller des fonctions du modèles pour contrôler.
            //include("controllerChat.php");
            $bon = connexion();
            
            

            if ($bon == 1)
            {
                $_SESSION['log'] = $_POST['pseudoLog'];
                header('Location: http://localhost/gits/projet-fin-d-annee-web2-2023-sebastien018/controllers/controllerChat.php');
                exit(1);
            }
            else
            {
                $bon = "Vérifier vos logins et mot de passe";
                include("../vues/connexion.php");
            }
        }
          
    ?>
</body>
</html>