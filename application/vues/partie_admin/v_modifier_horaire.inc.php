<form action="index.php?controleur=Horaires&action=modifierLesHoraires" method="post" class="form-container">
    <ul>
        <?php if (!empty(VariablesGlobales::$lesHoraires)) { ?>
            <?php
            // Limiter l'affichage aux 7 jours de la semaine
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

            // Limiter le nombre d'itérations à 7
            foreach (VariablesGlobales::$lesHoraires as $index => $horaire) {
                if ($index >= 7) break; // Arrêter après dimanche
            ?>
                <li class="horaire-item">
                    <label for="nomJour<?php echo $index; ?>" class="horaire-label"><?php echo $jours[$index]; ?></label> :

                    <!-- Champ caché pour l'idHoraire -->
                    <input type="hidden" name="idHoraire[]" value="<?php echo $horaire->idHoraire; ?>">

                    <!-- Champ pour marquer si le jour est fermé -->
                    <label for="ferme<?php echo $index; ?>" class="horaire-time-label"><?php echo $jours[$index]; ?> Fermé :</label>
                    <input type="checkbox" name="ferme[<?php echo $index; ?>]" value="1" 
                    <?php echo ($horaire->ferme == 1) ? 'checked' : ''; ?> class="horaire-input" 
                    onclick="toggleHoraires(<?php echo $index; ?>, this)">
                    
                    <!-- Matin -->
                    <div class="horaire-time">
                        <label for="ouvertureMatin<?php echo $index; ?>" class="horaire-time-label">Ouverture Matin :</label>
                        <input type="time" name="ouvertureMatin[<?php echo $index; ?>]" value="<?php echo $horaire->heureOuvertureMatin; ?>" 
                        <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?> class="horaire-input" id="ouvertureMatin<?php echo $index; ?>">
                        
                        <label for="fermetureMatin<?php echo $index; ?>" class="horaire-time-label">Fermeture Matin :</label>
                        <input type="time" name="fermetureMatin[<?php echo $index; ?>]" value="<?php echo $horaire->heureFermetureMatin; ?>" 
                        <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?> class="horaire-input" id="fermetureMatin<?php echo $index; ?>">
                    </div>

                    <!-- Après-midi -->
                    <div class="horaire-time">
                        <label for="ouvertureAprem<?php echo $index; ?>" class="horaire-time-label">Ouverture Après-midi :</label>
                        <input type="time" name="ouvertureAprem[<?php echo $index; ?>]" value="<?php echo $horaire->heureOuvertureAprem; ?>" 
                        <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?> class="horaire-input" id="ouvertureAprem<?php echo $index; ?>">
                        
                        <label for="fermetureAprem<?php echo $index; ?>" class="horaire-time-label">Fermeture Après-midi :</label>
                        <input type="time" name="fermetureAprem[<?php echo $index; ?>]" value="<?php echo $horaire->heureFermetureAprem; ?>" 
                        <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?> class="horaire-input" id="fermetureAprem<?php echo $index; ?>">
                    </div>

                    <!-- Affichage de l'état (ouvert/fermé) -->
                    <?php 
                    if ($horaire->ferme == 1) {
                        $etatMatin = "Fermé";
                        $etatAprem = "Fermé";
                    } else {
                        $etatMatin = (!empty($horaire->heureOuvertureMatin) && !empty($horaire->heureFermetureMatin)) ? "Ouvert" : "Fermé"; 
                        $etatAprem = (!empty($horaire->heureOuvertureAprem) && !empty($horaire->heureFermetureAprem)) ? "Ouvert" : "Fermé";
                    }
                    ?>
                    <p class="horaire-status">Matin: <span class="status"><?php echo $etatMatin; ?></span></p>
                    <p class="horaire-status">Après-midi: <span class="status"><?php echo $etatAprem; ?></span></p>
                </li>
                <br>
            <?php } ?>
        <?php } else { ?>
            <p>Aucun horaire trouvé.</p>
        <?php } ?>
    </ul>

    <!-- Bouton pour enregistrer les modifications -->
    <button type="submit" name="modifier" class="btn-submit">Enregistrer les modifications</button>
    <!-- Bouton retour vers la page admin -->
    <a href="index.php?controleur=Admin&action=afficherIndex" class="btn-submit">Retour au menu Admin</a>
</form>

<script>
    function toggleHoraires(index, checkbox) {
        const isChecked = checkbox.checked;
        
        // Sélectionner les champs d'heure pour le jour donné
        const ouvertureMatin = document.getElementById('ouvertureMatin' + index);
        const fermetureMatin = document.getElementById('fermetureMatin' + index);
        const ouvertureAprem = document.getElementById('ouvertureAprem' + index);
        const fermetureAprem = document.getElementById('fermetureAprem' + index);
        
        // Activer ou désactiver les champs en fonction de la case à cocher
        if (isChecked) {
            ouvertureMatin.disabled = true;
            fermetureMatin.disabled = true;
            ouvertureAprem.disabled = true;
            fermetureAprem.disabled = true;
        } else {
            ouvertureMatin.disabled = false;
            fermetureMatin.disabled = false;
            ouvertureAprem.disabled = false;
            fermetureAprem.disabled = false;
        }
    }
</script>
