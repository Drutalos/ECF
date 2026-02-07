<?php
$horaires = get_horaires_mongo();
?>

<body>
<nav class="footer">
    <div>
        <h3>Horaires d'ouverture</h3>
        <?php foreach ($horaires as $h): ?>
            <div>
                <?php echo $h->jour; ?> : <?php echo $h->heure_ouverture; ?> - <?php echo $h->heure_fermeture; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <ul class="nav-links">
            <a href="mentions_legales.php"> Mentions légales</a>
            <a href="cgv.php"> Conditions générales</a>
        </ul>
    </div>
</nav>
</body>
</html>