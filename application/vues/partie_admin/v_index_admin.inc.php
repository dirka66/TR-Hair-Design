<section class="form-container" style="padding-bottom: 851px;">
    <div class="titre">
        Administration du site (Accès réservé)<br>
        - Bonjour <?php echo $_SESSION['login_admin']; ?> -
    </div>
    
 <!-- Boutons pour les actions -->
    <a href="index.php?controleur=Admin&action=modifierLesHoraires" class="btn">Modifier les horaires</a><br>
    <a href="index.php?controleur=Famille&action=gererFamilles" class="btn">Gestion des familles</a><br>
    <a href="index.php?controleur=Produit&action=gererProduits" class="btn">Gestion des produits</a><br>
    <a href="index.php?controleur=Image&action=gererImages" class="btn">Gestion des images</a><br>
    <a href="index.php?controleur=Info&action=gererInformations" class="btn">Gestion des informations</a><br>
    
    
    <!-- Déconnexion -->
    <p>
        <a href="index.php?controleur=Admin&action=seDeconnecter" class="btn-logout">Déconnexion (<?php echo $_SESSION['login_admin']; ?>)</a>
    </p>
</section>
