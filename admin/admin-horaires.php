<?php require 'inc/header.php'; ?>

<h1>HORAIRES</h1>

<form method="post" action="">
    <?php
    $jours = [
        "Lundi",
        "Mardi",
        "Mercredi",
        "Jeudi",
        "Vendredi",
        "Samedi",
        "Dimanche"
    ];

    foreach ($jours as $jour) {
        $key = strtolower($jour);
        echo '
        <div class="jour">
            <label>' . $jour . '</label>
            <input type="time" name="' . $key . '_matin">
            <input type="time" name="' . $key . '_aprem">
        </div>
        ';
    }
    ?>

    <div class="actions">
        <button type="submit">Enregistrer</button>
    </div>
</form>

<?php require 'inc/footer.php'; ?>