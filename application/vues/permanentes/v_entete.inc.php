<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tahir Hair DESIGN - Salon de coiffure moderne spécialisé dans les coupes pour hommes, styles tendance et soins capillaires de haute qualité.">
    <meta name="keywords" content="coiffeur, salon, coiffure, homme, barber, style, tendance">
    <meta name="author" content="Tahir Hair DESIGN">
    
    <title>Tahir Hair DESIGN - Salon de Coiffure Moderne</title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo Chemins::IMAGES . 'logotahir.png'; ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo Chemins::IMAGES . 'logotahir.png'; ?>">
    
    <!-- Styles -->
    <link href="<?php echo Chemins::STYLES . 'style.css'; ?>" rel="stylesheet" type="text/css">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- JavaScript -->
    <script src="<?php echo Chemins::JS . 'main.js'; ?>" defer></script>
    
    <!-- OpenGraph pour les réseaux sociaux -->
    <meta property="og:title" content="Tahir Hair DESIGN - Salon de Coiffure Moderne">
    <meta property="og:description" content="Salon de coiffure spécialisé dans les coupes pour hommes et les styles tendance.">
    <meta property="og:image" content="<?php echo Chemins::IMAGES . 'logotahir.png'; ?>">
    <meta property="og:type" content="website">
</head>
<body>
    <!-- Header moderne et responsive -->
    <header id="header">
        <div class="header-content">
            <div class="logo">
                <img src="<?php echo Chemins::IMAGES . 'logotahir.png'; ?>" alt="Logo Tahir Hair DESIGN" class="logo-img">
                <h1>Tahir Hair DESIGN</h1>
            </div>
            
            <button class="nav-toggle" id="navToggle" aria-label="Menu de navigation">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav aria-label="Navigation principale">
                <ul id="navMenu">
                    <li><a href="index.php#accueil" class="nav-link" style="color: #2c3e50;">Accueil</a></li>
                    <li><a href="index.php#a-propos" class="nav-link" style="color: #2c3e50;">À propos</a></li>
                    <li><a href="index.php#infos" class="nav-link" style="color: #2c3e50;">Informations</a></li>
                    <li><a href="index.php#services" class="nav-link" style="color: #2c3e50;">Services</a></li>
                    <li><a href="index.php#galerie" class="nav-link" style="color: #2c3e50;">Galerie</a></li>
                    <li><a href="#rendez-vous" class="nav-link" style="color: #2c3e50;">Rendez-vous</a></li>
                    <li><a href="#contact" class="nav-link" style="color: #2c3e50;">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- JavaScript pour la navigation mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('navToggle');
            const navMenu = document.getElementById('navMenu');
            const header = document.getElementById('header');
            
            // Toggle menu mobile
            navToggle.addEventListener('click', function() {
                navMenu.classList.toggle('show');
                const icon = navToggle.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });
            
            // Fermer le menu mobile lors du clic sur un lien
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('show');
                    const icon = navToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                });
            });
            
            // Header scroll effect
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
            
            // Active link highlighting avec smooth scroll
            const currentPage = window.location.hash || '#accueil';
            
            // Fonction pour mettre à jour le lien actif
            function updateActiveLink() {
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                
                // Détecter quelle section est visible
                const sections = ['#accueil', '#a-propos', '#infos', '#services', '#galerie', '#rendez-vous', '#contact'];
                let currentSection = '#accueil';
                
                sections.forEach(section => {
                    const element = document.querySelector(section);
                    if (element) {
                        const rect = element.getBoundingClientRect();
                        if (rect.top <= 100 && rect.bottom >= 100) {
                            currentSection = section;
                        }
                    }
                });
                
                // Mettre à jour le lien actif
                document.querySelectorAll('.nav-link').forEach(link => {
                    if (link.getAttribute('href').includes(currentSection)) {
                        link.classList.add('active');
                    }
                });
            }
            
            // Mettre à jour au scroll
            window.addEventListener('scroll', updateActiveLink);
            
            // Mettre à jour au chargement
            updateActiveLink();
            
            document.querySelectorAll('.nav-link').forEach(link => {
                // Smooth scroll pour les liens internes
                if (link.getAttribute('href').includes('#')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Enlever la classe active de tous les liens
                        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                        // Ajouter la classe active au lien cliqué
                        this.classList.add('active');
                        
                        const targetId = this.getAttribute('href').split('#')[1];
                        const targetElement = document.querySelector('#' + targetId);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                }
            });
        });
    </script>
