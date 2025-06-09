<?php if (isset($_GET['controleur']) && $_GET['controleur'] === 'Familles') : ?>
<section class="form-container">
    <div class="titre">
        Gestion des Familles
    </div>

    <!-- Formulaire d'ajout d'une nouvelle famille -->
    <form action="index.php?controleur=Familles&action=ajouter" method="post" class="form-container">
        <label for="nomFamille">Nom de la famille :</label>
        <input type="text" name="nomFamille" required>
        <button type="submit" class="btn-submit">Ajouter</button>
    </form>

    <hr>

    <!-- Liste des familles existantes avec options de modification et suppression -->
    <table class="table-style">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3">Aucune famille trouvée.</td>
            </tr>
        </tbody>
    </table>

    <hr>

    <!-- Bouton retour vers la page admin -->
    <a href="index.php?controleur=Admin&action=afficherIndex" class="btn-submit">Retour au menu Admin</a>
</section>
<?php endif; ?>


<style>
    .form-container { max-width: 600px; margin: auto; }
    .table-style { width: 100%; border-collapse: collapse; }
    .table-style th, .table-style td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    .inline-form { display: inline; }
    .btn-edit { background-color: #4CAF50; color: white; padding: 5px; }
    .btn-delete { background-color: #f44336; color: white; padding: 5px; }
</style>
