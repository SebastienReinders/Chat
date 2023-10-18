<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel = "stylesheet" href="../css/styles.css">
    <title>ChatReindersSébastien</title>
</head>
<body>
    <?php
        include("../modeles/modele.php");
               
        if(!empty($_POST['pseudo']))
        {
            $tab = inscription();
                    /* Si la fonction inscription a retouné une des erreurs suivantes, on les gère ici :*/
            if(($tab['pseudo'] == FALSE) || ($tab['mdp'] == FALSE) || ($tab['dateNaiss'] == FALSE) || ($tab['genre'] == FALSE) || ($tab['image'] == FALSE) || ($tab['doublon'] == false))
            {
                if($tab['pseudo'] == FALSE)
                {
                    $tab['pseudo'] = "Vérifier pseudo. Entre 8 et 15 caractères.";
                }
                else
                {
                    $tab['pseudo'] = "";
                }
                if($tab['mdp'] == FALSE)
                {
                    $tab['mdp'] = "Vérifier mot de passe. Entre 8 et 15 caractères, dont au moins une majuscule, au moins une 
                    minuscule, au moins un chiffre et au moins un caractère spécial.";
                }
                else
                {
                    $tab['mdp'] = "";
                }
                if($tab['dateNaiss'] == FALSE)
                {
                    $tab['dateNaiss'] = "Vérifier date de naissance. Age min 14 ans.";
                }
                else
                {
                    $tab['dateNaiss'] = "";
                }
                if($tab['genre'] == FALSE)
                {
                    $tab['genre'] = "Vérifier genre.";
                }
                else
                {
                    $tab['genre'] = "";
                }
                if($tab['image'] == FALSE)
                {
                    $tab['image'] = "Vérifier image. Doit être au format PNG ou JPEG et mesurer au maximum 400 * 400.";
                }
                else
                {
                    $tab['image'] = "";
                }

                if ($tab['doublon'] == false)
                {
                    $tab['general'] = "Ce pseudo existe déjà.";
                }
                                
                include("../vues/inscription.php");
            }

            else
            {
                include("../vues/inscriptionValide.php");
            }
        }  
        else if(empty($_POST['pseudo']) && (!empty($_POST['psw']) || !empty($_POST['naissance']) || !empty($_POST['genre']) || !empty($_POST['avatar'])))
        {
            $tab['general'] = "Tous les champs doivent être remplis";
            include("../vues/inscription.php");
        }

        else
        {
            include("../vues/inscription.php");
        }       
              
    ?>
</body>
</html>