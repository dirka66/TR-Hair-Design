<?php
// Sécurité
if (!defined('INDEX_PRINCIPAL')) {
    header('Location: ../index.php');
    exit;
}
?>

<div class="admin-page-header">
    <div>
        <h1><i class="fas fa-cut"></i> Gestion des Services</h1>
        <p>Gérez vos prestations et tarifs</p>
    </div>
    <div class="page-actions">
        <a href="index.php?controleur=ProduitsModerne&action=ajouterProduit" class="btn-primary">
            <i class="fas fa-plus"></i> Ajouter un service
        </a>
    </div>
</div>

<?php if (isset($messageSucces) && $messageSucces): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo htmlspecialchars($messageSucces); ?>
    </div>
<?php endif; ?>

<?php if (isset($messageErreur) && $messageErreur): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo htmlspecialchars($messageErreur); ?>
    </div>
<?php endif; ?>

<!-- Statistiques -->
<div class="stats-grid">
    <?php 
    // Utiliser les statistiques passées par le contrôleur
    if (!isset($statistiques)) {
        $statistiques = (object) [
            'totalProduits' => count($lesProduits ?? []),
            'produitsActifs' => 0,
            'produitsPopulaires' => 0,
            'prixMoyen' => 0
        ];
        
        if (!empty($lesProduits)) {
            $totalPrix = 0;
            foreach ($lesProduits as $produit) {
                if ($produit->actif == 1) $statistiques->produitsActifs++;
                if (isset($produit->populaire) && $produit->populaire == 1) $statistiques->produitsPopulaires++;
                $totalPrix += $produit->prix;
            }
            $statistiques->prixMoyen = $totalPrix / count($lesProduits);
        }
    }
    ?>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-list"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques->totalProduits ?? 0; ?></h3>
            <p>Services totaux</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques->produitsActifs ?? 0; ?></h3>
            <p>Services actifs</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $statistiques->produitsPopulaires ?? 0; ?></h3>
            <p>Services populaires</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-euro-sign"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($statistiques->prixMoyen ?? 0, 2); ?>€</h3>
            <p>Prix moyen</p>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="admin-form">
    <div class="filters-section">
        <div class="filter-group">
            <input type="text" id="searchProduits" placeholder="Rechercher un service..." class="form-control">
        </div>
        <div class="filter-group">
            <select id="filterFamille" class="form-control">
                <option value="">Toutes les catégories</option>
                <?php if (isset($familles) && !empty($familles)): ?>
                    <?php foreach ($familles as $famille): ?>
                        <option value="<?php echo $famille->idFamille; ?>">
                            <?php echo htmlspecialchars($famille->nomFamille); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="filter-group">
            <select id="filterStatut" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="1">Actifs</option>
                <option value="0">Inactifs</option>
            </select>
        </div>
    </div>
</div>

<!-- Liste des produits/services -->
<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-scissors"></i> Services et prestations</h3>
        <div class="table-actions">
            <button type="button" class="btn-action btn-edit" onclick="toggleView()">
                <i class="fas fa-th" id="viewIcon"></i> Changer vue
            </button>
        </div>
    </div>
    
    <div class="table-content">
        <table class="admin-table" id="tableServices">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Service</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Populaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Utiliser $lesProduits si $produits n'est pas défini
                $produits = $lesProduits ?? $produits ?? [];
                if (empty($produits)): 
                ?>
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Aucun service trouvé</p>
                            <a href="index.php?controleur=ProduitsModerne&action=ajouterProduit" class="btn-primary">
                                <i class="fas fa-plus"></i> Ajouter le premier service
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produits as $produit): ?>
                        <tr class="produit-row" 
                            data-famille="<?php echo $produit->idFamille; ?>" 
                            data-statut="<?php echo $produit->actif; ?>">
                            <td>
                                <span class="position-badge"><?php echo $produit->position; ?></span>
                            </td>
                            <td>
                                <div class="service-info">
                                    <h6><?php echo htmlspecialchars($produit->nomProduit); ?></h6>
                                    <?php if (isset($produit->description) && $produit->description): ?>
                                        <small>
                                            <?php echo htmlspecialchars(substr($produit->description, 0, 80)); ?>
                                            <?php if (strlen($produit->description) > 80): ?>...<?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge-famille" style="background-color: <?php echo $produit->famille_couleur ?? '#6c757d'; ?>">
                                    <i class="<?php echo $produit->famille_icone ?? 'fas fa-tag'; ?>"></i>
                                    <?php echo htmlspecialchars($produit->nomFamille ?? 'Non catégorisé'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="price-badge"><?php echo number_format($produit->prix, 2); ?>€</span>
                            </td>
                            <td>
                                <span class="duration-badge">
                                    <i class="fas fa-clock"></i> <?php echo $produit->duree ?? 0; ?>min
                                </span>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn-action <?php echo $produit->actif ? 'btn-confirm' : 'btn-delete'; ?>"
                                        onclick="toggleStatut(<?php echo $produit->idProduit; ?>)"
                                        data-id="<?php echo $produit->idProduit; ?>">
                                    <i class="fas <?php echo $produit->actif ? 'fa-check' : 'fa-times'; ?>"></i>
                                    <?php echo $produit->actif ? 'Actif' : 'Inactif'; ?>
                                </button>
                            </td>
                            <td>
                                <?php if (isset($produit->populaire) && $produit->populaire): ?>
                                    <span class="badge-populaire">
                                        <i class="fas fa-star"></i> Populaire
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="index.php?controleur=ProduitsModerne&action=modifierProduit&id=<?php echo $produit->idProduit; ?>" 
                                       class="btn-action btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn-action btn-delete" 
                                            onclick="supprimerProduit(<?php echo $produit->idProduit; ?>, '<?php echo htmlspecialchars($produit->nomProduit, ENT_QUOTES); ?>')"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Recherche en temps réel
document.getElementById('searchProduits').addEventListener('input', function() {
    filtrerTableau();
});

document.getElementById('filterFamille').addEventListener('change', function() {
    filtrerTableau();
});

document.getElementById('filterStatut').addEventListener('change', function() {
    filtrerTableau();
});

function filtrerTableau() {
    const searchTerm = document.getElementById('searchProduits').value.toLowerCase();
    const familleFilter = document.getElementById('filterFamille').value;
    const statutFilter = document.getElementById('filterStatut').value;
    const rows = document.querySelectorAll('.produit-row');
    
    rows.forEach(row => {
        const nomProduit = row.querySelector('.service-info h6').textContent.toLowerCase();
        const description = row.querySelector('.service-info small')?.textContent.toLowerCase() || '';
        const famille = row.dataset.famille;
        const statut = row.dataset.statut;
        
        let visible = true;
        
        // Filtre par recherche textuelle
        if (searchTerm && !nomProduit.includes(searchTerm) && !description.includes(searchTerm)) {
            visible = false;
        }
        
        // Filtre par famille
        if (familleFilter && famille !== familleFilter) {
            visible = false;
        }
        
        // Filtre par statut
        if (statutFilter !== '' && statut !== statutFilter) {
            visible = false;
        }
        
        row.style.display = visible ? '' : 'none';
    });
}

// Toggle du statut actif/inactif
function toggleStatut(idProduit) {
    if (confirm('Voulez-vous modifier le statut de ce service ?')) {
        const formData = new FormData();
        formData.append('id', idProduit);
        
        fetch('index.php?controleur=ProduitsModerne&action=toggleActifProduit', {
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
            alert('Erreur lors de la modification du statut');
        });
    }
}

// Suppression de produit
function supprimerProduit(idProduit, nomProduit) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le service "${nomProduit}" ?\n\nCette action est irréversible.`)) {
        const formData = new FormData();
        formData.append('id', idProduit);
        
        fetch('index.php?controleur=ProduitsModerne&action=supprimerProduit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                location.reload();
            } else {
                alert('Erreur : ' + (data.message || 'Erreur lors de la suppression'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Changement de vue (table/grid)
let vueGrid = false;
function toggleView() {
    console.log('Changement de vue à implémenter');
}
</script>


