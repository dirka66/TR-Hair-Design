# TR Hair Design - Site Web de Salon de Coiffure

## 🎯 Description

Site web professionnel pour salon de coiffure avec système d'administration complet. Permet la gestion des rendez-vous, services, horaires, actualités et messages clients.

## ✨ Fonctionnalités

### 🎨 Interface Publique
- **Page d'accueil** avec présentation du salon
- **Services et tarifs** avec catégorisation
- **Horaires d'ouverture** dynamiques
- **Actualités et annonces**
- **Formulaire de contact**
- **Prise de rendez-vous en ligne**

### 🔧 Interface d'Administration
- **Tableau de bord** avec statistiques
- **Gestion des rendez-vous** (confirmer/supprimer)
- **Gestion des services** (ajouter/modifier/supprimer)
- **Gestion des catégories** de services
- **Gestion des horaires** d'ouverture
- **Gestion des actualités**
- **Gestion des messages** clients
- **Gestion du profil** administrateur

## 🚀 Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Extensions PHP : PDO, PDO_MySQL, mbstring

### Étapes d'installation

1. **Télécharger les fichiers**
   ```bash
   git clone [url-du-repo]
   cd trhairdesign
   ```

2. **Configurer la base de données**
   - Créer une base de données MySQL
   - Importer le fichier `configs/bdd_trhairdesign.sql`
   - Modifier `configs/mysql_config.class.php` avec vos identifiants

3. **Configurer l'application**
   - Copier `configs/production.php` vers `configs/config.php`
   - Modifier les paramètres dans `configs/config.php`
   - Ajuster les chemins dans `configs/chemins.class.php`

4. **Permissions des dossiers**
   ```bash
   chmod 755 public/
   chmod 755 application/
   chmod 644 .htaccess
   ```

5. **Configuration du serveur web**
   - Pointer le DocumentRoot vers le dossier racine
   - Activer la réécriture d'URL (mod_rewrite)
   - Configurer PHP avec les extensions requises

## ⚙️ Configuration

### Base de données
Modifier `configs/mysql_config.class.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'votre_base');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
```

### Configuration de production
Modifier `configs/config.php` :
```php
define('BASE_URL', 'https://votre-domaine.com');
define('ADMIN_EMAIL', 'admin@votre-domaine.com');
define('SMTP_HOST', 'smtp.votre-hebergeur.com');
// ... autres paramètres
```

### Identifiants administrateur par défaut
- **Login** : admin
- **Mot de passe** : admin123
- **⚠️ IMPORTANT** : Changer ces identifiants après la première connexion !

## 🔒 Sécurité

### Mesures implémentées
- Protection CSRF sur tous les formulaires
- Validation des données côté serveur
- Protection contre l'injection SQL (PDO)
- Headers de sécurité (XSS, Clickjacking)
- Protection des fichiers sensibles
- Sessions sécurisées

### Recommandations
- Utiliser HTTPS en production
- Changer les identifiants par défaut
- Configurer des sauvegardes automatiques
- Surveiller les logs d'erreurs
- Mettre à jour régulièrement PHP et MySQL

## 📁 Structure des fichiers

```
trhairdesign/
├── application/
│   ├── controleurs/     # Contrôleurs MVC
│   ├── modeles/        # Modèles de données
│   └── vues/           # Templates d'affichage
├── configs/            # Configuration
├── public/             # Fichiers publics
│   ├── images/         # Images du site
│   ├── js/            # JavaScript
│   └── styles/        # CSS
├── .htaccess          # Configuration Apache
├── index.php          # Point d'entrée
└── README.md          # Documentation
```

## 🛠️ Maintenance

### Sauvegardes
- Configurer des sauvegardes automatiques de la base de données
- Sauvegarder régulièrement les fichiers de configuration
- Tester les restaurations périodiquement

### Mises à jour
- Surveiller les mises à jour de sécurité PHP
- Tester les nouvelles fonctionnalités en environnement de développement
- Documenter les modifications apportées

### Monitoring
- Surveiller les logs d'erreurs
- Vérifier les performances du site
- Contrôler l'espace disque et la base de données

## 📞 Support

Pour toute question ou problème :
- Consulter la documentation technique
- Vérifier les logs d'erreurs
- Contacter le développeur

## 📄 Licence

Ce projet est développé pour TR Hair Design. Tous droits réservés.

---

**Version** : 2.0.0  
**Dernière mise à jour** : Décembre 2024  
**Développeur** : Assistant IA Claude
