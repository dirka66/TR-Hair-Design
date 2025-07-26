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
            <h1><i class="fas fa-edit"></i> Modifier une Image</h1>
            <p>Modifiez les informations de votre image de galerie</p>
        </div>
        <div class="header-actions">
            <a href="index.php?controleur=Galerie&action=afficherGestionGalerie" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la galerie
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

    <!-- Formulaire de modification -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> Informations de l'image</h3>
            <p class="text-muted">Modifiez les informations de votre image</p>
        </div>
        
        <div class="card-body">
            <form method="post" action="index.php?controleur=Galerie&action=modifierImage" enctype="multipart/form-data" class="admin-form">
                
                <input type="hidden" name="idImage" value="<?php echo $image->idImage; ?>">
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nomImage">Nom de l'image *</label>
                        <input type="text" 
                               id="nomImage" 
                               name="nomImage" 
                               class="form-control" 
                               required 
                               value="<?php echo htmlspecialchars($image->nomImage); ?>"
                               maxlength="255">
                        <small class="form-help">Nom technique de l'image (sans espaces ni caractères spéciaux)</small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="titreImage">Titre public *</label>
                        <input type="text" 
                               id="titreImage" 
                               name="titreImage" 
                               class="form-control" 
                               required 
                               value="<?php echo htmlspecialchars($image->titreImage); ?>"
                               maxlength="100">
                        <small class="form-help">Titre qui sera affiché aux visiteurs</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control" 
                              rows="4" 
                              placeholder="Décrivez cette réalisation, les techniques utilisées, etc."
                              maxlength="500"><?php echo htmlspecialchars($image->description); ?></textarea>
                    <small class="form-help">Description détaillée de l'image (optionnel)</small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Nouvelle image (optionnel)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="form-control file-input" 
                                   accept="image/*">
                            <div class="file-upload-info">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span class="file-text">Choisir une nouvelle image</span>
                            </div>
                        </div>
                        <small class="form-help">
                            Laissez vide pour conserver l'image actuelle<br>
                            Formats acceptés : JPG, PNG, GIF, WEBP<br>
                            Taille maximum : 5MB
                        </small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="ordre">Ordre d'affichage</label>
                        <input type="number" 
                               id="ordre" 
                               name="ordre" 
                               class="form-control" 
                               value="<?php echo $image->ordre; ?>" 
                               min="0" 
                               max="999">
                        <small class="form-help">Ordre d'affichage dans la galerie (0 = premier)</small>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" 
                               id="actif" 
                               name="actif" 
                               class="form-check-input" 
                               <?php echo $image->actif ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="actif">
                            Activer cette image
                        </label>
                        <small class="form-help">Décochez pour masquer temporairement l'image</small>
                    </div>
                </div>

                <!-- Image actuelle -->
                <div class="form-group">
                    <label>Image actuelle</label>
                    <div class="current-image-container">
                        <?php if (file_exists($image->cheminImage)): ?>
                            <div class="current-image">
                                <img src="<?php echo htmlspecialchars($image->cheminImage); ?>" 
                                     alt="<?php echo htmlspecialchars($image->titreImage); ?>"
                                     class="current-image-preview">
                                <div class="image-info">
                                    <p><strong>Fichier :</strong> <?php echo basename($image->cheminImage); ?></p>
                                    <p><strong>Taille :</strong> <?php echo round($image->taille / 1024, 1); ?> KB</p>
                                    <p><strong>Type :</strong> <?php echo $image->type; ?></p>
                                    <p><strong>Ajoutée le :</strong> <?php echo date('d/m/Y à H:i', strtotime($image->dateAjout)); ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="image-not-found">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>Image non trouvée sur le serveur</p>
                                <small>L'image a peut-être été supprimée manuellement</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Aperçu de la nouvelle image -->
                <div class="form-group" id="newImagePreviewGroup" style="display: none;">
                    <label>Aperçu de la nouvelle image</label>
                    <div class="image-preview-container">
                        <div id="imagePreview" class="image-preview-placeholder">
                            <i class="fas fa-image"></i>
                            <p>Aperçu de la nouvelle image</p>
                        </div>
                    </div>
                </div>

                <!-- Actions du formulaire -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="index.php?controleur=Galerie&action=afficherGestionGalerie" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions supplémentaires -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Actions supplémentaires</h3>
        </div>
        <div class="card-body">
            <div class="actions-grid">
                <div class="action-item">
                    <h4><i class="fas fa-eye"></i> Prévisualiser</h4>
                    <p>Voir comment cette image apparaît dans la galerie publique</p>
                    <a href="index.php?controleur=Galerie&action=afficherGalerie" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-external-link-alt"></i> Voir la galerie
                    </a>
                </div>
                
                <div class="action-item">
                    <h4><i class="fas fa-trash"></i> Supprimer</h4>
                    <p>Supprimer définitivement cette image de la galerie</p>
                    <button type="button" class="btn btn-danger btn-sm" onclick="supprimerImage(<?php echo $image->idImage; ?>, '<?php echo htmlspecialchars($image->titreImage); ?>')">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques au formulaire de modification */
.current-image-container {
    margin-top: 10px;
}

.current-image {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.current-image-preview {
    width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #dee2e6;
}

.image-info {
    flex: 1;
}

.image-info p {
    margin: 5px 0;
    font-size: 0.9em;
    color: #666;
}

.image-info p strong {
    color: #333;
}

.image-not-found {
    padding: 40px;
    text-align: center;
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    color: #856404;
}

.image-not-found i {
    font-size: 2em;
    margin-bottom: 15px;
}

.image-not-found p {
    margin: 10px 0 5px 0;
    font-weight: 600;
}

.image-not-found small {
    color: #856404;
}

.file-upload-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
}

.file-upload-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border: 2px dashed #ddd;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    min-height: 50px;
}

.file-upload-info:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.file-upload-info i {
    font-size: 1.5em;
    color: #6c757d;
}

.file-text {
    color: #6c757d;
    font-weight: 500;
}

.image-preview-container {
    margin-top: 10px;
}

.image-preview-placeholder {
    width: 200px;
    height: 150px;
    border: 2px dashed #ddd;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
    transition: all 0.3s ease;
}

.image-preview-placeholder i {
    font-size: 2em;
    margin-bottom: 10px;
}

.image-preview-placeholder p {
    margin: 0;
    font-size: 0.9em;
}

.image-preview-placeholder.has-image {
    border-style: solid;
    border-color: #28a745;
    background: #d4edda;
    color: #155724;
}

.image-preview-placeholder img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.action-item {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #6c757d;
    text-align: center;
}

.action-item h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.1em;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.action-item p {
    margin: 0 0 15px 0;
    color: #666;
    font-size: 0.9em;
    line-height: 1.4;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
    margin-top: 30px;
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .current-image {
        flex-direction: column;
        text-align: center;
    }
    
    .current-image-preview {
        width: 100%;
        max-width: 300px;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileText = document.querySelector('.file-text');
    const imagePreview = document.getElementById('imagePreview');
    const newImagePreviewGroup = document.getElementById('newImagePreviewGroup');
    
    // Gestion de l'upload de fichier
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Mettre à jour le texte
            fileText.textContent = file.name;
            
            // Vérifier le type de fichier
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner une image valide');
                fileInput.value = '';
                fileText.textContent = 'Choisir une nouvelle image';
                newImagePreviewGroup.style.display = 'none';
                return;
            }
            
            // Vérifier la taille (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('L\'image est trop volumineuse. Taille maximum : 5MB');
                fileInput.value = '';
                fileText.textContent = 'Choisir une nouvelle image';
                newImagePreviewGroup.style.display = 'none';
                return;
            }
            
            // Afficher l'aperçu
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Aperçu">`;
                imagePreview.classList.add('has-image');
                newImagePreviewGroup.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            fileText.textContent = 'Choisir une nouvelle image';
            newImagePreviewGroup.style.display = 'none';
        }
    });
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const nomImage = document.getElementById('nomImage').value.trim();
        const titreImage = document.getElementById('titreImage').value.trim();
        
        if (!nomImage) {
            e.preventDefault();
            alert('Veuillez saisir le nom de l\'image');
            document.getElementById('nomImage').focus();
            return;
        }
        
        if (!titreImage) {
            e.preventDefault();
            alert('Veuillez saisir le titre de l\'image');
            document.getElementById('titreImage').focus();
            return;
        }
        
        // Afficher un indicateur de chargement
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
        submitBtn.disabled = true;
        
        // Le formulaire sera soumis normalement
    });
});

// Fonction pour supprimer une image
function supprimerImage(idImage, titreImage) {
    if (confirm('Êtes-vous sûr de vouloir supprimer l\'image "' + titreImage + '" ?\n\nCette action est irréversible.')) {
        window.location.href = 'index.php?controleur=Galerie&action=supprimerImage&id=' + idImage;
    }
}
</script> 