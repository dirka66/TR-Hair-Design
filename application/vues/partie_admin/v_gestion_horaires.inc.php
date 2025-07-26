<?php
// Sécurité
if (!defined('INDEX_PRINCIPAL')) {
    header('Location: ../index.php');
    exit;
}
?>

<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-clock"></i> Gestion des Horaires</h1>
        <p>Configurez les horaires d'ouverture de votre salon</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn-primary" onclick="sauvegarderTousLesHoraires()">
            <i class="fas fa-save"></i> Sauvegarder
        </button>
    </div>
</div>

    <?php if ($messageSucces): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($messageSucces); ?>
        </div>
    <?php endif; ?>

    <?php if ($messageErreur): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($messageErreur); ?>
        </div>
    <?php endif; ?>

    <!-- Statistiques -->
    <div class="stats-grid">
        <?php 
        $gestionHoraires = new GestionHoraires();
        $stats = GestionHoraires::getStatistiquesHoraires();
        ?>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats->totalJours ?? 7; ?></h3>
                <p>Jours configurés</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats->heuresMoyennes ?? 8, 1); ?>h</h3>
                <p>Heures moyennes/jour</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats->joursOuverts ?? 6; ?></h3>
                <p>Jours ouverts</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-door-closed"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats->joursFermes ?? 1; ?></h3>
                <p>Jours fermés</p>
            </div>
        </div>
    </div>

    <!-- Gestion des horaires -->
    <div class="admin-form">
        <h3><i class="fas fa-calendar-alt"></i> Horaires d'ouverture</h3>
        <p>Configurez les horaires d'ouverture du salon pour chaque jour de la semaine</p>
            <form id="formHoraires" method="post" action="index.php?controleur=Horaires&action=modifierLesHoraires">
                
                <div class="horaires-grid">
                    <?php 
                    if (!empty($lesHoraires)) {
                        foreach ($lesHoraires as $index => $horaire): 
                    ?>
                        <div class="horaire-jour">
                            <div class="jour-header">
                                <h4><?php echo htmlspecialchars($horaire->jour); ?></h4>
                                <label class="toggle-switch">
                                    <input type="checkbox" 
                                           name="ouvert[<?php echo $index; ?>]" 
                                           value="1"
                                           <?php echo ($horaire->ferme == 0) ? 'checked' : ''; ?>
                                           onchange="toggleJour(<?php echo $index; ?>)">
                                    <span class="toggle-slider"></span>
                                    <span class="switch-label">Ouvert</span>
                                </label>
                            </div>
                            
                            <div class="horaire-fields" id="fields-<?php echo $index; ?>" 
                                 style="<?php echo ($horaire->ferme == 0) ? '' : 'display: none;'; ?>">
                                
                                <!-- Période matin -->
                                <div class="periode-group">
                                    <label>Matin</label>
                                    <div class="time-inputs">
                                        <input type="time" 
                                               name="ouvertureMatin[<?php echo $index; ?>]"
                                               value="<?php echo htmlspecialchars($horaire->heureOuvertureMatin ?? ''); ?>"
                                               placeholder="Ouverture">
                                        <span>à</span>
                                        <input type="time" 
                                               name="fermetureMatin[<?php echo $index; ?>]"
                                               value="<?php echo htmlspecialchars($horaire->heureFermetureMatin ?? ''); ?>"
                                               placeholder="Fermeture">
                                    </div>
                                </div>
                                
                                <!-- Période après-midi -->
                                <div class="periode-group">
                                    <label>Après-midi</label>
                                    <div class="time-inputs">
                                        <input type="time" 
                                               name="ouvertureAprem[<?php echo $index; ?>]"
                                               value="<?php echo htmlspecialchars($horaire->heureOuvertureAprem ?? ''); ?>"
                                               placeholder="Ouverture">
                                        <span>à</span>
                                        <input type="time" 
                                               name="fermetureAprem[<?php echo $index; ?>]"
                                               value="<?php echo htmlspecialchars($horaire->heureFermetureAprem ?? ''); ?>"
                                               placeholder="Fermeture">
                                    </div>
                                </div>
                                <input type="hidden" name="idHoraire[<?php echo $index; ?>]" value="<?php echo (int)$horaire->idHoraire; ?>">
                            </div>
                        </div>
                    <?php 
                        endforeach; 
                    } else {
                        echo '<p class="alert alert-error">Aucun horaire trouvé.</p>';
                    }
                    ?>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Sauvegarder les horaires
                    </button>
                    <button type="button" class="btn-action btn-edit" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Annuler les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Aperçu public -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-eye"></i> Aperçu public</h3>
            <p class="text-muted">Voici comment vos horaires apparaîtront sur le site</p>
        </div>
        
        <div class="card-body">
            <div class="horaires-public-preview">
                <?php 
                if (!empty($lesHoraires)) {
                    foreach ($lesHoraires as $horaire): 
                        $ouvert = ($horaire && isset($horaire->ferme) && $horaire->ferme == 0);
                ?>
                    <div class="horaire-preview-item <?php echo (!$ouvert) ? 'ferme' : 'ouvert'; ?>">
                        <span class="jour"><?php echo htmlspecialchars($horaire->jour); ?></span>
                        <span class="heures">
                            <?php if ($ouvert): ?>
                                <?php echo substr($horaire->heureOuvertureMatin ?? '', 0, 5); ?> - <?php echo substr($horaire->heureFermetureMatin ?? '', 0, 5); ?>
                                <?php if ($horaire->heureOuvertureAprem && $horaire->heureFermetureAprem): ?>
                                    et <?php echo substr($horaire->heureOuvertureAprem, 0, 5); ?> - <?php echo substr($horaire->heureFermetureAprem, 0, 5); ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-danger">Fermé</span>
                            <?php endif; ?>
                        </span>
                        <span class="statut">
                            <?php if ($ouvert): ?>
                                <i class="fas fa-check-circle text-success"></i>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-danger"></i>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php 
                    endforeach; 
                } else {
                    echo '<p class="text-muted">Aucun horaire configuré.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleJour(index) {
    const checkbox = document.querySelector(`input[name="ouvert[${index}]"]`);
    const fields = document.getElementById(`fields-${index}`);
    
    if (checkbox.checked) {
        fields.style.display = 'block';
    } else {
        fields.style.display = 'none';
    }
}

function sauvegarderTousLesHoraires() {
    document.getElementById('formHoraires').submit();
}

function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir annuler toutes les modifications ?')) {
        location.reload();
    }
}

// Validation des heures
document.addEventListener('DOMContentLoaded', function() {
    const timeInputs = document.querySelectorAll('input[type="time"]');
    
    timeInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateTimeLogic(this);
        });
    });
});

function validateTimeLogic(input) {
    const name = input.name;
    const index = name.match(/\[(\d+)\]/)[1];
    
    const ouvertureMatin = document.querySelector(`input[name="ouvertureMatin[${index}]"]`);
    const fermetureMatin = document.querySelector(`input[name="fermetureMatin[${index}]"]`);
    const ouvertureAprem = document.querySelector(`input[name="ouvertureAprem[${index}]"]`);
    const fermetureAprem = document.querySelector(`input[name="fermetureAprem[${index}]"]`);
    
    // Validation basique des heures
    if (ouvertureMatin && fermetureMatin && ouvertureMatin.value && fermetureMatin.value) {
        if (ouvertureMatin.value >= fermetureMatin.value) {
            alert('L\'heure d\'ouverture du matin doit être antérieure à l\'heure de fermeture du matin');
            return false;
        }
    }
    
    if (ouvertureAprem && fermetureAprem && ouvertureAprem.value && fermetureAprem.value) {
        if (ouvertureAprem.value >= fermetureAprem.value) {
            alert('L\'heure d\'ouverture de l\'après-midi doit être antérieure à l\'heure de fermeture de l\'après-midi');
            return false;
        }
    }
    
    return true;
}
</script>

<style>
.horaires-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.horaire-jour {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.horaire-jour:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.1);
}

.jour-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.jour-header h4 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.toggle-switch {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
    background-color: #ccc;
    border-radius: 24px;
    transition: 0.3s;
}

.toggle-slider:before {
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

input:checked + .toggle-slider {
    background-color: #28a745;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.switch-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.horaire-fields {
    animation: slideDown 0.3s ease;
}

.periode-group {
    margin-bottom: 1rem;
}

.periode-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #495057;
}

.time-inputs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.time-inputs input[type="time"] {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 0.9rem;
}

.time-inputs span {
    color: #6c757d;
    font-weight: 500;
}

.pause-fields {
    margin-top: 0.5rem;
}

.note-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    resize: vertical;
    font-size: 0.9rem;
}

.horaires-public-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
}

.horaire-preview-item {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #dee2e6;
}

.horaire-preview-item:last-child {
    border-bottom: none;
}

.horaire-preview-item.ferme {
    opacity: 0.6;
}

.horaire-preview-item .jour {
    font-weight: 600;
    color: #495057;
}

.horaire-preview-item .heures {
    color: #6c757d;
}

.horaire-preview-item .heures small {
    display: block;
    font-style: italic;
    margin-top: 0.25rem;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        max-height: 300px;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .horaires-grid {
        grid-template-columns: 1fr;
    }
    
    .horaire-preview-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: center;
    }
}
</style>
