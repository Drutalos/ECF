<?php
require_once __DIR__ . '/functions.php';
$horaires = get_horaires_mongo();
?>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Horaires d'ouverture</h3>
            <div class="horaires-list">
                <?php foreach ($horaires as $h): ?>
                    <div class="horaire-item">
                        <span class="jour"><?php echo $h->jour; ?></span>
                        <span class="heures">
                            <?php 
                            if ($h->heure_ouverture === "Fermé" || $h->heure_ouverture === "") {
                                echo "Fermé";
                            } else {
                                echo $h->heure_ouverture . " - " . $h->heure_fermeture;
                            }
                            ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="footer-section">
            <h3>Liens utiles</h3>
            <nav class="footer-nav">
                <a href="mentions_legales.php">Mentions légales</a>
                <a href="cgv.php">Conditions générales</a>
            </nav>
        </div>
    </div>
</footer>

</body>
</html>