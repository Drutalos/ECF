<?php require 'inc/header.php'; ?>

<?php for ($i = 1; $i <= 10; $i++): ?>
    <section class="menus">
        <div class="menu">
            <img src="assets/img/burger.jpg" alt="Menu classique" class="menu-photo">
            <div class="menu-content">
                <h2>Menu classique</h2>
                <ul>
                    <li>Entrée : Verrine de mousse d’avocat et crevettes citronnées</li>
                    <li>Plat : Burger du Chef & frites maison</li>
                    <li>Dessert : Cœur fondant au chocolat</li>
                </ul>
            </div>
        </div>
    </section>
<?php endfor; ?>

<?php require 'inc/footer.php'; ?>