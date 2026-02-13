<?php

// inc/functions.php
require_once __DIR__ . '/config.php';

//récupération des horaires
function get_horaires_mongo(): mixed
{
    global $horaires;
    return $horaires->find()->toArray();
}
// Récupération des menus
function get_all_menus()
{
    global $pdo;
    $sql = "SELECT m.*, t.libelle as theme_nom, 
            COUNT(DISTINCT mp.plat_id) as nb_plats
            FROM menu m
            LEFT JOIN theme t ON m.theme_id = t.theme_id
            LEFT JOIN menu_plat mp ON m.menu_id = mp.menu_id
            WHERE m.actif = TRUE
            GROUP BY m.menu_id
            ORDER BY m.created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupération d'un menu spécifique avec tous les détails
function get_menu_details($menu_id)
{
    global $pdo;

    // Infos menu
    $sql = "SELECT m.*, t.libelle as theme_nom 
            FROM menu m
            LEFT JOIN theme t ON m.theme_id = t.theme_id
            WHERE m.menu_id = :menu_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['menu_id' => $menu_id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$menu)
        return null;

    // Récupérer les plats du menu
    $sql = "SELECT p.*, GROUP_CONCAT(a.libelle SEPARATOR ', ') as allergenes
            FROM plat p
            INNER JOIN menu_plat mp ON p.plat_id = mp.plat_id
            LEFT JOIN plat_allergene pa ON p.plat_id = pa.plat_id
            LEFT JOIN allergene a ON pa.allergene_id = a.allergene_id
            WHERE mp.menu_id = :menu_id
            GROUP BY p.plat_id, p.type_plat
            ORDER BY FIELD(p.type_plat, 'entree', 'plat', 'dessert')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['menu_id' => $menu_id]);
    $menu['plats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $menu;
}

/**
 * Vérifie que le mot de passe respecte les règles de sécurité
 * Retourne un tableau d'erreurs (vide si tout est ok)
 */
function valider_password($password)
{
    // Tableau qui va stocker les erreurs
    $erreurs = [];

    // Vérifie la longueur minimum (10 caractères)
    if (strlen($password) < 10) {
        $erreurs[] = "Le mot de passe doit contenir au moins 10 caractères";
    }

    // Vérifie la présence d'au moins une majuscule
    if (!preg_match('/[A-Z]/', $password)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une majuscule";
    }

    // Vérifie la présence d'au moins une minuscule
    if (!preg_match('/[a-z]/', $password)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une minuscule";
    }

    // Vérifie la présence d'au moins un chiffre
    if (!preg_match('/[0-9]/', $password)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un chiffre";
    }

    // Vérifie la présence d'au moins un caractère spécial
    // [^a-zA-Z0-9] signifie "tout ce qui n'est pas une lettre ou un chiffre"
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un caractère spécial";
    }

    // Retourne le tableau d'erreurs (vide si tout est ok)
    return $erreurs;
}

/**
 * Vérifie si un email existe déjà en base de données
 * Retourne TRUE si l'email existe, FALSE sinon
 */
function email_existe($email)
{
    global $pdo;

    // Requête préparée pour éviter les injections SQL
    // Le :email est un paramètre qui sera remplacé par la vraie valeur
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");

    // On exécute la requête en remplaçant :email par la vraie valeur
    $stmt->execute(['email' => $email]);

    // fetchColumn() récupère la première colonne du premier résultat
    // COUNT(*) retourne le nombre de lignes trouvées
    // Si > 0, l'email existe déjà
    return $stmt->fetchColumn() > 0;
}

/**
 * Inscrit un nouvel utilisateur en base de données
 * Retourne TRUE si succès, FALSE sinon
 */
function inscrire_utilisateur($nom, $prenom, $email, $password, $telephone, $ville, $pays, $adresse)
{
    global $pdo;

    // Hache le mot de passe avant de le stocker
    // PASSWORD_BCRYPT est l'algorithme de hachage recommandé
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Requête préparée pour insérer le nouvel utilisateur
    $sql = "INSERT INTO utilisateur 
            (nom, prenom, email, password, telephone, ville, pays, adresse_postale, role_id) 
            VALUES 
            (:nom, :prenom, :email, :password, :telephone, :ville, :pays, :adresse, 1)";
    // role_id = 1 correspond au rôle 'utilisateur' (défini dans nos données de test)

    $stmt = $pdo->prepare($sql);

    // On exécute en remplaçant tous les paramètres par les vraies valeurs
    return $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'password' => $password_hash, // On stocke le hash, jamais le vrai mot de passe
        'telephone' => $telephone,
        'ville' => $ville,
        'pays' => $pays,
        'adresse' => $adresse
    ]);
}

/**
 * Cherche un utilisateur par son email en base de données
 * Retourne les données de l'utilisateur ou FALSE si non trouvé
 */
function get_utilisateur_par_email($email) {
    global $pdo;

    // On fait une jointure avec la table role pour récupérer
    // le libellé du rôle (ex: "admin") en plus des infos utilisateur
    $sql = "SELECT u.*, r.libelle as role_libelle 
            FROM utilisateur u
            INNER JOIN role r ON u.role_id = r.role_id
            WHERE u.email = :email
            AND u.actif = TRUE";
    // AND u.actif = TRUE → refuse la connexion aux comptes désactivés

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    // fetch() récupère UNE seule ligne (contrairement à fetchAll qui récupère tout)
    // Retourne FALSE si aucun utilisateur trouvé
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>