<?php
// Vérification si des images sont disponibles
$hasImages = !empty($lesImages);
?>

<div class="galerie-container">
    <!-- En-tête de la galerie -->
    <div class="galerie-header">
        <div class="header-content">
            <h1><i class="fas fa-images"></i> Notre Galerie</h1>
            <p>Découvrez nos plus belles réalisations et laissez-vous inspirer</p>
        </div>
    </div>

    <?php if ($hasImages): ?>
        <!-- Filtres et options -->
        <div class="galerie-controls">
            <div class="controls-left">
                <span class="images-count">
                    <i class="fas fa-image"></i>
                    <?php echo count($lesImages); ?> réalisation<?php echo count($lesImages) > 1 ? 's' : ''; ?>
                </span>
            </div>
            <div class="controls-right">
                <button type="button" class="btn btn-outline" onclick="toggleViewMode()">
                    <i class="fas fa-th" id="viewIcon"></i>
                    <span id="viewText">Vue grille</span>
                </button>
            </div>
        </div>

        <!-- Grille des images -->
        <div class="galerie-grid" id="galerieGrid">
            <?php foreach ($lesImages as $image): ?>
                <div class="galerie-item" data-id="<?php echo $image->idImage; ?>">
                    <div class="image-container">
                        <?php if (file_exists($image->cheminImage)): ?>
                            <img src="<?php echo htmlspecialchars($image->cheminImage); ?>" 
                                 alt="<?php echo htmlspecialchars($image->titreImage); ?>"
                                 class="galerie-image"
                                 loading="lazy"
                                 onclick="openImageModal(<?php echo $image->idImage; ?>)">
                        <?php else: ?>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>Image non disponible</p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Overlay avec informations -->
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <h3><?php echo htmlspecialchars($image->titreImage); ?></h3>
                                <?php if (!empty($image->description)): ?>
                                    <p><?php echo htmlspecialchars(substr($image->description, 0, 100)); ?><?php echo strlen($image->description) > 100 ? '...' : ''; ?></p>
                                <?php endif; ?>
                                <button type="button" class="btn btn-light btn-sm" onclick="openImageModal(<?php echo $image->idImage; ?>)">
                                    <i class="fas fa-search-plus"></i> Voir plus
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations de l'image -->
                    <div class="image-info">
                        <h4><?php echo htmlspecialchars($image->titreImage); ?></h4>
                        <?php if (!empty($image->description)): ?>
                            <p class="image-description">
                                <?php echo htmlspecialchars(substr($image->description, 0, 80)); ?><?php echo strlen($image->description) > 80 ? '...' : ''; ?>
                            </p>
                        <?php endif; ?>
                        <div class="image-meta">
                            <small>
                                <i class="fas fa-calendar"></i>
                                <?php echo date('d/m/Y', strtotime($image->dateAjout)); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal pour afficher l'image en grand -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitle"></h3>
                    <button type="button" class="modal-close" onclick="closeImageModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-image-container">
                        <img id="modalImage" src="" alt="">
                    </div>
                    <div class="modal-info">
                        <p id="modalDescription"></p>
                        <div class="modal-meta">
                            <small id="modalDate"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="prendreRendezVous()">
                        <i class="fas fa-calendar-plus"></i> Prendre rendez-vous
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeImageModal()">
                        <i class="fas fa-times"></i> Fermer
                    </button>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- État vide -->
        <div class="empty-galerie">
            <div class="empty-content">
                <i class="fas fa-images"></i>
                <h3>Galerie en cours de préparation</h3>
                <p>Nos plus belles réalisations seront bientôt disponibles ici.</p>
                <p>En attendant, n'hésitez pas à nous contacter pour découvrir nos services !</p>
                <div class="empty-actions">
                    <a href="#contact" class="btn btn-primary">
                        <i class="fas fa-phone"></i> Nous contacter
                    </a>
                    <a href="#services" class="btn btn-outline">
                        <i class="fas fa-list"></i> Voir nos services
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Styles pour la galerie */
.galerie-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.galerie-header {
    text-align: center;
    margin-bottom: 40px;
}

.galerie-header h1 {
    font-size: 2.5em;
    color: var(--primary-color);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.galerie-header p {
    font-size: 1.1em;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

.galerie-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.images-count {
    font-weight: 600;
    color: #666;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
}

/* Grille des images */
.galerie-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.galerie-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.galerie-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.galerie-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.galerie-item:hover .galerie-image {
    transform: scale(1.05);
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
}

.image-placeholder i {
    font-size: 3em;
    margin-bottom: 15px;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.galerie-item:hover .image-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    color: white;
    padding: 20px;
}

.overlay-content h3 {
    margin: 0 0 10px 0;
    font-size: 1.2em;
}

.overlay-content p {
    margin: 0 0 15px 0;
    font-size: 0.9em;
    opacity: 0.9;
}

.image-info {
    padding: 20px;
}

.image-info h4 {
    margin: 0 0 10px 0;
    color: var(--primary-color);
    font-size: 1.1em;
}

.image-description {
    color: #666;
    font-size: 0.9em;
    line-height: 1.4;
    margin-bottom: 10px;
}

.image-meta {
    color: #999;
    font-size: 0.8em;
}

.image-meta i {
    margin-right: 5px;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
}

.modal-content {
    position: relative;
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.modal-header h3 {
    margin: 0;
    color: var(--primary-color);
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    color: #666;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f8f9fa;
    color: #333;
}

.modal-body {
    padding: 20px;
}

.modal-image-container {
    text-align: center;
    margin-bottom: 20px;
}

.modal-image-container img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 8px;
}

.modal-info p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.modal-meta {
    color: #999;
    font-size: 0.9em;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* État vide */
.empty-galerie {
    text-align: center;
    padding: 80px 20px;
}

.empty-content i {
    font-size: 4em;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-content h3 {
    color: #666;
    margin-bottom: 15px;
}

.empty-content p {
    color: #999;
    margin-bottom: 10px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.empty-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
    .galerie-container {
        padding: 20px 15px;
    }
    
    .galerie-header h1 {
        font-size: 2em;
    }
    
    .galerie-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .galerie-controls {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .empty-actions {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .galerie-grid {
        grid-template-columns: 1fr;
    }
    
    .image-container {
        height: 200px;
    }
}
</style>

<script>
// Données des images pour le modal
const imagesData = <?php echo json_encode($lesImages); ?>;

// Fonction pour ouvrir le modal d'image
function openImageModal(imageId) {
    const image = imagesData.find(img => img.idImage == imageId);
    if (!image) return;
    
    const modal = document.getElementById('imageModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalDescription = document.getElementById('modalDescription');
    const modalDate = document.getElementById('modalDate');
    
    modalTitle.textContent = image.titreImage;
    modalImage.src = image.cheminImage;
    modalImage.alt = image.titreImage;
    modalDescription.textContent = image.description || 'Aucune description disponible.';
    modalDate.innerHTML = `<i class="fas fa-calendar"></i> Ajoutée le ${new Date(image.dateAjout).toLocaleDateString('fr-FR')}`;
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fonction pour fermer le modal
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fermer le modal en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target == modal) {
        closeImageModal();
    }
}

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});

// Fonction pour basculer le mode d'affichage
let isGridView = true;

function toggleViewMode() {
    const grid = document.getElementById('galerieGrid');
    const viewIcon = document.getElementById('viewIcon');
    const viewText = document.getElementById('viewText');
    
    isGridView = !isGridView;
    
    if (isGridView) {
        grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
        viewIcon.className = 'fas fa-th';
        viewText.textContent = 'Vue grille';
    } else {
        grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(400px, 1fr))';
        viewIcon.className = 'fas fa-list';
        viewText.textContent = 'Vue liste';
    }
}

// Fonction pour prendre rendez-vous
function prendreRendezVous() {
    closeImageModal();
    // Rediriger vers la section contact ou le formulaire de RDV
    const contactSection = document.getElementById('contact');
    if (contactSection) {
        contactSection.scrollIntoView({ behavior: 'smooth' });
    } else {
        window.location.href = 'index.php#contact';
    }
}

// Animation au scroll pour les images
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observer tous les éléments de la galerie
    document.querySelectorAll('.galerie-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
});
</script> 