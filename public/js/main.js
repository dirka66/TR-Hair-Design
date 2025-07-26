// Scripts pour l'animation et l'UX du site
document.addEventListener('DOMContentLoaded', function() {
    
    // Animation d'apparition des éléments au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observer tous les éléments avec la classe 'animate-on-scroll'
    document.querySelectorAll('.service-card, .info-card, .stat-card').forEach(el => {
        el.classList.add('animate-on-scroll');
        observer.observe(el);
    });

    // Smooth scroll pour les liens d'ancre
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

    // Auto-fermeture des alertes après 5 secondes
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });

    // Validation du formulaire de contact
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const email = this.querySelector('input[type="email"]');
            const telephone = this.querySelector('input[type="tel"]');
            
            // Validation email
            if (email && email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    e.preventDefault();
                    showAlert('Veuillez saisir une adresse email valide.', 'error');
                    email.focus();
                    return;
                }
            }
            
            // Validation téléphone
            if (telephone && telephone.value) {
                const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
                if (!phoneRegex.test(telephone.value.replace(/\s/g, ''))) {
                    e.preventDefault();
                    showAlert('Veuillez saisir un numéro de téléphone valide.', 'error');
                    telephone.focus();
                    return;
                }
            }
        });
    }

    // Validation du formulaire de rendez-vous
    const rdvForm = document.querySelector('.rdv-form');
    if (rdvForm) {
        rdvForm.addEventListener('submit', function(e) {
            const dateInput = this.querySelector('input[type="date"]');
            const heureSelect = this.querySelector('select[name="heure_rdv"]');
            
            // Vérifier que la date n'est pas dans le passé
            if (dateInput && dateInput.value) {
                const selectedDate = new Date(dateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    e.preventDefault();
                    showAlert('Vous ne pouvez pas choisir une date dans le passé.', 'error');
                    dateInput.focus();
                    return;
                }
            }
            
            // Vérifier qu'un créneau horaire est sélectionné
            if (heureSelect && !heureSelect.value) {
                e.preventDefault();
                showAlert('Veuillez sélectionner un créneau horaire.', 'error');
                heureSelect.focus();
                return;
            }
        });
    }
});

// Fonction pour afficher des alertes dynamiques
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    // Insérer l'alerte en haut du contenu
    const main = document.querySelector('main') || document.body;
    main.insertBefore(alertDiv, main.firstChild);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        alertDiv.style.transition = 'opacity 0.5s ease-out';
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 500);
    }, 5000);
}

// Fonction pour formater les numéros de téléphone en temps réel
function formatPhoneNumber(input) {
    input.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.startsWith('33')) {
            value = '0' + value.substring(2);
        }
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        // Format: 01 23 45 67 89
        if (value.length >= 2) {
            value = value.replace(/(\d{2})(?=\d)/g, '$1 ');
        }
        
        this.value = value.trim();
    });
}

// Appliquer le formatage aux champs téléphone
document.querySelectorAll('input[type="tel"]').forEach(formatPhoneNumber);
