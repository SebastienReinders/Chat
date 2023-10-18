<?php
    include("header.php");
?>

<h1>Vous êtes sur la page d'inscription</h1>
<br/>
<br/>
<br/>
<br/>
<div class = "formu">
    <form traitement = "../controllerInscription.php" method = "post" enctype="multipart/form-data">
        <?php 
            if (isset($tab["general"])) 
            { 
        ?>
                <span class="error"><?php echo $tab["general"]; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <br/>
        <label for = "pseudo" id = "pseudo">Pseudo : </label>
        <input type = "text" name = "pseudo">

        <?php 
            if (isset($tab["pseudo"])) 
            { 
        ?>
                <span class="error"><?php echo $tab["pseudo"]; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <br/>
        <label for = "psw" id = "psw">Mot de passe : </label>
        <input type = "password" name = "psw"> 

        <?php 
            if (isset($tab["mdp"])) 
            { 
        ?>
                <span class="error"><?php echo $tab["mdp"]; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <br/>
        <label for = "psw2" id = "psw2">Confirmation mot de passe : </label>
        <input type = "password" name = "psw2">

        <br/>
        <br/>   
        <label>
            <input type="radio" name="genre" value="M">
                Masculin
        </label> 
        <label>
            <input type="radio" name="genre" value="F">
                Féminin
        </label>
        <label>
            <input type="radio" name="genre" value="S">
                Sans réponse
        </label>

        <?php 
            if (isset($tab["genre"])) 
            { 
        ?>
                <span class="error"><?php echo $tab["genre"]; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <br/>
        <label for = "naissance" id = "naissance">Date de naissance : </label>
        <input type = "date" name = "naissance">

        <?php 
            if (isset($tab["dateNaiss"])) 
            { 
        ?>
                <span class="error"><?php echo $tab["dateNaiss"]; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <br/>
        
        <label for="avatar">Avatar : </label>
        <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg">
    

        <?php 
            if (isset($tab["image"])) 
            { 
        ?>
                <span class="error"><?php echo $tab["image"]; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <br/>
        <input type = "submit" value = "soumettre"> 
        <br/>
        <br/>
    </form>
</div>
<?php
    include("footer.php");
?>