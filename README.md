# 🍲 Recipe Web App

This is a web application for managing and searching recipes. It is built using PHP, MySQL, HTML5, CSS3, and JavaScript. Users can register, log in, search for recipes, view full recipe details, and save their favourites.

---

## 📁 Table of Contents

* [✅ Features](#features)
* [📁 Folder Structure](#folder-structure)
* [💻 Requirements](#requirements)
* [🛠️ Installation and Setup Guide](#installation-and-setup-guide)
* [▶️ How to Run the App](#how-to-run-the-app)
* [👥 Contributors](#contributors)
* [📜 License](#license)

---
<a id="features"></a>
## ✅ Features

* User registration and login
* Recipe search with various criteria
* Detailed recipe views with ingredients and preparation steps
* Ability to rate recipes and mark favourites
* Account page to manage favourites
* Admin pages to add/edit recipes
* Automatic database creation and seeding from SQL dump
* Mobile responsive and accessible design

---
<a id="folder-structure"></a>
## 📁 Folder Structure

```
recipe-web-app/
├── index.php
├── README.md
├── init.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── scripts.js
│   ├── images/
│   └── audio/
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   └── auth.php
├── templates/
│   ├── home.php
│   ├── search.php
│   ├── recipe.php
│   ├── register.php
│   ├── login.php
│   ├── account.php
│   ├── all_recipes.php
│   ├── mark_favourite.php
│   ├── rate_recipe.php
│   └── update_profile.php
├── sql/
│   └── recipe_app_dump.sql
└── test/
    
```

---
<a id="requirements"></a>
## 💻 Requirements

* [XAMPP](https://www.apachefriends.org/index.html) (Apache, PHP, MySQL)
* [Visual Studio Code](https://code.visualstudio.com/) (or any code editor)
* Web browser (Google Chrome recommended)

---
<a id="installation-and-setup-guide"></a>
## 🛠️ Installation & Setup Guide

### 1. Install XAMPP

Download and install from:
🔗 [https://www.apachefriends.org/](https://www.apachefriends.org/)
Make sure to install Apache and MySQL components.

### 2. Install Visual Studio Code

Download and install from:
🔗 [https://code.visualstudio.com/](https://code.visualstudio.com/)

### 3. Set Up Project Directory

* Go to: `C:\xampp\htdocs\`
* Create a folder named: `recipe-web-app`
* Copy all project files into this folder

### 4. 🔐 Set or Update MySQL Root Password

By default, XAMPP sets the MySQL root password to blank (`''`). If you set a custom password, you'll need to update it both in your **code** and in **XAMPP/phpMyAdmin**.

#### ✅ Update in the Code

Edit the files in the following paths: 
* recipe-web-app/includes/db.php
* recipe-web-app/includes/init.php

Find this line:
```php
$password = '';
```
Change it to your custom password:
```php
$password = 'your_custom_password';
```

✅ Set Password in XAMPP (phpMyAdmin)
1. Open http://localhost/phpmyadmin/
2. Go to the User Accounts tab
3. Locate the row for root@localhost
4. Click Edit Privileges
5. Scroll to the Change Password section
6. Enter your new password and confirm it
7. Click Go

⚠️ If the password in your PHP code does not match the one set in phpMyAdmin, the app won’t be able to connect to the database.

### 5. Start Apache and MySQL

* Launch the **XAMPP Control Panel**
* Start the following services:

  * ✅ Apache
  * ✅ MySQL

### 6. Launch the App

* Visit: `http://localhost/recipe-web-app/`
* On first run, the app will:

  * ✅ Automatically create the `recipe_app` database if it doesn't exist
  * ✅ Import schema and data from `/sql/recipe_app_dump.sql` file

**Note:** You can continue adding more seed data in the `recipe_app_dump.sql` file. It will be automatically reloaded if new data is not already present.

### 7. (Optional) Verify Setup

Open the test script:
🔗 `http://localhost/recipe-web-app/test/test_db.php`
This will confirm connection and list tables.

---
<a id="how-to-run-the-app"></a>
## ▶️ How to Run the App

1. Ensure XAMPP’s **Apache** and **MySQL** are running.
2. Open your browser.
3. Visit: `http://localhost/recipe-web-app/`
4. Register a new user or use seeded accounts to test
5. Browse and interact with recipe listings.

---
<a id="contributors"></a>
## 👥 Contributors

* Tsz Fung Cheung
* Sarim Shaikh
* Sung Shin

---
<a id="license"></a>
## 📜 License

This project is licensed under the MIT License.

Copyright (c) 2025 Tsz Fung Cheung, Sarim Shaikh, Sung Shin

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
