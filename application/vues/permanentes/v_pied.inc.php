        <!-- Footer moderne -->
        <footer>
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>Tahir Hair DESIGN</h3>
                        <p>Votre salon de coiffure moderne spécialisé dans les coupes pour hommes et les styles tendance.</p>
                        <div class="social-links">
                            <a href="https://www.facebook.com/share/14ExRSfzgpc/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/kd_r66?igsh=MW9od2RnZnd5dWJwOA%3D%3D&utm_source=qr" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.linkedin.com/in/kadir-cetintas-b6652b204/" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://dirka66.github.io/Portfolio_2025/#accueil" target="_blank" aria-label="Portfolio"><i class="fas fa-user-tie"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Contact</h4>
                        <p><i class="fas fa-map-marker-alt"></i> 43 Av. Georges Clemenceau, 34500 Béziers</p>
                        <p><i class="fas fa-phone"></i> 07 82 91 61 64</p>
                        <p><i class="fas fa-envelope"></i> kadircetintas023@gmail.com</p>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Horaires</h4>
                        <?php
                        // Affichage détaillé de chaque jour
                        if (!empty($lesHoraires)) {
                            $jours = [
                                1 => 'Lundi',
                                2 => 'Mardi', 
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                7 => 'Dimanche'
                            ];
                            
                            foreach ($lesHoraires as $horaire) {
                                $jour = $jours[$horaire->idHoraire] ?? 'Jour ' . $horaire->idHoraire;
                                echo '<p>';
                                echo '<strong>' . $jour . ':</strong> ';
                                
                                if (isset($horaire->ferme) && $horaire->ferme) {
                                    echo 'Fermé';
                                } else {
                                    $horairesAffichage = [];
                                    
                                    if (!empty($horaire->heureOuvertureMatin) && !empty($horaire->heureFermetureMatin)) {
                                        $horairesAffichage[] = $horaire->heureOuvertureMatin . ' - ' . $horaire->heureFermetureMatin;
                                    }
                                    
                                    if (!empty($horaire->heureOuvertureAprem) && !empty($horaire->heureFermetureAprem)) {
                                        $horairesAffichage[] = $horaire->heureOuvertureAprem . ' - ' . $horaire->heureFermetureAprem;
                                    }
                                    
                                    if (!empty($horairesAffichage)) {
                                        echo implode(' / ', $horairesAffichage);
                                    } else {
                                        echo 'Fermé';
                                    }
                                }
                                echo '</p>';
                            }
                        } else {
                            echo '<p>Aucun horaire disponible</p>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Tahir Hair DESIGN. Tous droits réservés.</p>
                    <a href="index.php?controleur=Admin&action=afficherIndex" class="admin-footer-btn">
                        <i class="fas fa-user-shield"></i> Connexion Admin
                    </a>
                </div>
            </div>
        </footer>

        <!-- Bouton retour en haut -->
        <button id="scrollToTopBtn" onclick="scrollToTop()" aria-label="Retour en haut">
            <i class="fas fa-arrow-up"></i>
        </button>

        <!-- Scripts -->
        <script>
            // Bouton retour en haut
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Afficher/masquer le bouton selon le scroll
            window.addEventListener('scroll', function() {
                const scrollBtn = document.getElementById('scrollToTopBtn');
                if (window.pageYOffset > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            });

            // Smooth scrolling pour les liens internes
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>

