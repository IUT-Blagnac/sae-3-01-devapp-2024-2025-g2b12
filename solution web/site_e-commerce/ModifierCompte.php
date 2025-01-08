<?php

ob_start(); // Active le tampon de sortie
require_once('./include/session.php');
require_once('./include/Connect.inc.php');
require_once('./include/header.php');
require_once('./include/menu.php');

// Récupérer les informations du compte
$idClient = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM CLIENT WHERE idClient = :idClient");
$query->execute(['idClient' => $idClient]);
$client = $query->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo "Erreur : Informations du compte non trouvées.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once('./include/head.php'); ?>
    <style>
        .content {
            padding: 20px;
            margin-top: 20px;
            margin-left: 220px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 40px);
        }

        .card {
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
            position: relative;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        form {
            width: 100%;
        }

        form input[type="text"],
        form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        form input[type="text"]:focus,
        form input[type="email"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            color: #fff;
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        form input[type="submit"]:hover {
            background-color: #c82333;
            transform: scale(1.03);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php require_once('./include/sidebar_compte.php'); ?>
            <main class="content">
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <input type='text' name='nom' placeholder='Nom'
                                value='<?php echo htmlspecialchars($client['nomClient']); ?>' required>
                            <input type='text' name='prenom' placeholder='Prénom'
                                value='<?php echo htmlspecialchars($client['prenomClient']); ?>' required>
                            <input type='email' name='email' placeholder='Email'
                                value='<?php echo htmlspecialchars($client['emailClient']); ?>' required>
                            <input type='submit' name='envoyer' value='Enregistrer'>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php require_once('./include/footer.php'); ?>
</body>

<?php
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['envoyer'])) {

    $req = $conn->prepare("UPDATE CLIENT SET nomClient  = :pNomClient, prenomClient = :pPrenomClient, emailClient = :pEmailClient WHERE idClient = :pIdClient");
    $req->execute(["pNomClient" => htmlentities($_POST['nom']), "pPrenomClient" => htmlentities($_POST['prenom']), "pEmailClient" => htmlentities($_POST['email']), "pIdClient" => htmlentities($idClient)]);
    header("Location: visualiser_compte.php");
    exit();
}
?>

</html>