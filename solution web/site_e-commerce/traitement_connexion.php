<?php
session_start();
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'creer_compte') {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $numTel = $_POST['numTel'];

        // Validation des entrées
        if (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $login)) {
            header("Location: creer_compte.php?erreur=Login invalide&login=$login&email=$email&nom=$nom&prenom=$prenom&numTel=$numTel");
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: creer_compte.php?erreur=Email invalide&login=$login&email=$email&nom=$nom&prenom=$prenom&numTel=$numTel");
            exit();
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/', $password)) {
            header("Location: creer_compte.php?erreur=Mot de passe invalide&login=$login&email=$email&nom=$nom&prenom=$prenom&numTel=$numTel");
            exit();
        }
        if (!preg_match('/^[a-zA-Z\s]{1,50}$/', $nom)) {
            header("Location: creer_compte.php?erreur=Nom invalide&login=$login&email=$email&nom=$nom&prenom=$prenom&numTel=$numTel");
            exit();
        }
        if (!preg_match('/^[a-zA-Z\s]{1,50}$/', $prenom)) {
            header("Location: creer_compte.php?erreur=Prénom invalide&login=$login&email=$email&nom=$nom&prenom=$prenom&numTel=$numTel");
            exit();
        }
        if (!preg_match('/^[0-9\s]{10,15}$/', $numTel)) {
            header("Location: creer_compte.php?erreur=Numéro de téléphone invalide&login=$login&email=$email&nom=$nom&prenom=$prenom&numTel=$numTel");
            exit();
        }

        // Hachage du mot de passe
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Insertion des données dans la base de données
        $sql = "INSERT INTO CLIENT (loginClient, mdpClient, nomClient, prenomClient, telephoneClient, emailClient) VALUES (:login, :password, :nom, :prenom, :numTel, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':numTel', $numTel);

        if ($stmt->execute()) {
            header ("Location: connexion.php");
        } else {
            echo "Erreur: " . $stmt->errorInfo()[2];
        }
    } elseif ($action == 'connexion') {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);

        // Vérification des identifiants
        $sql = "SELECT * FROM CLIENT WHERE loginClient=:login OR emailClient=:login";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['mdpClient'])) {
                $_SESSION['user_id'] = $row['idClient'];
                $_SESSION['login'] = $row['loginClient'];

                if ($remember) {
                    setcookie('user_id', $row['idClient'], time() + 3600, "/");
                    setcookie('login', $row['loginClient'], time() + 3600, "/");
                }

                header("Location: index.php");
            } else {
                header("Location: connexion.php?erreur=Mot de passe incorrect&login=$login");
            }
        } else {
            header("Location: connexion.php?erreur=Utilisateur non trouvé&login=$login");
        }
    } elseif ($action == 'connexion_admin') {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);

        // Vérification des identifiants administrateur
        $sql = "SELECT * FROM ADMIN WHERE loginAdmin=:login OR emailAdmin=:login";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['mdpAdmin'])) {
                $_SESSION['admin_id'] = $row['idAdmin'];
                $_SESSION['admin_login'] = $row['loginAdmin'];

                if ($remember) {
                    setcookie('admin_id', $row['idAdmin'], time() + 3600, "/");
                    setcookie('admin_login', $row['loginAdmin'], time() + 3600, "/");
                }

                header("Location: admin/espace_administrateur.php");
            } else {
                header("Location: connexion_admin.php?erreur=Mot de passe incorrect&login=$login");
            }
        } else {
            header("Location: connexion_admin.php?erreur=Utilisateur non trouvé&login=$login");
        }
    }
}
?>