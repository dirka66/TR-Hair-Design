<section class="form-container">
    <div class="titre">
        Administration du site (Accès réservé)
    </div>
    <form method="post" action="index.php?controleur=Admin&action=verifierConnexion">
        <fieldset>
            <legend>Identification</legend> 
            <label for="login">Votre login :</label> <input type="text" name="login" id="login" /> <br/> <br/>
            <label for="passe">Votre mot de passe : </label><input type="password" name="passe" id="passe" />
            <button type="button" id="afficher_mdp">Afficher</button> <!-- Bouton pour afficher le mot de passe -->
            <br/> <br/>
            <input type="checkbox" name="connexion_auto" id="connexion_auto" />
            <label for="connexion_auto" class="enligne"> Connexion automatique </label><br/> <br/>
            <input type="submit" value="Connexion" />
        </fieldset>
    </form>
</section>
<script>
<?php
require_once Chemins::JS . 'showMDP.js';
?>
</script>
