<?php require 'inc/header.php'; ?>
<?php require_once 'inc/functions.php'; ?>

<?php
// Tableau qui contiendra les erreurs de validation
$erreurs = [];

// Tableau qui garde les valeurs saisies pour les réafficher en cas d'erreur
// Comme ça l'utilisateur ne doit pas tout retaper !
$donnees = [
    'nom'      => '',
    'prenom'   => '',
    'email'    => '',
    'telephone'=> '',
    'adresse'  => '',
    'ville'    => '',
    'pays'     => ''
];

// isset($_POST['submit']) vérifie si le formulaire a été soumis
// $_POST contient toutes les valeurs envoyées par le formulaire
if (isset($_POST['submit'])) {

    // On récupère et nettoie chaque champ
    // trim() supprime les espaces au début et à la fin
    // htmlspecialchars() sécurise contre les attaques XSS
    $donnees['nom']       = trim(htmlspecialchars($_POST['nom']));
    $donnees['prenom']    = trim(htmlspecialchars($_POST['prenom']));
    $donnees['email']     = trim(htmlspecialchars($_POST['email']));
    $donnees['telephone'] = trim(htmlspecialchars($_POST['telephone']));
    $donnees['adresse']   = trim(htmlspecialchars($_POST['adresse']));
    $donnees['ville']     = trim(htmlspecialchars($_POST['ville']));
    $donnees['pays']      = trim(htmlspecialchars($_POST['pays']));
    $password             = $_POST['password'];
    $password_confirm     = $_POST['password_confirm'];

    // === VALIDATION DES CHAMPS ===

    // Vérifie que les champs obligatoires ne sont pas vides
    // empty() retourne TRUE si la variable est vide
    if (empty($donnees['nom']))       $erreurs[] = "Le nom est obligatoire";
    if (empty($donnees['prenom']))    $erreurs[] = "Le prénom est obligatoire";
    if (empty($donnees['telephone'])) $erreurs[] = "Le téléphone est obligatoire";
    if (empty($donnees['adresse']))   $erreurs[] = "L'adresse est obligatoire";
    if (empty($donnees['ville']))     $erreurs[] = "La ville est obligatoire";
    if (empty($donnees['pays']))      $erreurs[] = "Le pays est obligatoire";

    // Vérifie que l'email est valide avec filter_var()
    // FILTER_VALIDATE_EMAIL est un filtre PHP qui vérifie le format d'un email
    if (!filter_var($donnees['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'email n'est pas valide";
    }

    // Vérifie que l'email n'existe pas déjà en base
    elseif (email_existe($donnees['email'])) {
        $erreurs[] = "Cet email est déjà utilisé";
    }

    // Vérifie que les deux mots de passe sont identiques
    if ($password !== $password_confirm) {
        $erreurs[] = "Les mots de passe ne correspondent pas";
    } else {
        // Valide les règles de sécurité du mot de passe
        // array_merge() fusionne les erreurs de validation avec le tableau existant
        $erreurs = array_merge($erreurs, valider_password($password));
    }

    // === INSCRIPTION SI PAS D'ERREURS ===

    // count() compte le nombre d'éléments dans le tableau
    // Si 0 erreurs, on peut inscrire l'utilisateur
    if (count($erreurs) === 0) {

        // Tente d'insérer l'utilisateur en base
        $succes = inscrire_utilisateur(
            $donnees['nom'],
            $donnees['prenom'],
            $donnees['email'],
            $password,
            $donnees['telephone'],
            $donnees['ville'],
            $donnees['pays'],
            $donnees['adresse']
        );

        if ($succes) {
            // Redirige vers la page de connexion avec un message de succès
            // Le ?inscription=succes permet d'afficher un message sur la page connexion
            header('Location: connexion.php?inscription=succes');
            exit;
        } else {
            $erreurs[] = "Une erreur est survenue, veuillez réessayer";
        }
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <h2>Créer un compte</h2>

        <?php if (!empty($erreurs)): ?>
            <!-- Affiche les erreurs si le tableau n'est pas vide -->
            <div class="erreurs">
                <?php foreach ($erreurs as $erreur): ?>
                    <p>❌ <?php echo $erreur; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- method="POST" envoie les données de façon sécurisée (pas dans l'URL) -->
        <!-- action="" soumet le formulaire sur la même page -->
        <form method="POST" action="">

            <div class="form-row">
                <!-- Le 'value' réaffiche la valeur saisie en cas d'erreur -->
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" 
                           value="<?php echo $donnees['nom']; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" 
                           value="<?php echo $donnees['prenom']; ?>" 
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo $donnees['email']; ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone *</label>
                <input type="tel" id="telephone" name="telephone" 
                       value="<?php echo $donnees['telephone']; ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse postale *</label>
                <input type="text" id="adresse" name="adresse" 
                       value="<?php echo $donnees['adresse']; ?>" 
                       required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ville">Ville *</label>
                    <input type="text" id="ville" name="ville" 
                           value="<?php echo $donnees['ville']; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="pays">Pays *</label>
                    <input type="text" id="pays" name="pays" 
                           value="<?php echo $donnees['pays']; ?>" 
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <!-- type="password" masque le mot de passe -->
                <input type="password" id="password" name="password" required>
                <!-- Rappel des règles à l'utilisateur -->
                <small>10 caractères minimum, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial</small>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe *</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <!-- name="submit" permet à isset($_POST['submit']) de détecter la soumission -->
            <button type="submit" name="submit" class="btn-submit">
                Créer mon compte
            </button>

        </form>

        <!-- Lien vers la page connexion si déjà un compte -->
        <p class="auth-link">
            Déjà un compte ? <a href="connexion.php">Se connecter</a>
        </p>
    </div>
</section>

<?php require 'inc/footer.php'; ?>