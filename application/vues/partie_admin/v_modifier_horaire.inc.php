<?php
// Afficher les messages d'alerte
if (isset($_SESSION['message_admin'])) {
    $typeClass = $_SESSION['type_message_admin'] == 'success' ? 'alert-success' : 'alert-error';
    echo '<div class="alert ' . $typeClass . '">' . $_SESSION['message_admin'] . '</div>';
    unset($_SESSION['message_admin']);
    unset($_SESSION['type_message_admin']);
}
?>

<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-clock"></i> Gestion des Horaires d'Ouverture</h1>
        <p>Modifiez les heures d'ouverture de votre salon</p>
    </div>
</div>

<div class="admin-form">
    <form action="index.php?controleur=Horaires&action=modifierLesHoraires" method="post" class="horaires-form">
        <?php if (!empty(VariablesGlobales::$lesHoraires)) { ?>
            <?php
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            
            foreach (VariablesGlobales::$lesHoraires as $index => $horaire) {
                if ($index >= 7) break;
            ?>
                <div class="horaire-card">
                    <div class="horaire-header">
                        <h3><i class="fas fa-calendar-day"></i> <?php echo $jours[$index]; ?></h3>
                        <label class="switch">
                            <input type="checkbox" name="ferme[<?php echo $index; ?>]" value="1" 
                                   <?php echo ($horaire->ferme == 1) ? 'checked' : ''; ?>
                                   onchange="toggleHoraires(<?php echo $index; ?>, this)">
                            <span class="slider"></span>
                            <span class="switch-label">Fermé</span>
                        </label>
                    </div>

                    <input type="hidden" name="idHoraire[]" value="<?php echo $horaire->idHoraire; ?>">

                    <div class="horaire-times" id="horaires<?php echo $index; ?>" 
                         <?php echo ($horaire->ferme == 1) ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                        
                        <div class="time-period">
                            <h4><i class="fas fa-sun"></i> Matin</h4>
                            <div class="time-inputs">
                                <div class="input-group">
                                    <label>Ouverture</label>
                                    <input type="time" name="ouvertureMatin[<?php echo $index; ?>]" 
                                           value="<?php echo $horaire->heureOuvertureMatin; ?>"
                                           <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?>>
                                </div>
                                <div class="input-group">
                                    <label>Fermeture</label>
                                    <input type="time" name="fermetureMatin[<?php echo $index; ?>]" 
                                           value="<?php echo $horaire->heureFermetureMatin; ?>"
                                           <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>

                        <div class="time-period">
                            <h4><i class="fas fa-moon"></i> Après-midi</h4>
                            <div class="time-inputs">
                                <div class="input-group">
                                    <label>Ouverture</label>
                                    <input type="time" name="ouvertureAprem[<?php echo $index; ?>]" 
                                           value="<?php echo $horaire->heureOuvertureAprem; ?>"
                                           <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?>>
                                </div>
                                <div class="input-group">
                                    <label>Fermeture</label>
                                    <input type="time" name="fermetureAprem[<?php echo $index; ?>]" 
                                           value="<?php echo $horaire->heureFermetureAprem; ?>"
                                           <?php echo ($horaire->ferme == 1) ? 'disabled' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Sauvegarder les horaires
                </button>
                <a href="index.php?controleur=Horaires&action=listerHorairesAdmin" class="btn-action btn-edit">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        <?php } else { ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                Aucun horaire trouvé.
            </div>
        <?php } ?>
    </form>
</div>

<style>
/* Styles spécifiques pour la page de modification des horaires */
.horaires-form {
    display: grid;
    gap: 20px;
}

.horaire-card {
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.horaire-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.horaire-header h3 {
    color: #2c3e50;
    margin: 0;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Switch toggle */
.switch {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
    background-color: #ccc;
    border-radius: 24px;
    transition: 0.3s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: 0.3s;
}

input:checked + .slider {
    background-color: #27ae60;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.switch-label {
    font-size: 0.9rem;
    color: #7f8c8d;
    font-weight: 500;
}

.horaire-times {
    display: grid;
    gap: 20px;
}

.time-period {
    background: rgba(255,255,255,0.5);
    border-radius: 10px;
    padding: 20px;
}

.time-period h4 {
    color: #2c3e50;
    margin: 0 0 15px 0;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.time-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.input-group label {
    font-weight: 600;
    color: #7f8c8d;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-group input[type="time"] {
    padding: 10px 12px;
    border: 2px solid rgba(0,0,0,0.1);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.8);
}

.input-group input[type="time"]:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    background: white;
}

.input-group input[type="time"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .horaire-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .time-inputs {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
function toggleHoraires(index, checkbox) {
    const horairesDiv = document.getElementById('horaires' + index);
    const inputs = horairesDiv.querySelectorAll('input[type="time"]');
    
    if (checkbox.checked) {
        horairesDiv.style.opacity = '0.5';
        horairesDiv.style.pointerEvents = 'none';
        inputs.forEach(input => input.disabled = true);
    } else {
        horairesDiv.style.opacity = '1';
        horairesDiv.style.pointerEvents = 'auto';
        inputs.forEach(input => input.disabled = false);
    }
}
</script>
