<?php if(session_status()===PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo"><a href="#" Vite & Gourmand></a></div>

    <ul class="nav-links">
        <li><a href="index.php"> Accueil></a></li>
        <li><a href="menu.php"> Nos menus></a></li>
        <li><a href="commande.php"> Commander></a></li>
        <li><a href="#"> L'esprit Vite & Gourmand></a></li>
        <li><a href="contact.php"> Nous contacter></a></li>
    </ul>

    <div class="nav-buttons">
        <a href="#"> Se connecter</a>
        <a href="#"> Créer un compte</a>
    </div>
</nav>