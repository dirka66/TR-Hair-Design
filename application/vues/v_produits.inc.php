<ul>
    <?php
    foreach (VariablesGlobales::$lesProduits as $produit) {
        ?>
        <li>
            <u>
                <?php echo $produit->nomProduit; ?>
            </u>
            <?php
            echo " : ";
            echo $produit->prix;
            echo"€";
            echo " par ";
            echo $produit->unite;
            ?>
        </li> <br>
    <?php }
    ?>
</ul>
