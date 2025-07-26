<?php if (!empty(VariablesGlobales::$lesHoraires)) { ?>
    <div class="horaires-list">
        <?php 
            $jours = [
                1 => 'Lundi',
                2 => 'Mardi', 
                3 => 'Mercredi',
                4 => 'Jeudi',
                5 => 'Vendredi',
                6 => 'Samedi',
                7 => 'Dimanche'
            ];
        ?>
        <?php foreach (VariablesGlobales::$lesHoraires as $horaire) { ?>
            <div class="horaire-item">
                <strong><?php echo isset($jours[$horaire->idJour]) ? $jours[$horaire->idJour] : 'Jour ' . $horaire->idJour; ?> :</strong>
                <span class="horaire-heures">
                    <?php 
                        $affichage = [];
                        if (!empty($horaire->heureOuvertureMatin) && !empty($horaire->heureFermetureMatin)) {
                            $affichage[] = $horaire->heureOuvertureMatin . " - " . $horaire->heureFermetureMatin;
                        }
                        if (!empty($horaire->heureOuvertureAprem) && !empty($horaire->heureFermetureAprem)) {
                            $affichage[] = $horaire->heureOuvertureAprem . " - " . $horaire->heureFermetureAprem;
                        }
                        echo $affichage ? implode(' / ', $affichage) : '<span class="ferme">Fermé</span>';
                    ?>
                </span>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="no-horaires">
        <i class="fas fa-clock"></i>
        <p>Aucun horaire disponible.</p>
    </div>
<?php } ?>
