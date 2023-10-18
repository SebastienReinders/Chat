</br>
<p>
    <div class = "boucle">
        <img src = "../avatar/<?php echo $row['avatar'] ?>" alt="avatar utilisateur" width="40" height="40">
         A <?php echo htmlspecialchars($row['temps']) ?>
        <span class= "<?php echo $row['genre'] ?>" >
            <?php echo htmlspecialchars($row['pseudo']) ?>
        </span>
        a dit :
 
        <div class = "mess">
            <?php echo htmlspecialchars($row['message']) ?>
        </div>
        </br>-------------------------------
    </div>
</p>