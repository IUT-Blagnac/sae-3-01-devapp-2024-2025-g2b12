<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<!-- Partie récupération dans la bd -->
<?php
ob_start();
session_start();
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les produits du panier du client avec leurs variétés
$sql = "SELECT p.idProduit, p.nomProduit, v.idVariete, v.specVariete, v.prixVariete, e.qteEnregistree 
        FROM ENREGISTRER e 
        JOIN VARIETE v ON e.idVariete = v.idVariete
        JOIN PRODUIT p ON v.idProduit = p.idProduit 
        WHERE e.idClient = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($produits as $prod) {
    $total += $prod['prixVariete'] * $prod['qteEnregistree'];
}
?>
<!-- Partie BODY -->
<?php require_once('./include/header.php'); ?>
<?php require_once("./include/menu.php"); ?>

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <!-- partie contenu principal -->
        <main class="container" style="margin-top:8%; margin-bottom:5%;">
            <h2 class="text-center">Mon Panier</h2>
            <?php
            if (!empty($produits)) {
                echo "<div class='col-md-8 mx-auto'>";
                echo "<div class='card mb-4 shadow-sm'>";
                echo "<div class='card-body'>";
                foreach ($produits as $prod) {
                    $imagePath = "image/produits/prod" . htmlspecialchars($prod['idVariete']) . ".png"; // Chemin de l'image
                    echo "<div class='row mb-3'>";
                    echo "<div class='col-md-4'>";
                    echo "<div class='product-image-placeholder'>";
                    echo "<img src='" . $imagePath . "' alt='Image du produit' style='width: 100%; height: 100%; object-fit: cover;'>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='col-md-8 product-container'>";
                    echo "<div>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($prod['nomProduit']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($prod['prixVariete']) . " €</p>";
                    echo "<p class='card-text'>" . htmlspecialchars($prod['specVariete']) . "</p>";
                    echo "<div class='d-flex align-items-center'>";
                    echo "<p class='card-text mb-0'>Quantité: <span class='quantity'>" . htmlspecialchars($prod['qteEnregistree']) . "</span> &nbsp;&nbsp; </p>";
                    echo "<button class='btn btn-custom btn-custom-dark btn-sm decrease' data-id='" . htmlspecialchars($prod['idVariete']) . "'>-</button>";
                    echo "<button class='btn btn-custom btn-custom-light btn-sm increase' data-id='" . htmlspecialchars($prod['idVariete']) . "'>+</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='button-container'>";
                    echo "<button class='btn btn-custom btn-danger btn-sm delete-button' data-variete-id='" . htmlspecialchars($prod['idVariete']) . "'>Supprimer</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<hr>";
                }
                if (isset($_POST['NbPts'])) {
                    $NbPts = trim($_POST['NbPts']);
            
                    if (is_numeric($NbPts) && $NbPts > 0) {
                        $req = $conn->prepare("SELECT * FROM CLIENT WHERE idClient = :pIdClient");
                        $req->execute(["pIdClient" => htmlentities($user_id)]);
                        $NbPointsF = $req->fetch(PDO::FETCH_ASSOC);

                        if ($NbPointsF) {
                            if ($NbPts > $NbPointsF["nbPointsClient"]) {
                                header("Location: visualiser_compte.php?Pts=oui&error=insufficient_points");
                                exit();
                            } else {
                                $total = $total - ($NbPts / 10);
                                if($total < 0){
                                    $total = 0;
                                }
                            }
                        }
                    } else {
                        header("Location: visualiser_compte.php?Pts=oui&error=invalid_points");
                        exit();
                    }
                }
                echo "<div class='text-right'><strong>Prix Total: " . htmlspecialchars($total) . " €</strong></div>";
                echo "<a href='visualiser_compte.php?Pts=oui'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-gift' viewBox='0 0 16 16'>
                <path d='M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A3 3 0 0 1 3 2.506zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43zM9 3h2.932l.023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0zM1 4v2h6V4zm8 0v2h6V4zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5z'/>
                </svg></a>";
                echo "<div class='text-center mt-3'>";
                echo "<a href='commander.php?Total=" . $total . "' class='btn btn-custom'>Commander</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<br><hr>";
            } else {
                echo "<p class='text-center'>Votre panier est vide.</p>";
            }
            ?>
        </main>
    </div>
</div>

<style>
    .btn-custom {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        font-size: 0.9em;
        margin: 0 5px;
    }

    .btn-custom {
        background-color: rgba(136, 172, 223);
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-custom:hover {
        background-color: rgb(67, 83, 107);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-custom-light {
        background-color: rgb(122, 122, 122);
        color: #fff;
    }

    .btn-custom-light:hover {
        background-color: rgb(136, 172, 223, 255);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-custom-dark {
        background-color: rgb(39, 39, 39);
        color: #fff;
    }

    .btn-custom-dark:hover {
        background-color: rgba(136, 172, 223, 255);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-danger {
        background-color: red;
        color: white;
    }

    .btn-danger:hover {
        background-color: darkred;
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .product-image-placeholder {
        width: 100%;
        height: 200px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 1.5em;
    }

    .product-image-placeholder::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 5px;
        /* Largeur de la barre */
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(200, 200, 200, 0.75), rgba(0, 0, 0, 0));
        /* Couleurs plus claires */
    }

    .product-container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
</style>

<script>
    document.querySelectorAll('.increase').forEach(button => {
        button.addEventListener('click', function () {
            updateQuantity(this.dataset.id, 'increase');
        });
    });

    document.querySelectorAll('.decrease').forEach(button => {
        button.addEventListener('click', function () {
            updateQuantity(this.dataset.id, 'decrease');
        });
    });

    function updateQuantity(variete_id, action) {
        fetch('update_quantity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `variete_id=${variete_id}&action=${action}`
        })
            .then(response => response.text())
            .then(data => {
                location.reload();
            });
    }

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const variete_id = this.getAttribute('data-variete-id');
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_article.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `variete_id=${variete_id}`
                    })
                        .then(response => response.text())
                        .then(data => {
                            Swal.fire(
                                'Supprimé!',
                                'L\'article a été supprimé.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        });
                }
            });
        });
    });
</script>

<?php include_once('include/footer.php'); ?>