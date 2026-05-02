# projet-gestion\suivi-de-stock
second projet pour l'épreuve E6 


Application web de gestion et suivi de stock développée avec PHP, MySQL, HTML, CSS et JavaScript.

##  Description

A l'aide d'une interface web, ce projet permet de gérer un stock de linge et le suivi individuel des pièces de linge pendant leur mise à disposition dans des locations saisonnières .  

Il permet d’ajouter, modifier et supprimer du linge mais aussi de suivre les étapes et consulter les actions effectuées (log).

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
- Suivi des pièces de linge pendant les étapes d'utilisation (Installation dans location, Envoi en lavage)
- Gestion des défauts constatés sur les pièces de linge (Abimé, manquant...)
- Gestion du stock et suivi des quantités

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

### 2. Cloner le projet

```bash
git clone https://github.com/souvynicolas/gestion_de_stock.git
```

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
* Cliquer sur **Exécuter**

👉 Si tout se passe bien, les tables apparaissent

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
