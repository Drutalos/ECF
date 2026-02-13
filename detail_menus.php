<?php require 'inc/header.php'; ?>
<?php require_once 'inc/functions.php'; ?>

<?php
// On récupère l'ID dans l'URL (ex: detail_menus.php?id=1)
// isset() vérifie que 'id' existe dans l'URL
// (int) force la valeur à être un nombre entier (sécurité)
// Si 'id' n'existe pas dans l'URL, on met 0 par défaut
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// On appelle la fonction get_menu_details() avec l'ID récupéré
// Cette fonction retourne un tableau avec toutes les infos du menu
// ou NULL si le menu n'existe pas
$menu = get_menu_details($id);

// Si le menu n'existe pas (ID invalide ou inexistant)
// On redirige vers la page des menus
if (!$menu) {
    header('Location: menu.php');
    exit; // Arrête l'exécution du script après la redirection
}
?>

<section class="detail-menu">

    <!-- Titre du menu -->
    <h2><?php echo htmlspecialchars($menu['titre']); ?></h2>

    <!-- Image principale du menu -->
    <img src="<?php echo htmlspecialchars($menu['image_path']); ?>" 
         alt="<?php echo htmlspecialchars($menu['titre']); ?>"
         class="menu-detail-photo">

    <!-- Informations générales -->
    <div class="menu-info">

        <!-- Description complète du menu -->
        <p><?php echo htmlspecialchars($menu['description']); ?></p>

        <!-- Thème du menu (Noël, Pâques, etc.) -->
        <p><strong>Thème :</strong> <?php echo htmlspecialchars($menu['theme_nom']); ?></p>

        <!-- Régime alimentaire (végétarien, vegan, etc.) -->
        <p><strong>Régime :</strong> <?php echo htmlspecialchars($menu['regime']); ?></p>

        <!-- Nombre minimum de personnes pour commander -->
        <p><strong>Minimum :</strong> <?php echo $menu['nombre_personne_minimum']; ?> personnes</p>

        <!-- Prix formaté avec 2 décimales -->
        <p><strong>Prix :</strong> <?php echo number_format($menu['prix_par_personne'], 2); ?>€ par personne</p>

        <!-- Stock restant -->
        <p><strong>Stock disponible :</strong> <?php echo $menu['quantite_restante']; ?> commandes</p>

        <!-- Conditions importantes (mis en évidence comme demandé dans l'énoncé) -->
        <div class="conditions">
            <p><strong>⚠️ Conditions :</strong> <?php echo htmlspecialchars($menu['conditions']); ?></p>
        </div>

    </div>

    <!-- Liste des plats du menu -->
    <div class="menu-plats">
        <h3>Composition du menu</h3>

        <?php 
        // On boucle sur tous les plats du menu
        // $menu['plats'] est un tableau de plats récupéré par get_menu_details()
        foreach ($menu['plats'] as $plat): ?>

            <div class="plat">

                <!-- Type du plat (entrée, plat, dessert) -->
                <!-- ucfirst() met la première lettre en majuscule -->
                <span class="type-plat"><?php echo ucfirst($plat['type_plat']); ?></span>

                <!-- Nom du plat -->
                <h4><?php echo htmlspecialchars($plat['titre_plat']); ?></h4>

                <!-- Description du plat -->
                <p><?php echo htmlspecialchars($plat['description']); ?></p>

                <!-- Allergènes du plat -->
                <?php if (!empty($plat['allergenes'])): ?>
                    <!-- !empty() vérifie que la liste d'allergènes n'est pas vide -->
                    <p class="allergenes">
                        <strong>⚠️ Allergènes :</strong> 
                        <?php echo htmlspecialchars($plat['allergenes']); ?>
                    </p>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>
    </div>

    <!-- Bouton commander -->
    <!-- On passe l'ID du menu en paramètre GET pour pré-remplir le formulaire -->
    <a href="commander.php?menu_id=<?php echo $menu['menu_id']; ?>" class="btn-commander">
        Commander ce menu
    </a>

</section>

<?php require 'inc/footer.php'; ?>