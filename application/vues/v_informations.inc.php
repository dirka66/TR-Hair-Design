<br> 
<?php
    foreach (VariablesGlobales::$lesInformations as $information) {
        ?>
            <u> <h1> <center>
                <?php echo $information->titreInformation; ?>
            </u> </h1> </center> <br>
            <?php
            echo $information->libelleInformation;
            ?>
    <?php }
    ?>

