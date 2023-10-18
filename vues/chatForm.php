<br/>
<br/>
<br/>
<div class = "formu">
    <form traitement = "../controllerChat.php" method = "post">
    <label for="message">Nouveau message :</label><br>
    <textarea id="message" name="message" maxlength="1000"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea><br>
    <input type="submit" value="Envoyer">
    </form>
</div>
<div>
    <a class = "formu3" href="../controllers/controllerDeconnexion.php"> Deconnection </a>
</div>