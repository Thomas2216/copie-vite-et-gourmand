# Vite & Gourmand 🍽️

Application web de traiteur en ligne permettant aux clients de commander des menus livrés à domicile.

Développée dans le cadre du **Titre Professionnel DWWM (Développeur Web et Web Mobile)** avec **Symfony 7.4**, **MySQL**, **MongoDB** et **Bootstrap 5**.

---

## Stack technique

- **Back-end** : PHP 8.2 / Symfony 7.4
- **ORM** : Doctrine ORM
- **Base de données** : MySQL 8.0 + MongoDB (statistiques NoSQL)
- **Front-end** : Twig / Bootstrap 5.3 / JavaScript ES6 (Fetch API)
- **Emails** : Symfony Mailer + Mailtrap (dev)
- **Admin** : EasyAdmin 4
- **Outils** : Docker, Composer, Symfony CLI, Flatpickr

---

## Prérequis

- PHP 8.2+
- Composer
- MySQL 8.0+
- MongoDB
- Docker & Docker Compose
- Symfony CLI

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Thomas2216/Vite-et-gourmand.git
cd Vite-et-gourmand
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

Copier le fichier `.env` et créer un `.env.local` :

```bash
cp .env .env.local
```

Modifier `.env.local` avec vos paramètres :

```env
# Base de données
DATABASE_URL="mysql://root:votre_mot_de_passe@127.0.0.1:3306/vite_gourmand"

# MongoDB
MONGODB_URL="mongodb://localhost:27017"
MONGODB_DB="vite_gourmand"

# Mailer (Mailtrap pour le dev)
MAILER_DSN=smtp://username:password@sandbox.smtp.mailtrap.io:587

# Clé API OpenRouteService (calcul frais de livraison)
ORS_API_KEY=votre_cle_ors
```

### 4. Lancer Docker

```bash
docker-compose up -d
```

### 5. Créer la base de données

```bash
php bin/console doctrine:database:create
```

### 6. Importer le schéma et les données de test

```bash
mysql -u root -p vite_gourmand < dump.sql
```

### 7. Lancer les migrations

```bash
php bin/console doctrine:migrations:migrate
```

### 8. Lancer le serveur

```bash
symfony server:start
```

L'application est accessible sur **http://127.0.0.1:8000**

---

## Comptes de démonstration

Mot de passe pour tous les comptes : `Demo1234!`

| Rôle           | Email              |
|----------------|--------------------|
| Administrateur | admin@demo.fr      |
| Employé        | employe1@demo.fr   |
| Utilisateur    | user1@demo.fr      |

---

## Fonctionnalités

### Côté client (ROLE_USER)
- Inscription / connexion / déconnexion
- Réinitialisation de mot de passe par email
- Catalogue des menus avec détail des plats et allergènes
- Commande en ligne avec panier dynamique (Fetch API / JavaScript vanilla)
- Sélection de date et créneau horaire (Flatpickr)
- Calcul automatique des frais de livraison (API OpenRouteService)
- Réduction 10% appliquée automatiquement selon le nombre de personnes
- Historique des commandes avec suivi des statuts
- Dépôt d'avis avec note (1-5 étoiles)
- Modification du profil

### Côté employé (ROLE_EMPLOYE)
- Visualisation de toutes les commandes
- Mise à jour des statuts de commandes (7 états disponibles)

### Côté administrateur (ROLE_ADMIN)
- Panel EasyAdmin : gestion complète des utilisateurs, menus, plats, commandes et avis
- Modération des avis (valider / refuser)
- Tableau de bord de statistiques de commandes (MongoDB / NoSQL)

---

## Structure du projet
src/
├── Controller/          # Controllers Symfony
│   ├── Admin/           # CRUDs EasyAdmin
│   └── EmployeController.php
├── Document/            # Documents MongoDB (statistiques)
├── Entity/              # Entités Doctrine
├── Form/                # Types de formulaires
├── Repository/          # Requêtes personnalisées
└── Security/            # Authenticator personnalisé
templates/
├── emails/              # Templates emails HTML
├── employe/             # Espace employé
├── index/               # Pages front (accueil, menus, commande...)
├── reset_password/      # Réinitialisation mot de passe
└── security/            # Login / inscription
assets/
├── styles/app.css       # Feuille de style principale
└── app.js               # Logique JavaScript (panier, Fetch API)
migrations/              # Migrations Doctrine versionnées
dump.sql                 # Schéma et données de démonstration
docker-compose.yml       # Configuration Docker

---

## Variables d'environnement requises

| Variable | Description |
|----------|-------------|
| `DATABASE_URL` | URL de connexion MySQL |
| `MONGODB_URL` | URL de connexion MongoDB |
| `MONGODB_DB` | Nom de la base MongoDB |
| `MAILER_DSN` | DSN SMTP pour l'envoi d'emails |
| `ORS_API_KEY` | Clé API OpenRouteService |
| `APP_SECRET` | Secret Symfony (généré automatiquement) |

---

## 📸 Captures d'écran

*(placer les images dans un dossier `/screenshots` à la racine du projet)*

| Page | Aperçu |
|---|---|
| Accueil | ![Accueil](screenshots/home.png) |
| Catalogue menus | ![Menus](screenshots/menus.png) |
| Commande | ![Commande](screenshots/commande.png) |
| Administration | ![Admin](screenshots/admin.png) |
| Statistiques | ![Stats](screenshots/stats.png) |

---

## 👨‍💻 Auteur

**Thomas Valle** — Développeur Web PHP/Symfony | TP DWWM
[LinkedIn](https://www.linkedin.com/in/thomas-valle-dev/) • [GitHub](https://github.com/Thomas2216)

---

## 📄 Licence

Projet réalisé dans un cadre pédagogique — Studi, 2025.
