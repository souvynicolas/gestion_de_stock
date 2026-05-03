# Projet-gestion\suivi-de-stock
Second projet pour l'épreuve E6 


Application web de gestion et suivi de stock développée avec PHP, MySQL, HTML, CSS et JavaScript.

##  Description

A l'aide d'une interface web, ce projet permet de gérer un stock de linge et le suivi individuel des pièces de linge pendant leur mise à disposition dans des locations saisonnières .  

Il permet d'assurer la gestion du linge (Création, modification, suppression) mais aussi de le suivre aux différentes étapes de son cycle d'utilisation.

## 🛠️ Technologies utilisées

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

##  Fonctionnalités

- Gestion de paramètres
- Gestion des articles (Création, mofification,suppression,visualisation)
- Gestion des pièces de linges (Création, mofification,suppression,visualisation)
- Déclaration des actions d'installation et d'envoi en lavage (Vers blanchisserie) des pièces de linge
- Gestion des défauts constatés sur les pièces de linge (Abimé, manquant...)
- Historisation des étapes de suivi des pièces de linge
- Consultation de l'historique des étapes
- Gestion du stock et suivi des quantités

## doumentation
 synthèse des documents remit dans dossier documentations list_doc.png

## 🌍 Site en ligne

Le projet est disponible ici :

```txt
https://projet2-gestion-stock.nicolassweb.fr

Solution de secours

## ⚙️ Installation en local

### 1. Installer XAMPP

* Télécharger XAMPP : https://www.apachefriends.org/fr/index.html
* Installer le logiciel (laisser les options par défaut)
* Lancer le **XAMPP Control Panel**
* Démarrer :

  * **Apache**
  * **MySQL**

---

### 2. télécharger le dossier

cliquer sur code en haut a droite
download zip
extraire le dossier

---

### 3. Placer le projet

Déplacer le dossier dans :

```txt
C:\xampp\htdocs
```
le nommer"gestion_de_stock"
---

### 4. Importer la base de données avec phpMyAdmin

#### Étape 1 : Ouvrir phpMyAdmin

Dans votre navigateur :

```txt
http://localhost/phpmyadmin
```

---

#### Étape 2 : Créer une base de données

* Cliquer sur **"Nouvelle base de données"** (à gauche ou en haut)
* Donner un nom à la base : `gestion_de_stock_test`
* Cliquer sur **Créer**

---

#### Étape 3 : Importer le fichier `.sql`

* Cliquer sur la base que vous venez de créer
* Aller dans l’onglet **"Importer"**
* Cliquer sur **"Choisir un fichier"**
* Sélectionner le fichier `gestion_de_stock_test.sql` présent dans le projet
* Cliquer sur **Importer**

👉 Si tout se passe bien, les tables apparaissent
 si erreur remplacer dans fichier gestion_de_stock_test.sql utfm_mb4_0900_ai_ci par utfm_mb4_0900_unicode_ci

---

### 5. Configurer la connexion à la base de données

Dans le projet, ouvrir le fichier de connexion dans  le dossier config.php database.php et vérifier :

```php
$host = "localhost";
$user = "root";
$password = ""; // mettre votre mot de passe si vous en avez un
$database = "gestion_de_stock_test";
```

---

### 6. Lancer le projet

Dans votre navigateur :

```txt
http://localhost/gestion_de_stock
```

---
