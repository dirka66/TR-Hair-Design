<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<section id="accueil">
    <div class="container">
        <h2>Bienvenue chez Tahir Hair DESIGN</h2>
        <p>Coiffeur Homme.</p> <br>
        <a href="#services" class="btn">Découvrez nos services</a>
    </div>
</section>

<section id="a-propos">
    <div class="container">
        <h2>À propos :</h2>
        <p>Tahir Hair DESIGN est un salon de coiffure moderne spécialisé dans les coupes pour hommes, les styles tendance, et les soins capillaires de haute qualité. Avec plusieurs années d'expérience, nous nous engageons à offrir des services adaptés à vos besoins et à vous faire vivre une expérience exceptionnelle.</p>
        <p>Notre histoire : Tahir Hair DESIGN a été créé par Tahir, un coiffeur passionné et talentueux qui a développé son savoir-faire à travers des années de formation et d’expérience. Notre salon, situé au cœur de la ville, est un espace dédié à la beauté et au style masculin.</p>
    </div>
</section>

<section id="infos">
    <div class="container">
        <h2>Informations :</h2>
        <div class="info-sections">
            <div class="info-section">
                <h3>Horaires</h3>
                <?php
                $controleurHoraires = new ControleurHoraires();
                $controleurHoraires->afficher();
                ?>
            </div>
            <div class="info-section">
                <h3>Tarifs</h3>
                <?php
                $controleurProduits = new ControleurProduits();
                $controleurProduits->afficher();
                ?>
            </div>
            <div class="info-section">
                <h3>Dernières Informations</h3>
                <?php
                $controleurInformations = new ControleurInformations();
                $controleurInformations->afficher();
                ?>
            </div>
        </div>
    </div>
</section>

<section id="services">
    <div class="container">
        <h2>Nos Services :</h2>
        <ul>
            <li>Coupes pour hommes</li>
            <li>Styles personnalisés</li>
            <li>Soin et entretien capillaires</li>
        </ul>
    </div>
</section>

<section id="galerie">
    <div class="container">
        <h2>Galerie :</h2>
        <div class="gallery">
            <img src="image1.jpg" alt="Coupe tendance">
            <img src="image2.jpg" alt="Salon de coiffure">
            <img src="image3.jpg" alt="Soins capillaires">
        </div>
    </div>
</section>



        <script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-ab5b99f3-8413-4d79-808c-d4afbce21822" data-elfsight-app-lazy></div>
    </div>


<section id="contact">
    <div class="container">
        <h2>Contactez-nous :</h2>
        <form action="#">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message :</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Envoyer</button>
        </form>
    </div>
</section>
<button id="scrollToTopBtn" onclick="scrollToTop()"><i class="fas fa-arrow-up"></i></button>

<script>
<?php
require_once Chemins::JS . 'btnRemonter.js';
?>
</script>
