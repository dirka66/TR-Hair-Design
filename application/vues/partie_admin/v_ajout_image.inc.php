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
            <h1><i class="fas fa-plus"></i> Ajouter une Image</h1>
            <p>Ajoutez une nouvelle image à votre galerie pour présenter vos réalisations</p>
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

    <!-- Formulaire d'ajout -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-upload"></i> Informations de l'image</h3>
            <p class="text-muted">Remplissez les informations de votre image</p>
        </div>
        
        <div class="card-body">
            <form method="post" action="index.php?controleur=Galerie&action=ajouterImage" enctype="multipart/form-data" class="admin-form">
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nomImage">Nom de l'image *</label>
                        <input type="text" 
                               id="nomImage" 
                               name="nomImage" 
                               class="form-control" 
                               required 
                               placeholder="Ex: Coupe moderne femme"
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
                               placeholder="Ex: Coupe moderne pour femme"
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
                              maxlength="500"></textarea>
                    <small class="form-help">Description détaillée de l'image (optionnel)</small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Fichier image *</label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="form-control file-input" 
                                   accept="image/*" 
                                   required>
                            <div class="file-upload-info">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span class="file-text">Choisir une image</span>
                            </div>
                        </div>
                        <small class="form-help">
                            Formats acceptés : JPG, PNG, GIF, WEBP<br>
                            Taille maximum : 5MB<br>
                            Dimensions recommandées : 800x600px minimum
                        </small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="ordre">Ordre d'affichage</label>
                        <input type="number" 
                               id="ordre" 
                               name="ordre" 
                               class="form-control" 
                               value="0" 
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
                               checked>
                        <label class="form-check-label" for="actif">
                            Activer cette image
                        </label>
                        <small class="form-help">Décochez pour masquer temporairement l'image</small>
                    </div>
                </div>

                <!-- Aperçu de l'image -->
                <div class="form-group">
                    <label>Aperçu de l'image</label>
                    <div class="image-preview-container">
                        <div id="imagePreview" class="image-preview-placeholder">
                            <i class="fas fa-image"></i>
                            <p>Aperçu de l'image</p>
                        </div>
                    </div>
                </div>

                <!-- Actions du formulaire -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Ajouter l'image
                    </button>
                    <a href="index.php?controleur=Galerie&action=afficherGestionGalerie" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Conseils -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-lightbulb"></i> Conseils pour une bonne galerie</h3>
        </div>
        <div class="card-body">
            <div class="tips-grid">
                <div class="tip-item">
                    <i class="fas fa-camera"></i>
                    <h4>Qualité des images</h4>
                    <p>Utilisez des images de bonne qualité (800x600px minimum) pour un rendu optimal</p>
                </div>
                <div class="tip-item">
                    <i class="fas fa-tags"></i>
                    <h4>Descriptions détaillées</h4>
                    <p>Ajoutez des descriptions pour expliquer les techniques et styles utilisés</p>
                </div>
                <div class="tip-item">
                    <i class="fas fa-sort"></i>
                    <h4>Ordre logique</h4>
                    <p>Organisez vos images par ordre d'importance ou par style de coiffure</p>
                </div>
                <div class="tip-item">
                    <i class="fas fa-eye"></i>
                    <h4>Visibilité</h4>
                    <p>Activez seulement les images que vous souhaitez montrer aux visiteurs</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques au formulaire d'ajout d'image */
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

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.tip-item {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    text-align: center;
}

.tip-item i {
    font-size: 2em;
    color: #007bff;
    margin-bottom: 15px;
}

.tip-item h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.1em;
}

.tip-item p {
    margin: 0;
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
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileText = document.querySelector('.file-text');
    const imagePreview = document.getElementById('imagePreview');
    
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
                fileText.textContent = 'Choisir une image';
                return;
            }
            
            // Vérifier la taille (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('L\'image est trop volumineuse. Taille maximum : 5MB');
                fileInput.value = '';
                fileText.textContent = 'Choisir une image';
                return;
            }
            
            // Afficher l'aperçu
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Aperçu">`;
                imagePreview.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        } else {
            fileText.textContent = 'Choisir une image';
            imagePreview.innerHTML = `
                <i class="fas fa-image"></i>
                <p>Aperçu de l'image</p>
            `;
            imagePreview.classList.remove('has-image');
        }
    });
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const nomImage = document.getElementById('nomImage').value.trim();
        const titreImage = document.getElementById('titreImage').value.trim();
        const file = fileInput.files[0];
        
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
        
        if (!file) {
            e.preventDefault();
            alert('Veuillez sélectionner une image');
            fileInput.focus();
            return;
        }
        
        // Afficher un indicateur de chargement
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout en cours...';
        submitBtn.disabled = true;
        
        // Le formulaire sera soumis normalement
    });
    
    // Génération automatique du nom de fichier
    document.getElementById('titreImage').addEventListener('input', function(e) {
        const titre = e.target.value;
        const nomImage = titre
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '_')
            .substring(0, 50);
        
        document.getElementById('nomImage').value = nomImage;
    });
});
</script> 