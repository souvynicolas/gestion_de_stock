# projet-gestion-de-stock
deuxième projet epreuve e6 


Application web de gestion de stock développée avec PHP, MySQL, HTML, CSS et JavaScript.

##  Description

Ce projet permet de gérer un stock de linges simplement depuis une interface web.  
Il permet d’ajouter, modifier, supprimer, suivre les etapes et consulter les produits enregistrés.

## 🛠️ Technologies utilisées

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

##  Fonctionnalités

- création de produits
- Ajout de produits
- Modification de produits
- Suppression de produits
- Affichage de la liste des produits
- Gestion des quantités en stock

## ⚙️ Installation

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
git clone https://github.com/souvynicolas/projet-gestion-de-stock.git
```

---

### 3. Placer le projet

Déplacer le dossier dans :

```txt
C:\xampp\htdocs
```

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
* Sélectionner le fichier `database.sql` présent dans le projet
* Cliquer sur **Exécuter**

👉 Si tout se passe bien, les tables apparaissent

---

### 5. Configurer la connexion à la base de données

Dans le projet, ouvrir le fichier de connexion (ex : `config.php` ou `db.php`) et vérifier :

```php
$host = "localhost";
$user = "root";
$password = ""; // mettre votre mot de passe si vous en avez un
$database = "gestion_stock"; // nom de la base créée
```

---

### 6. Lancer le projet

Dans votre navigateur :

```txt
http://localhost/gestion-de-stock
```

---

## 💡 Remarques

* phpMyAdmin est inclus dans XAMPP (pas besoin de l’installer séparément)
* Le fichier `database.sql` contient la structure de la base de données
* Vérifiez que Apache et MySQL sont bien démarrés avant d’ouvrir le projet
