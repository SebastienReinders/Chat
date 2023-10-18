<?php
    date_default_timezone_set('Europe/Brussels'); // Paramètrage fuseau horaire pour utilisation fonction date.

    function chatCount()
    {
        $mysqli = new mysqli("localhost", "root", "root", "chat");

        if($mysqli->connect_errno)
        {
            echo "Connection échouée !";
            exit();
        }
    
        $result = $mysqli->query("SELECT COUNT(*) FROM chatMessage");
        $count = $result->fetch_row()[0];
        
        return $count;
    }
       
    function chat1($debut)
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');
    
        $stmt = mysqli_prepare($id, "SELECT utilisateur.pseudo, utilisateur.avatar, utilisateur.genre, chatMessage.message, chatMessage.temps
        FROM chatMessage INNER JOIN utilisateur
        ON (utilisateur.pseudo = chatMessage.emetteur) 
        ORDER BY temps DESC 
        LIMIT ?, ?");
        mysqli_stmt_bind_param($stmt, 'ii', $debut, $nbElements);
        $nbElements = 20;
        mysqli_stmt_execute($stmt);
        
        $res = mysqli_stmt_get_result($stmt);
    
        $tab = mysqli_fetch_all($res, MYSQLI_ASSOC);
    
        mysqli_stmt_close($stmt);
        mysqli_close($id);
        
        return $tab;
    } 



  

    function chatPrive($debut)
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');

        $pseudo = $_SESSION['log'];
        $recepteur = $_SESSION['recepteur'];

        $stmt = mysqli_prepare($id, "SELECT utilisateur.pseudo, utilisateur.avatar, utilisateur.genre, chatMessage.message, chatMessage.temps, chatMessage.recepteur
        FROM chatMessage INNER JOIN utilisateur
        ON (utilisateur.pseudo = chatMessage.emetteur) 
        WHERE utilisateur.pseudo IN (?)
        AND chatMessage.recepteur IN (?)
        ORDER BY temps DESC 
        LIMIT ?, ?");
        mysqli_stmt_bind_param($stmt, 'ssii', $pseudo, $recepteur, $debut, $nbElements);
        $nbElements = 20;
        mysqli_stmt_execute($stmt);
        
        $res = mysqli_stmt_get_result($stmt);

        $tab = mysqli_fetch_all($res, MYSQLI_ASSOC);

        mysqli_stmt_close($stmt);
        mysqli_close($id);
        
        return $tab;
    }





    /*function chat2($a)
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');
        
        $stmt = mysqli_prepare($id, "SELECT avatar, genre FROM utilisateur WHERE pseudo = ?");
        mysqli_stmt_bind_param($stmt, 's', $a);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    
        $tab = mysqli_fetch_assoc($res);
        
        return $tab;
    }*/
    


    function inscription()
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');
        
        $tabErr['pseudo'] = TRUE;
        $tabErr['mdp'] = TRUE;
        $tabErr['mdp2'] = TRUE;
        $tabErr['dateNaiss'] = TRUE;
        $tabErr['genre'] = TRUE;
        $tabErr['image'] = TRUE;
        $tabErr['doublon'] = TRUE;
        $verif = FALSE;


        $a = $_POST['pseudo'];

        if(!preg_match('#^[[:print:]]{8,15}$#', $a))
        {
            $verif = TRUE;
            $tabErr['pseudo'] = FALSE;   
        }

        $b = password_hash($_POST['psw'], PASSWORD_DEFAULT);
       
        if($_POST['psw'] != $_POST['psw2'])
        {
            $verif = TRUE;  
            $tabErr['mdp'] = FALSE;
        }

        if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_])[A-Za-z\d\W_]{8,15}$/', $_POST['psw']))
        {
            $verif = TRUE;  
            $tabErr['mdp'] = FALSE;
        }


        if (!empty($_POST['naissance']))
        {
            $d = $_POST['naissance'];
            if(date('Y') - 14 < date('Y', strtotime($d)))
            {
                $verif = TRUE;
                $tabErr['dateNaiss'] = FALSE;
            }
        }
        else
        {
            $tabErr['dateNaiss'] = FALSE;
            $verif = TRUE;
        }
           
        if(empty($_POST['genre']))
        {
            $verif = TRUE;
            $tabErr['genre'] = FALSE;
        }
        else
        {
             $e = $_POST['genre'];            
        }

        #-----------------------------------------------------------------------------
        
        
        
        if(empty($_FILES['avatar']))
        {
            $verif = TRUE;
            $tabErr['image'] = FALSE;
        }
        else
        {
            
            if ($_FILES['avatar']['name'] != "")
            {
                $image = $_FILES['avatar'];
                $imgdata = file_get_contents($image['tmp_name']);
                $resource = imagecreatefromstring($imgdata);
                $width = imagesx($resource);
                $height = imagesy($resource);
                if ($width > 400 || $height > 400) 
                {
                    $verif = TRUE;
                    $tabErr['image'] = FALSE;
                }
            }
            else
            {
                $verif = TRUE;
                $tabErr['image'] = FALSE;
            }
         }

        if($verif == FALSE)
        {
            if (isset($_FILES['avatar']))
            {
                $name = $_FILES['avatar']['name'];
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $location = $_FILES['avatar']['tmp_name'];
                $name = $a;
                
            
                $imageBD = $a.'.'.$ext;
            }
            else
            {
                $tabErr['image'] = FALSE;

                $verif = TRUE;
            }
        }

        #-----------------------------------------------------------------------------
  
        if($verif == FALSE)
        {
            $stmt = mysqli_prepare($id, "INSERT INTO utilisateur (pseudo, mdp, genre, ddn, avatar) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $a, $b, $e, $d, $imageBD);
            $InscriptionOK = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if($InscriptionOK != 1) /*L'inscription n'est pas bonne*/
            {
                $tabErr['doublon'] = false;
            }
            else /*L'inscription est ok et on se connecte*/
            {
                move_uploaded_file($location, '../avatar/'.$name.'.'.$ext);

                $_POST['pseudoLog'] = $a;
                $_POST['pswLog'] = $_POST['psw'];
                $rien = connexion();
            }
        }

        return $tabErr;
    
    }

    function connexion()
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');
        
        $a = $_POST['pseudoLog'];
        $b = $_POST['pswLog'];
        
        $stmt = mysqli_prepare($id, "SELECT * FROM utilisateur WHERE pseudo = ?");
        mysqli_stmt_bind_param($stmt, 's', $a);
        mysqli_stmt_execute($stmt);
        $res3 = mysqli_stmt_get_result($stmt);
        
        $tab3 = mysqli_fetch_assoc($res3);
        
        if($a === $tab3['pseudo'] && password_verify($b, $tab3['mdp']))
        {
            session_start();
            $_SESSION = $_POST;
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function encoderChat()
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');
    
        $texte = $_POST['message'];
        $datePub = $_POST['date1'];
        $pseudo = $_SESSION['pseudoLog'];
    
        $stmt = mysqli_prepare($id, "INSERT INTO chatmessage (message, temps, emetteur) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sss', $texte, $datePub, $pseudo);
        mysqli_stmt_execute($stmt);
    }


    function encoderChatPrive()
    {
        $id = mysqli_connect("localhost", "root", "root", "chat");
        mysqli_set_charset($id, 'utf8');
    
        $texte = $_POST['message'];
        $datePub = $_POST['date1'];
        $pseudo = $_SESSION['pseudoLog'];
        $ami = $_SESSION['recepteur'];
    
        $stmt = mysqli_prepare($id, "INSERT INTO chatmessage (message, temps, emetteur, recepteur) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $texte, $datePub, $pseudo, $ami);
        mysqli_stmt_execute($stmt);
    }





    function selectUtilisateur()
    {
        $mysqli = new mysqli("localhost", "root", "root", "chat");

        if ($mysqli->connect_errno) {
            echo "Connection échouée !";
            exit();
        }

        $result = $mysqli->query("SELECT * FROM utilisateur");

        if (!$result) {
            echo "Erreur de requête : " . $mysqli->error;
            exit();
        }

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $mysqli->close();

        return $data;
    }

    

    




/*
    function newFiltre($cat)
    {
        $id = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id, 'utf8');
        
        $res2 = mysqli_query($id, "select * from news where categorie = $cat order by publication desc");

        while($tab = mysqli_fetch_assoc($res2))
        {
            echo '<div class = "news">';
                echo "Id news : ";
                echo $tab['idNews'];
                echo "<br/>";
                echo "Titre : ";
                echo $tab['titre'];
                echo "<br/>";
                echo "Texte : ";
                echo $tab['texte'];
                echo "<br/>";
                echo "Publié par : ";
                echo $tab['pseudo'];
                echo "<br/>";
                echo "Date de publication : ";
                echo $tab['publication'];
                echo "<br/>";
                echo "Date d'expiration : ";
                echo $tab['expiration'];
                echo "<br/>";
                echo "Categorie : ";
                echo $tab['categorie'];
                echo "<br/>";
                echo "<br/>";
                echo "<br/>";
                echo "<br/>";
                $verif = $tab['idNews'];
            echo '</div>';
        }
        



        if(!isset($verif))
        {
            echo '<p class = "non">';
                echo "Pas de news dans cette catégorie !";
            echo '</p>';
            
        }
        
    }



    function encoderNews()
    {
        $id = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id, 'utf8');

        $titre = $_POST['titre'];
        $texte = $_POST['texte'];
        $datePub = $_POST['pub'];
        $dateExp = $_POST['exp'];
        $cat = $_POST['cat'];
        $pseudo = $_SESSION['nom'];

        echo '<div class = "non">';
            mysqli_query($id, "insert into news (titre, texte, pseudo, publication, expiration, categorie) values ('$titre', '$texte', '$pseudo', '$datePub', '$dateExp', '$cat')") or die ("Erreur d'enregistrement");
        echo '</div>';
    }


    function modifierNews()
    {
        $id2 = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id2, 'utf8');

        $titre = $_POST['titre'];
        $texte = $_POST['texte'];
        $datePub = $_POST['pub'];
        $dateExp = $_POST['exp'];
        $cat = $_POST['cat'];
        $pseudo = $_SESSION['nom'];
        $id = $_POST['id'];

        if($titre == NULL || $texte == NULL || $datePub == NULL || $cat == NULL || $pseudo == NULL)
        {
            echo '<div class = "non">';
                echo "Champs non valides !";
            echo '</div>';
        }
        else
        {
            echo '<div class = "non">';
                mysqli_query($id2, "UPDATE news SET titre = '$titre' WHERE idNews = $id") or die ("Erreur de modification.");
                mysqli_query($id2, "UPDATE news SET texte = '$texte' WHERE idNews = $id") or die ("Erreur de modification.");
                mysqli_query($id2, "UPDATE news SET publication = '$datePub' WHERE idNews = $id") or die ("Erreur de modification.");
                mysqli_query($id2, "UPDATE news SET expiration = '$dateExp' WHERE idNews = $id") or die ("Erreur de modification.");
                mysqli_query($id2, "UPDATE news SET categorie = '$cat' WHERE idNews = $id") or die ("Erreur de modification.");
            echo '</div>';
        }
    }


    function supprimerNews()
    {
        $id2 = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id2, 'utf8');

        $id = $_POST['id'];

        mysqli_query($id2, "DELETE FROM news WHERE idNews = $id");
    }




    function encoderCat()
    {
        $id = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id, 'utf8');

        $idCatego = $_POST['idCat'];
        $nomCategorie = $_POST['categorie'];

        if(preg_match("#[A-Za-z0-9+-/ ,*_]{3,50}#", $nomCategorie))
        {
            echo '<div class = "non">';
                mysqli_query($id, "insert into categorie (id, nom) values ('$idCatego', '$nomCategorie')")or die ("Le nom saisit existe déja !");
            echo '</div>';
        }
        else
        {
            echo '<div class = "non">';
                echo "Champs non valides !";
            echo '</div>';
        }

    }



    function modifierCat()
    {
        $id2 = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id2, 'utf8');

        $idCatego2 = $_POST['idCat'];
        $nomCategorie2 = $_POST['categorie'];


        if(preg_match("#[A-Za-z0-9+-/ ,*_]{3,50}#", $nomCategorie))
        {
            if($idCatego2 == NULL || $nomCategorie2 == NULL)
            {
                echo '<div class = "non">';
                    echo "Champs non valides !";
                echo '</div>';
            }
            else
            {
                echo '<div class = "non">';
                    mysqli_query($id2, "UPDATE categorie SET id = '$idCatego2' WHERE id = $idCatego2") or die ("Erreur de modification.");
                    mysqli_query($id2, "UPDATE categorie SET nom = '$nomCategorie2' WHERE id = $idCatego2") or die ("Erreur de modification.");
                echo '</div>';
            }
        }

    }


    function categorie()
    {
        $id = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id, 'utf8');
        
        $resCat = mysqli_query($id, "select * from categorie");
        while($tab = mysqli_fetch_assoc($resCat))
        {
            echo '<div class = "news">';
                echo "Id : ";
                echo $tab['id'];
                echo "<br/>";
                echo "Nom : ";
                echo $tab['nom'];
                echo "<br/>";
            echo '</div>';
        }
    }



    function supprimerCategorie()
    {
        $id2 = mysqli_connect("localhost", "root", "root", "projetsebastien");
        mysqli_set_charset($id2, 'utf8');

        $idSupp = $_POST['idDelete'];

        $req = mysqli_query ($id2, "select * from news where categorie = $idSupp");

        

        $cptr = 0;


        while($new = mysqli_fetch_assoc ($req))
        {
            $cptr++;
        }

        if($idSupp == 0)
        {
            echo '<div class = "non">';
                echo "Aucune catégorie séléctionnée";
            echo '</div>';
        }


        
        if($cptr == 0)
        {
            mysqli_query($id2, "DELETE FROM categorie WHERE id = $idSupp");
        }
        else
        {
            echo '<div class = "non">';
                echo "Impossible de supprimer cette catégorie";
            echo '</div>';
        }


    }
*/

?>