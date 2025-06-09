document.getElementById('afficher_mdp').addEventListener('click', function() {
    var inputMotDePasse = document.getElementById('passe');
    if (inputMotDePasse.type === "password") {
        inputMotDePasse.type = "text";
        this.textContent = "Masquer";
    } else {
        inputMotDePasse.type = "password";
        this.textContent = "Afficher";
    }
});

