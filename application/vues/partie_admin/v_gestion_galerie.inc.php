<?php
// Vérification des messages de session
$messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
$messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
?>

<div class="admin-container">
    <!-- En-tête de la page -->
    <div class="admin-page-header">
        <div class="header-content">
            <h1><i class="fas fa-images"></i> Gestion de la Galerie</h1>
            <p>Gérez les images de votre galerie pour présenter vos réalisations</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controleur=Galerie&action=afficherAjoutImage" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une image
            </a>
        </div>
    </div>

    <!-- Messages de notification -->
    <?php if ($messageSucces): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($messageSucces); ?>
        </div>
    <?php endif; ?>

    <?php if ($messageErreur): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($messageErreur); ?>
        </div>
    <?php endif; ?>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $statistiques->totalImages; ?></h3>
                <p>Total des images</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $statistiques->imagesActives; ?></h3>
                <p>Images actives</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon inactive">
                <i class="fas fa-eye-slash"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $statistiques->imagesInactives; ?></h3>
                <p>Images inactives</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-hdd"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo round($statistiques->tailleTotale / 1024 / 1024, 2); ?> MB</h3>
                <p>Espace utilisé</p>
            </div>
        </div>
    </div>

    <!-- Liste des images -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Images de la galerie</h3>
            <p class="text-muted">Gérez l'ordre et le statut de vos images</p>
        </div>
        
        <div class="card-body">
            <?php if (empty($lesImages)): ?>
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <h3>Aucune image dans la galerie</h3>
                    <p>Commencez par ajouter votre première image pour présenter vos réalisations</p>
                    <a href="index.php?controleur=Galerie&action=afficherAjoutImage" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une image
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="admin-table" id="galerieTable">
                        <thead>
                            <tr>
                                <th width="80">Image</th>
                                <th>Nom</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th width="100">Ordre</th>
                                <th width="100">Statut</th>
                                <th width="120">Date</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="galerieTableBody">
                            <?php foreach ($lesImages as $image): ?>
                                <tr data-id="<?php echo $image->idImage; ?>">
                                    <td>
                                        <div class="image-preview">
                                            <?php if (file_exists($image->cheminImage)): ?>
                                                <img src="<?php echo htmlspecialchars($image->cheminImage); ?>" 
                                                     alt="<?php echo htmlspecialchars($image->titreImage); ?>"
                                                     class="preview-thumbnail">
                                            <?php else: ?>
                                                <div class="image-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($image->nomImage); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo round($image->taille / 1024, 1); ?> KB
                                        </small>
                                    </td>
                                    <td><?php echo htmlspecialchars($image->titreImage); ?></td>
                                    <td>
                                        <?php 
                                        $description = htmlspecialchars($image->description);
                                        echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                        ?>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control ordre-input" 
                                               value="<?php echo $image->ordre; ?>"
                                               min="0"
                                               data-id="<?php echo $image->idImage; ?>">
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $image->actif ? 'badge-success' : 'badge-secondary'; ?>">
                                            <?php echo $image->actif ? 'Actif' : 'Inactif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y', strtotime($image->dateAjout)); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?controleur=Galerie&action=afficherModifierImage&id=<?php echo $image->idImage; ?>" 
                                               class="btn btn-sm btn-secondary" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm <?php echo $image->actif ? 'btn-warning' : 'btn-success'; ?>"
                                                    onclick="toggleStatutImage(<?php echo $image->idImage; ?>)"
                                                    title="<?php echo $image->actif ? 'Désactiver' : 'Activer'; ?>">
                                                <i class="fas fa-<?php echo $image->actif ? 'eye-slash' : 'eye'; ?>"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="supprimerImage(<?php echo $image->idImage; ?>, '<?php echo htmlspecialchars($image->titreImage); ?>')"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Actions en lot -->
                <div class="table-actions">
                    <button type="button" class="btn btn-secondary" onclick="sauvegarderOrdre()">
                        <i class="fas fa-save"></i> Sauvegarder l'ordre
                    </button>
                    <a href="index.php?controleur=Galerie&action=afficherGalerie" target="_blank" class="btn btn-info">
                        <i class="fas fa-eye"></i> Voir la galerie publique
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques à la galerie */
.image-preview {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
}

.preview-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.5em;
}

.ordre-input {
    width: 60px;
    text-align: center;
    padding: 4px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9em;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75em;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-secondary {
    background: #e2e3e5;
    color: #6c757d;
}

.btn-group {
    display: flex;
    gap: 4px;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 0.8em;
}

.table-actions {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
    justify-content: flex-start;
}

/* Animation pour les changements d'ordre */
.ordre-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .admin-table {
        font-size: 0.9em;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-sm {
        padding: 6px 8px;
    }
}
</style>

<script>
// Fonction pour sauvegarder l'ordre des images
function sauvegarderOrdre() {
    const ordreInputs = document.querySelectorAll('.ordre-input');
    const ordreImages = {};
    
    ordreInputs.forEach(input => {
        ordreImages[input.dataset.id] = parseInt(input.value) || 0;
    });
    
    // Afficher un indicateur de chargement
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
    btn.disabled = true;
    
    fetch('index.php?controleur=Galerie&action=updateOrdreImages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'ordre=' + JSON.stringify(ordreImages)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Ordre sauvegardé avec succès !', 'success');
        } else {
            showNotification('Erreur lors de la sauvegarde : ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Erreur de connexion', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Fonction pour changer le statut d'une image
function toggleStatutImage(idImage) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cette image ?')) {
        window.location.href = 'index.php?controleur=Galerie&action=toggleStatutImage&id=' + idImage;
    }
}

// Fonction pour supprimer une image
function supprimerImage(idImage, titreImage) {
    if (confirm('Êtes-vous sûr de vouloir supprimer l\'image "' + titreImage + '" ?\n\nCette action est irréversible.')) {
        window.location.href = 'index.php?controleur=Galerie&action=supprimerImage&id=' + idImage;
    }
}

// Fonction pour afficher des notifications
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-' + (type === 'success' ? 'success' : 'error');
    notification.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    
    // Insérer la notification après l'en-tête
    const header = document.querySelector('.admin-page-header');
    header.parentNode.insertBefore(notification, header.nextSibling);
    
    // Supprimer la notification après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Sauvegarde automatique de l'ordre lors du changement
document.addEventListener('DOMContentLoaded', function() {
    const ordreInputs = document.querySelectorAll('.ordre-input');
    
    ordreInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optionnel : sauvegarde automatique après un délai
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                sauvegarderOrdre();
            }, 2000);
        });
    });
});
</script> 