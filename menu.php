<?php
require 'inc/header.php';
require_once 'inc/functions.php';

$menus = get_all_menus();
?>

<section class="menus">
    <?php foreach ($menus as $menu): ?>
        <div class="menu">
            <img src="<?php echo htmlspecialchars($menu['image_path']); ?>"
                alt="<?php echo htmlspecialchars($menu['titre']); ?>" class="menu-photo">
            <div class="menu-content">
                <h2><?php echo htmlspecialchars($menu['titre']); ?></h2>
                <p><?php echo htmlspecialchars($menu['description']); ?></p>
                <p><strong>Thème :</strong> <?php echo htmlspecialchars($menu['theme_nom']); ?></p>
                <p><strong>Régime :</strong> <?php echo htmlspecialchars($menu['regime']); ?></p>
                <p><strong>À partir de :</strong> <?php echo $menu['nombre_personne_minimum']; ?> personnes</p>
                <p class="prix"><strong><?php echo number_format($menu['prix_par_personne'], 2); ?>€</strong> par personne
                </p>
                <a href="detail_menus.php?id=<?php echo $menu['menu_id']; ?>" class="btn-detail">Voir le détail</a>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<?php require 'inc/footer.php'; ?>