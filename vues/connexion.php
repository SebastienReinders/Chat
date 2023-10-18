<?php
    include("header.php");
?>

<h1>Vous êtes sur la page de connexion</h1>
<br/>
<br/> 

<div class = "formu">
    <form traitement = "../controllerConnexion.php" method = "post">
    <?php 
            if ($bon != 2) 
            { 
        ?> 
                <span class="error"><?php echo $bon; ?></span>
        <?php 
            } 
        ?>
        <br/>
        <label for = "pseudoLog" id = "pseudoLog">Pseudo : </label>
        <input type = "text" name = "pseudoLog">
        <br/>
        <br/>
        <label for = "pswLog" id = "pswLog">Mot de passe : </label>
        <input type = "password" name = "pswLog"> 
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