# 🚀 Guide de Déploiement - TR Hair Design sur Byethost

## 📋 Informations de Connexion

### 🔐 Accès cPanel
- **URL** : cpanel.byethost15.com
- **Utilisateur** : b15_39567149
- **Mot de passe** : Cetintas05**

### 🗄️ Base de Données MySQL
- **Hôte** : sql112.byethost15.com
- **Nom de la base** : b15_39567149_bdd_trhairdesign
- **Utilisateur** : b15_39567149
- **Mot de passe** : Cetintas05**

### 📁 Accès FTP
- **Hôte** : ftp.byethost15.com
- **Utilisateur** : b15_39567149
- **Mot de passe** : Cetintas05**

### 🌐 URL du Site
- **Site public** : http://trhairdesign.byethost15.com
- **Administration** : http://trhairdesign.byethost15.com/index.php?controleur=Admin

---

## 📦 Étapes de Déploiement

### 1️⃣ Préparation des Fichiers

#### Fichiers à supprimer avant upload :
```
test_*.php
debug_*.php
*.log
*.txt (sauf README)
```

#### Fichiers essentiels à conserver :
```
index.php
.htaccess
configs/
application/
public/
deploy.php
README_DEPLOIEMENT.md
```

### 2️⃣ Upload des Fichiers

#### Méthode FTP (recommandée) :
1. Connectez-vous via FTP avec FileZilla ou WinSCP
2. Naviguez vers le dossier `htdocs` ou `public_html`
3. Uploadez tous les fichiers du projet
4. Vérifiez que la structure est correcte

#### Méthode cPanel :
1. Connectez-vous au cPanel
2. Ouvrez le Gestionnaire de fichiers
3. Naviguez vers `public_html`
4. Uploadez les fichiers via l'interface web

### 3️⃣ Configuration de la Base de Données

#### Import du fichier SQL :
1. Connectez-vous à phpMyAdmin via le cPanel
2. Sélectionnez la base `b15_39567149_bdd_trhairdesign`
3. Cliquez sur "Importer"
4. Sélectionnez le fichier `configs/bdd_trhairdesign.sql`
5. Cliquez sur "Exécuter"

#### Vérification de l'import :
```sql
SHOW TABLES;
-- Doit afficher : horaire, produit, famille, information, contact, rendezvous
```

### 4️⃣ Configuration des Permissions

#### Dossiers à rendre accessibles en écriture (755) :
```
logs/
public/uploads/
public/uploads/images/
cache/
```

#### Fichiers de configuration (644) :
```
configs/
.htaccess
index.php
```

### 5️⃣ Test de l'Installation

#### Test automatique :
1. Accédez à : `http://trhairdesign.byethost15.com/deploy.php`
2. Vérifiez que tous les tests passent
3. Notez les informations affichées

#### Test manuel :
1. **Site public** : Visitez l'URL principale
2. **Administration** : Accédez à l'interface admin
3. **Connexion admin** : admin / admin123

---

## 🔧 Configuration Post-Déploiement

### 1️⃣ Sécurité

#### Changement du mot de passe admin :
1. Connectez-vous à l'administration
2. Allez dans "Mon compte"
3. Modifiez le mot de passe
4. Utilisez un mot de passe fort

#### Configuration des emails :
1. Modifiez `configs/production_config.php`
2. Configurez les paramètres SMTP si nécessaire
3. Testez l'envoi d'emails

### 2️⃣ Personnalisation

#### Informations du salon :
1. Modifiez les horaires d'ouverture
2. Ajoutez vos services et tarifs
3. Configurez les informations de contact
4. Uploadez votre logo et images

#### Contenu du site :
1. Ajoutez vos actualités
2. Configurez les catégories de services
3. Personnalisez les couleurs si nécessaire

### 3️⃣ Optimisation

#### Performance :
1. Activez la compression GZIP (déjà configurée)
2. Optimisez les images
3. Vérifiez la vitesse de chargement

#### SEO :
1. Configurez les meta tags
2. Ajoutez Google Analytics
3. Créez un sitemap

---

## 🛠️ Dépannage

### Problèmes Courants

#### ❌ Erreur de connexion base de données :
- Vérifiez les paramètres dans `configs/production_config.php`
- Testez la connexion via phpMyAdmin
- Vérifiez que la base existe

#### ❌ Pages blanches :
- Activez l'affichage des erreurs temporairement
- Vérifiez les logs dans le dossier `logs/`
- Contrôlez les permissions des fichiers

#### ❌ Images non affichées :
- Vérifiez les permissions du dossier `public/uploads/`
- Contrôlez les chemins dans le CSS
- Vérifiez la taille des fichiers

#### ❌ Formulaire de contact non fonctionnel :
- Vérifiez la configuration SMTP
- Testez l'envoi d'emails
- Contrôlez les logs d'erreur

### Logs et Debug

#### Activation du debug temporaire :
```php
// Dans configs/production_config.php
define('DEBUG_MODE', true);
define('DISPLAY_ERRORS', true);
```

#### Consultation des logs :
- Erreurs : `logs/error.log`
- Accès : `logs/access.log`

---

## 📞 Support

### Informations de Contact
- **Email** : kadircetintas023@gmail.com
- **Développeur** : Assistant IA Claude
- **Version** : 2.0

### Ressources Utiles
- **Documentation Byethost** : https://byethost.com/help
- **Documentation PHP** : https://www.php.net/docs.php
- **Guide MySQL** : https://dev.mysql.com/doc/

---

## ✅ Checklist de Déploiement

- [ ] Fichiers uploadés sur le serveur
- [ ] Base de données importée
- [ ] Permissions configurées
- [ ] Site accessible publiquement
- [ ] Administration fonctionnelle
- [ ] Mot de passe admin changé
- [ ] Informations du salon configurées
- [ ] Emails de contact testés
- [ ] Images et logo uploadés
- [ ] Horaires d'ouverture configurés
- [ ] Services et tarifs ajoutés
- [ ] Test de performance effectué
- [ ] Sauvegarde créée

---

## 🎉 Félicitations !

Votre site TR Hair Design est maintenant en ligne et prêt à accueillir vos clients !

**URL finale** : http://trhairdesign.byethost15.com

N'oubliez pas de :
- Faire des sauvegardes régulières
- Surveiller les logs d'erreur
- Maintenir le site à jour
- Répondre aux demandes de contact

**Bonne chance avec votre salon de coiffure ! 💇‍♀️✨** 