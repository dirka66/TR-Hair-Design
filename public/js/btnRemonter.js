// Afficher le bouton lorsque l'utilisateur fait défiler vers le bas de 20px à partir du haut du document
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollToTopBtn.style.display = "block";
    } else {
        scrollToTopBtn.style.display = "none";
    }
}

// Lorsque l'utilisateur clique sur le bouton, remonter en haut de la page
function scrollToTop() {
    document.body.scrollTop = 0; // Pour Safari
    document.documentElement.scrollTop = 0; // Pour Chrome, Firefox, IE et Opera
}


