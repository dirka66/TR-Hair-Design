<?php
// Sécurité
if (!defined('INDEX_PRINCIPAL')) {
    header('Location: ../index.php');
    exit;
}
?>

<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-newspaper"></i> Gestion des Actualités</h1>
        <p>Publiez des informations importantes pour vos clients</p>
    </div>
    <div class="page-actions">
        <button class="btn-primary" onclick="document.getElementById('addForm').style.display='block'">
            <i class="fas fa-plus"></i> Ajouter une actualité
        </button>
    </div>
</div>

<?php if (!empty($messageSucces)) : ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo htmlspecialchars($messageSucces); ?>
    </div>
<?php endif; ?>

<?php if (!empty($messageErreur)) : ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo htmlspecialchars($messageErreur); ?>
    </div>
<?php endif; ?>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques->totalInformations ?? 0; ?></h3>
            <p>Total actualités</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques->informationsImportantes ?? 0; ?></h3>
            <p>Informations importantes</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques->informationsRecentes ?? 0; ?></h3>
            <p>Récentes (7 jours)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo count($lesInformations ?? []); ?></h3>
            <p>Affichées</p>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="admin-form">
    <div class="filters-section">
        <div class="filter-group">
            <input type="text" id="searchInformations" placeholder="Rechercher une actualité..." class="form-control">
        </div>
        <div class="filter-group">
            <select id="filterType" class="form-control">
                <option value="">Tous les types</option>
                <option value="actualite">Actualités</option>
                <option value="annonce">Annonces</option>
                <option value="promotion">Promotions</option>
                <option value="information">Informations générales</option>
            </select>
        </div>
        <div class="filter-group">
            <select id="filterStatut" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="1">Actives</option>
                <option value="0">Inactives</option>
            </select>
        </div>
    </div>
</div>

<!-- Liste des informations -->
<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-newspaper"></i> Liste des actualités</h3>
    </div>
    <div class="table-content">
        <?php if (empty($lesInformations)): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <p>Aucune actualité trouvée</p>
                <button class="btn-primary" onclick="document.getElementById('addForm').style.display='block'">
                    <i class="fas fa-plus"></i> Créer la première actualité
                </button>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Contenu</th>
                        <th>Important</th>
                        <th>Date de publication</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lesInformations as $info): ?>
                        <tr>
                            <td>
                                <span class="position-badge"><?php echo $info->idInfo; ?></span>
                            </td>
                            <td>
                                <div class="service-info">
                                    <h6><?php echo htmlspecialchars($info->titreInformation); ?></h6>
                                    <?php if (isset($info->libelleInformation) && $info->libelleInformation): ?>
                                        <small>
                                            <?php echo htmlspecialchars(substr($info->libelleInformation, 0, 80)); ?>
                                            <?php if (strlen($info->libelleInformation) > 80): ?>...<?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?php echo htmlspecialchars(substr($info->libelleInformation, 0, 100)); ?>
                                    <?php if (strlen($info->libelleInformation) > 100): ?>...<?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($info->important): ?>
                                    <span class="badge-populaire">
                                        <i class="fas fa-star"></i> Important
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="duration-badge">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('d/m/Y H:i', strtotime($info->dateCreation)); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" 
                                            class="btn-action btn-edit"
                                            onclick="modifierInformation(<?php echo $info->idInfo; ?>, '<?php echo htmlspecialchars($info->titreInformation, ENT_QUOTES); ?>')"
                                            title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn-action btn-delete" 
                                            onclick="supprimerInformation(<?php echo $info->idInfo; ?>, '<?php echo htmlspecialchars($info->titreInformation, ENT_QUOTES); ?>')"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Formulaire d'ajout (caché par défaut) -->
<div id="addForm" class="admin-form" style="display: none;">
    <div class="table-header">
        <h3><i class="fas fa-plus"></i> Ajouter une nouvelle actualité</h3>
    </div>
    <div class="form-group">
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            <div style="flex: 1;">
                <input type="text" id="newInfoTitre" placeholder="Titre de l'actualité" required class="form-control">
            </div>
            <div style="flex: 2;">
                <textarea id="newInfoContenu" placeholder="Contenu de l'actualité" required class="form-control" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <label style="margin: 0;">
                    <input type="checkbox" id="newInfoImportant"> Important
                </label>
                <button type="button" class="btn-primary" onclick="ajouterInformation()">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
                <button type="button" class="btn-action" onclick="document.getElementById('addForm').style.display='none'">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Recherche en temps réel
document.getElementById('searchInformations').addEventListener('input', function() {
    filtrerTableau();
});

document.getElementById('filterType').addEventListener('change', function() {
    filtrerTableau();
});

document.getElementById('filterStatut').addEventListener('change', function() {
    filtrerTableau();
});

function filtrerTableau() {
    const searchTerm = document.getElementById('searchInformations').value.toLowerCase();
    const typeFilter = document.getElementById('filterType').value;
    const statutFilter = document.getElementById('filterStatut').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const titre = row.querySelector('.service-info h6').textContent.toLowerCase();
        const contenu = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        let visible = true;
        
        // Filtre par recherche textuelle
        if (searchTerm && !titre.includes(searchTerm) && !contenu.includes(searchTerm)) {
            visible = false;
        }
        
        row.style.display = visible ? '' : 'none';
    });
}

// Ajout d'information
function ajouterInformation() {
    const titre = document.getElementById('newInfoTitre').value.trim();
    const contenu = document.getElementById('newInfoContenu').value.trim();
    const important = document.getElementById('newInfoImportant').checked ? 1 : 0;
    
    if (!titre || !contenu) {
        alert('Veuillez remplir le titre et le contenu');
        return;
    }

    const formData = new FormData();
    formData.append('titre', titre);
    formData.append('contenu', contenu);
    formData.append('important', important);
    
    fetch('index.php?controleur=Informations&action=ajouterInformation', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.succes) {
            location.reload();
        } else {
            alert('Erreur : ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout de l\'actualité');
    });
}

// Suppression d'information
function supprimerInformation(idInformation, titre) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'actualité "${titre}" ?\n\nCette action est irréversible.`)) {
        const formData = new FormData();
        formData.append('idInformation', idInformation);
        
        fetch('index.php?controleur=Informations&action=supprimerInformation', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                location.reload();
            } else {
                alert('Erreur : ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Modification d'information
function modifierInformation(idInformation, titre) {
    const nouveauTitre = prompt('Modifier le titre:', titre);
    if (nouveauTitre && nouveauTitre.trim() !== '') {
        const nouveauContenu = prompt('Modifier le contenu:', '');
        if (nouveauContenu !== null) {
            const formData = new FormData();
            formData.append('idInformation', idInformation);
            formData.append('titre', nouveauTitre.trim());
            formData.append('contenu', nouveauContenu.trim());
            formData.append('important', '0');
            
            fetch('index.php?controleur=Informations&action=modifierInformation', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.succes) {
                    location.reload();
                } else {
                    alert('Erreur : ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la modification');
            });
        }
    }
}
</script>
