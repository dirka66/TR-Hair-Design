<div class="container">
    <!-- Section Hero -->
    <section class="hero" id="accueil">
        <div class="hero-content">
            <h2>Bienvenue chez Tahir Hair DESIGN</h2>
            <p>Votre salon de coiffure moderne spécialisé dans les coupes pour hommes, les styles tendance et les soins capillaires de haute qualité.</p>
            <a href="#services" class="btn-main">
                <i class="fas fa-cut"></i> Découvrez nos services
            </a>
        </div>
    </section>

    <!-- Section À propos -->
    <section class="section" id="a-propos">
        <h2 class="section-title">À propos de nous</h2>
        <div class="about-content">
            <div class="about-text">
                <p>Tahir Hair DESIGN est un salon de coiffure moderne spécialisé dans les coupes pour hommes, les styles tendance, et les soins capillaires de haute qualité. Avec plusieurs années d'expérience, nous nous engageons à offrir des services adaptés à vos besoins et à vous faire vivre une expérience exceptionnelle.</p>
                
                <p>Notre histoire : Tahir Hair DESIGN a été créé par Tahir, un coiffeur passionné et talentueux qui a développé son savoir-faire à travers des années de formation et d'expérience. Notre salon, situé au cœur de la ville, est un espace dédié à la beauté et au style masculin.</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-award"></i>
                        <h4>Expertise</h4>
                        <p>Années d'expérience dans la coiffure masculine</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <h4>Passion</h4>
                        <p>Dédié à votre satisfaction et votre style</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-star"></i>
                        <h4>Qualité</h4>
                        <p>Produits et services de haute qualité</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Informations -->
    <section class="section" id="infos">
        <h2 class="section-title">Informations pratiques</h2>
        <div class="cards">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Nos Horaires</h3>
                <?php
                $controleurHoraires = new ControleurHoraires();
                $controleurHoraires->afficher();
                ?>
            </div>
            
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <h3>Nos Tarifs</h3>
                <?php
                $controleurProduits = new ControleurProduits();
                $controleurProduits->afficher();
                ?>
            </div>
            
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3>Dernières Informations</h3>
                <?php
                $controleurInformations = new ControleurInformations();
                $controleurInformations->afficher();
                ?>
            </div>
        </div>
    </section>

    <!-- Section Services -->
    <section class="section" id="services">
        <h2 class="section-title">Nos Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-cut"></i>
                </div>
                <h3>Coupes pour hommes</h3>
                <p>Coupes classiques et modernes adaptées à votre style et à la forme de votre visage.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <h3>Styles personnalisés</h3>
                <p>Création de styles uniques selon vos préférences et les dernières tendances.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Soins capillaires</h3>
                <p>Traitements et soins pour maintenir la santé et la beauté de vos cheveux.</p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Styling professionnel</h3>
                <p>Coiffage pour événements spéciaux et occasions professionnelles.</p>
            </div>
        </div>
    </section>

    <!-- Section Galerie -->
    <section class="section" id="galerie">
        <h2 class="section-title">Notre Galerie</h2>
        <?php
        // Utilisation des images de la galerie chargées dans index.php
        // $lesImagesGalerie est déjà disponible depuis le chargement dans index.php
        ?>
        
        <?php if (!empty($lesImagesGalerie)): ?>
            <div class="gallery-grid">
                <?php foreach ($lesImagesGalerie as $index => $image): ?>
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <?php if (file_exists($image->cheminImage)): ?>
                            <img src="<?php echo htmlspecialchars($image->cheminImage); ?>" 
                                 alt="<?php echo htmlspecialchars($image->titreImage); ?>" 
                                 loading="lazy">
                        <?php else: ?>
                            <img src="<?php echo Chemins::IMAGES; ?>image-placeholder.jpg" 
                                 alt="Image non disponible" 
                                 loading="lazy">
                        <?php endif; ?>
                        
                        <div class="gallery-overlay">
                            <h4><?php echo htmlspecialchars($image->titreImage); ?></h4>
                            <?php if (!empty($image->description)): ?>
                                <p><?php echo htmlspecialchars(substr($image->description, 0, 80)); ?><?php echo strlen($image->description) > 80 ? '...' : ''; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Galerie par défaut si aucune image en base -->
            <div class="gallery-grid">
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                    <img src="<?php echo Chemins::IMAGES; ?>image1.jpg" alt="Coupe tendance homme" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Coupe Moderne</h4>
                        <p>Style tendance et élégant</p>
                    </div>
                </div>
                
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                    <img src="<?php echo Chemins::IMAGES; ?>hamza.jpg" alt="Salon de coiffure moderne" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Notre Salon</h4>
                        <p>Espace moderne et accueillant</p>
                    </div>
                </div>
                
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                    <img src="<?php echo Chemins::IMAGES; ?>background.jpeg" alt="Soins capillaires professionnels" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Soins Premium</h4>
                        <p>Traitements de qualité</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Widget Elfsight -->
        <div class="social-widget">
            <script src="https://static.elfsight.com/platform/platform.js" async></script>
            <div class="elfsight-app-ab5b99f3-8413-4d79-808c-d4afbce21822" data-elfsight-app-lazy></div>
        </div>
    </section>

    <!-- Section Contact -->
    <section class="section" id="contact">
        <h2 class="section-title">Contactez-nous</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Informations de contact</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Adresse</h4>
                        <p>123 Rue de la Coiffure<br>75001 Paris, France</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Téléphone</h4>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>contact@tahirhairdesign.fr</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Horaires</h4>
                        <p>Lun-Ven: 9h-19h<br>Sam: 9h-18h<br>Dim: Fermé</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h3>Envoyez-nous un message</h3>
                <form class="contact-form" id="contactForm" autocomplete="off">
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" required placeholder="Votre nom complet">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email *</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" required placeholder="votre.email@exemple.com">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <div class="input-wrapper">
                            <input type="tel" id="phone" name="phone" placeholder="+33 1 23 45 67 89">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <div class="input-wrapper">
                            <textarea id="message" name="message" required placeholder="Décrivez votre demande..." rows="5"></textarea>
                            <i class="fas fa-comment-dots"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                    
                    <div id="contact-success" class="success-message">
                        <i class="fas fa-check-circle"></i>
                        Merci pour votre message ! Nous vous répondrons rapidement.
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
// Gestion du formulaire de contact
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Animation de soumission
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
    submitBtn.disabled = true;
    
    // Simulation d'envoi (remplacer par votre logique d'envoi)
    setTimeout(() => {
        document.getElementById('contact-success').style.display = 'block';
        this.reset();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Masquer le message après 5 secondes
        setTimeout(() => {
            document.getElementById('contact-success').style.display = 'none';
        }, 5000);
    }, 2000);
});

// Animation des cartes au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationDelay = `${Math.random() * 0.5}s`;
            entry.target.classList.add('animate-fade-in');
        }
    });
}, observerOptions);

// Observer tous les éléments animables
document.querySelectorAll('.card, .service-card, .gallery-item, .feature').forEach(el => {
    observer.observe(el);
});
</script>
