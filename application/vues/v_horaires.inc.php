<?php if (!empty(VariablesGlobales::$lesHoraires)) { ?>
    <?php foreach (VariablesGlobales::$lesHoraires as $index => $horaire) { ?>
        <li class="horaire-item">
            <label for="nomJour<?php echo $index; ?>" class="horaire-label"><?php echo $horaire->idHoraire; ?></label> :

            <!-- Affichage des horaires pour chaque jour -->
            <p>Matin: <?php echo $horaire->heureOuvertureMatin . " - " . $horaire->heureFermetureMatin; ?></p>
            <p>Après-midi: <?php echo $horaire->heureOuvertureAprem . " - " . $horaire->heureFermetureAprem; ?></p>
        </li>
    <?php } ?>
<?php } else { ?>
    <p>Aucun horaire trouvé.</p>
<?php } ?>
