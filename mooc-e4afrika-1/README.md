# E4Afrika MOOC Platform

> Plateforme de formation en ligne certifiante — Mémoire de Licence Professionnelle  
> **École Supérieure Sainte Félicité (ESSF)** | Cotonou, Bénin | Stage E4Afrika

---

## 📋 Présentation

**E4Afrika MOOC** est une plateforme d'apprentissage en ligne (MOOC) développée dans le cadre d'un stage académique de 3 mois au sein de l'entreprise **E4Afrika** (Cotonou, Bénin). Elle permet à des apprenants africains de suivre des formations certifiantes en ligne, accessibles depuis tout appareil.

### Fonctionnalités principales
- 🎓 Catalogue de cours avec enrollment en ligne
- 📊 Suivi de progression par module
- 📝 Soumission et notation de devoirs
- 🏆 Génération automatique de certificats PDF
- 🔐 Authentification sécurisée multi-rôles
- 📱 Interface responsive (mobile-first)

---

## 🛠️ Stack technique

| Composant | Technologie |
|-----------|-------------|
| Backend   | PHP 8.1+ (architecture MVC) |
| Frontend  | HTML5, CSS3, Bootstrap 5.3, JavaScript ES6+ |
| Base de données | MySQL 8.0 |
| Serveur local | WAMP / XAMPP (Apache + PHP + MySQL) |
| Éditeur recommandé | Visual Studio Code |
| Versioning | Git |

---

## 📁 Structure du projet

```
mooc-e4afrika/
├── index.php                     ← Page d'accueil
├── README.md
├── .htaccess                     ← Sécurité Apache
├── config/
│   ├── database.php              ← Connexion PDO
│   ├── config.php                ← Constantes globales
│   └── session.php               ← Gestion session
├── controllers/
│   ├── AuthController.php        ← Login/logout/register
│   ├── CoursController.php       ← Catalogue, détail, quiz
│   ├── ApprenantController.php   ← Dashboard apprenant
│   ├── FormateurController.php   ← Dashboard formateur
│   └── AdminController.php       ← Dashboard admin
├── models/
│   ├── User.php                  ← Auth multi-rôles
│   ├── Cours.php                 ← CRUD cours/modules
│   ├── Apprenant.php             ← Progression, devoirs
│   ├── Formateur.php             ← Stats, notation
│   ├── Admin.php                 ← Gestion globale
│   └── Certificat.php           ← Rendu HTML/PDF
├── helpers/
│   ├── auth.php                  ← Contrôle d'accès
│   ├── csrf.php                  ← Protection CSRF
│   ├── functions.php             ← Utilitaires
│   └── upload.php                ← Gestion fichiers
├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   └── footer.php
│   ├── auth/                     ← Login, register, forgot
│   ├── public/                   ← Accueil, catalogue, détail
│   ├── apprenant/                ← Dashboard, cours, quiz...
│   ├── formateur/                ← Dashboard, cours, notation
│   └── admin/                    ← Dashboard, utilisateurs...
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── img/logo.svg
├── uploads/
│   └── devoirs/                  ← Fichiers soumis
└── sql/
    └── bdmooc.sql                ← Script BDD complet
```

---

## 🚀 Installation locale (WAMP/XAMPP)

### Prérequis
- [WAMP](https://www.wampserver.com/) ou [XAMPP](https://www.apachefriends.org/) installé
- PHP >= 8.1
- MySQL >= 8.0
- Navigateur : Google Chrome (recommandé)

### Étape 1 — Placer les fichiers

**WAMP :**
```
C:\wamp64\www\mooc-e4afrika\
```

**XAMPP :**
```
C:\xampp\htdocs\mooc-e4afrika\
```

### Étape 2 — Créer la base de données

1. Démarrez WAMP/XAMPP
2. Ouvrez **phpMyAdmin** → `http://localhost/phpmyadmin`
3. Créez une base de données nommée `bdmooc` (utf8mb4_unicode_ci)
4. Sélectionnez `bdmooc` → onglet **Importer**
5. Choisissez le fichier `sql/bdmooc.sql` → cliquez **Exécuter**

Ou via la ligne de commande :
```bash
mysql -u root -p -e "CREATE DATABASE bdmooc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p bdmooc < sql/bdmooc.sql
```

### Étape 3 — Configurer la connexion

Ouvrir `config/database.php` et ajuster si nécessaire :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bdmooc');
define('DB_USER', 'root');
define('DB_PASS', '');          // Mot de passe MySQL (vide par défaut sur WAMP/XAMPP)
```

Ouvrir `config/config.php` et vérifier la BASE_URL :
```php
define('BASE_URL', 'http://localhost/mooc-e4afrika');
```

### Étape 4 — Permissions uploads

S'assurer que le dossier `uploads/` est accessible en écriture :
```bash
chmod -R 755 uploads/       # Linux/Mac
# Sur Windows : clic droit → Propriétés → Sécurité → Modifier
```

### Étape 5 — Lancer l'application

Ouvrez : **http://localhost/mooc-e4afrika/**

---

## 🔑 Comptes de démonstration

> **Note :** Les mots de passe hashés dans `bdmooc.sql` correspondent à `password` (hash Laravel-compatible). Pour une démo rapide, régénérez-les avec `password_hash()`.

Exécutez ce script PHP une seule fois pour réinitialiser les mots de passe de démo :

```php
<?php
require 'config/database.php';
$pdo = getPDO();
$hash = password_hash('Admin@1234', PASSWORD_BCRYPT);
$pdo->prepare("UPDATE administrateur SET password=?")->execute([$hash]);
$hash2 = password_hash('Formateur@1', PASSWORD_BCRYPT);
$pdo->prepare("UPDATE formateur SET password=?")->execute([$hash2]);
$hash3 = password_hash('Apprenant@1', PASSWORD_BCRYPT);
$pdo->prepare("UPDATE apprenant SET password=?")->execute([$hash3]);
echo "Mots de passe mis à jour !";
```

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| 🛡️ Super Admin | admin@e4afrika.com | Admin@1234 |
| 🎓 Formateur 1 | kokou.mensah@e4afrika.com | Formateur@1 |
| 🎓 Formateur 2 | aissatou.diallo@e4afrika.com | Formateur@1 |
| 📚 Apprenant 1 | fidele.agossou@email.com | Apprenant@1 |
| 📚 Apprenant 2 | gervais.boco@email.com | Apprenant@1 |
| 📚 Apprenant 3 | mariette.azondekon@email.com | Apprenant@1 |

---

## 🗄️ Base de données — Schéma

```
administrateur ──── (authentification admin)
formateur      ──┐
                 ├── cours ──── module ──── contenu
apprenant      ──┤              │          (VIDEO/PDF/QUIZ)
  └── option   ──┘              └── quiz
       └── filiere
enroller (apprenant ↔ cours)    ← enrollment + progression
soumission_devoir               ← remise devoirs
progression_module              ← suivi par module
reponse_quiz                    ← réponses quiz
certificat                      ← généré automatiquement
logs_actions                    ← audit trail
```

---

## 🔐 Sécurité implémentée

| Mesure | Détail |
|--------|--------|
| Hashage passwords | `password_hash()` BCRYPT |
| Injections SQL | PDO + requêtes préparées |
| XSS | `htmlspecialchars()` sur tous les outputs |
| CSRF | Token unique par formulaire |
| Contrôle d'accès | Middleware par rôle (`requireAdmin()`, etc.) |
| Blocage brute-force | 3 tentatives → blocage 15 min |
| Sessions sécurisées | `httponly`, `samesite=Strict`, régénération ID |
| Upload sécurisé | Validation MIME réelle + taille max 5 Mo |
| Logs actions | Table `logs_actions` en base |

---

## 📸 Pages pour captures d'écran (mémoire)

| Page | URL |
|------|-----|
| Accueil | `http://localhost/mooc-e4afrika/` |
| Connexion | `http://localhost/mooc-e4afrika/controllers/AuthController.php?action=login` |
| Inscription | `http://localhost/mooc-e4afrika/controllers/AuthController.php?action=register` |
| Catalogue | `http://localhost/mooc-e4afrika/controllers/CoursController.php?action=catalogue` |
| Détail cours | `http://localhost/mooc-e4afrika/controllers/CoursController.php?action=detail&id=1` |
| Dashboard apprenant | `http://localhost/mooc-e4afrika/controllers/ApprenantController.php` |
| Progression | `http://localhost/mooc-e4afrika/controllers/ApprenantController.php?action=progression&idcours=1` |
| Quiz | `http://localhost/mooc-e4afrika/controllers/CoursController.php?action=quiz&idmodule=2` |
| Devoirs | `http://localhost/mooc-e4afrika/controllers/ApprenantController.php?action=devoirs` |
| Certificats | `http://localhost/mooc-e4afrika/controllers/ApprenantController.php?action=certificats` |
| Dashboard formateur | `http://localhost/mooc-e4afrika/controllers/FormateurController.php` |
| Mes cours (formateur) | `http://localhost/mooc-e4afrika/controllers/FormateurController.php?action=mes_cours` |
| Dashboard admin | `http://localhost/mooc-e4afrika/controllers/AdminController.php` |
| Gestion apprenants | `http://localhost/mooc-e4afrika/controllers/AdminController.php?action=apprenants` |
| Rapports | `http://localhost/mooc-e4afrika/controllers/AdminController.php?action=rapports` |

---

## 👤 Auteur

**Mémoire de Licence Professionnelle en Informatique**  
Option : Systèmes Informatiques et Logiciels  
École Supérieure Sainte Félicité (ESSF) — Cotonou, Bénin  
Entreprise d'accueil : **E4Afrika**  

---

## 📄 Licence

Projet académique — Usage éducatif uniquement.  
© 2024 E4Afrika MOOC Platform
