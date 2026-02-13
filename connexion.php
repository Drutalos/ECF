<?php require 'inc/header.php'; ?>
<?php require_once 'inc/functions.php'; ?>

<?php
// Tableau qui contiendra les erreurs
$erreurs = [];

// Garde l'email saisi pour le réafficher en cas d'erreur
$email = '';

// Vérifie si le formulaire a été soumis
if (isset($_POST['submit'])) {

    // Récupère et nettoie les champs
    $email    = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];
    // Note : on ne htmlspecialchars() pas le password car
    // les caractères spéciaux font partie du mot de passe !

    // === VALIDATION BASIQUE ===

    // Vérifie que les champs ne sont pas vides
    if (empty($email) || empty($password)) {
        $erreurs[] = "Email et mot de passe sont obligatoires";
    } else {

        // === VÉRIFICATION EN BASE ===

        // Cherche l'utilisateur par son email
        $utilisateur = get_utilisateur_par_email($email);

        // Si l'utilisateur n'existe pas OU si le mot de passe est incorrect
        // On met les deux conditions ensemble pour ne pas donner d'indice au hacker
        if (!$utilisateur || !password_verify($password, $utilisateur['password'])) {
            $erreurs[] = "Email ou mot de passe incorrect";
        } else {

            // === CRÉATION DE LA SESSION ===

            // On stocke les infos utiles dans la session
            // Comme ça on peut y accéder sur toutes les pages
            $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
            $_SESSION['prenom']         = $utilisateur['prenom'];
            $_SESSION['email']          = $utilisateur['email'];
            $_SESSION['role']           = $utilisateur['role_libelle'];
            // On ne stocke JAMAIS le mot de passe en session !

            // === REDIRECTION SELON LE RÔLE ===

            // switch() est comme une série de if/elseif mais plus lisible
            // Il compare $_SESSION['role'] avec chaque cas
            switch ($_SESSION['role']) {
                case 'administrateur':
                    header('Location: admin/espace_admin.php');
                    break; // Arrête le switch après ce cas

                case 'employe':
                    header('Location: employe/espace_employe.php');
                    break;

                default:
                    // Par défaut (role = utilisateur) → page d'accueil
                    header('Location: index.php');
                    break;
            }
            exit; // Arrête l'exécution du script après la redirection
        }
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <h2>Se connecter</h2>

        <?php 
        // Affiche le message de succès si l'utilisateur vient de s'inscrire
        // isset() vérifie que le paramètre existe dans l'URL
        // === vérifie que la valeur est exactement "succes"
        if (isset($_GET['inscription']) && $_GET['inscription'] === 'succes'): ?>
            <div class="succes">
                ✅ Compte créé avec succès ! Vous pouvez vous connecter.
            </div>
        <?php endif; ?>

        <?php if (!empty($erreurs)): ?>
            <div class="erreurs">
                <?php foreach ($erreurs as $erreur): ?>
                    <p>❌ <?php echo $erreur; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo $email; ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required>
            </div>

            <!-- Lien mot de passe oublié -->
            <div class="forgot-password">
                <a href="mot_de_passe_oublie.php">Mot de passe oublié ?</a>
            </div>

            <button type="submit" name="submit" class="btn-submit">
                Se connecter
            </button>

        </form>

        <!-- Lien vers inscription si pas encore de compte -->
        <p class="auth-link">
            Pas encore de compte ? <a href="inscription.php">Créer un compte</a>
        </p>
    </div>
</section>

<?php require 'inc/footer.php'; ?>