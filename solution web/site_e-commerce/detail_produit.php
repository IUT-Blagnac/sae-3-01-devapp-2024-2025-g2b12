<?php
session_start();
require_once('./include/head.php');
require_once("./include/Connect.inc.php");

// Partie HTML et affichage des produits

require_once('./include/header.php');
require_once('./include/menu.php');

if (isset($_GET['idProduit'])) {
    $idProduit = $_GET['idProduit'];

    // Récupérer les détails du produit
    $produit = $conn->prepare("SELECT * FROM PRODUIT WHERE idProduit = :idProduit");
    $produit->execute(['idProduit' => $idProduit]);
    $prod = $produit->fetch();

    if ($prod) {
        $imagePath = "image/produits/prod" . htmlspecialchars($prod['idProduit']) . ".png";
?>
        <style>
            .product-detail-container {
                margin-top: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                font-family: Arial, sans-serif;
            }

            .product-detail {
                display: flex;
                flex-direction: row;
                width: 80%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
                background-color: #fff;
            }

            .product-image {
                flex: 1;
                background-size: cover;
                background-position: center;
                height: 500px;
                position: relative;
            }

            .product-image::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 1px;
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(200, 200, 200, 0.75), rgba(0, 0, 0, 0));
                /* Couleurs plus claires */
            }

            .product-info {
                flex: 1;
                padding: 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .product-title {
                font-size: 2em;
                margin-bottom: 10px;
                transition: color 0.3s ease;
            }

            .product-title:hover {
                color: #007bff;
            }

            .product-description {
                font-size: 1.2em;
                margin-bottom: 20px;
                transition: color 0.3s ease;
            }

            .product-description:hover {
                color: #555;
            }

            .product-price {
                font-size: 1.5em;
                font-weight: bold;
                margin-bottom: 20px;
                color: #28a745;
            }

            .btn-custom {
                padding: 10px 20px;
                background-color: rgba(136, 172, 223);
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
                font-size: 1em;
            }

            .btn-custom:hover {
                background-color: rgb(67, 83, 107);
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }

            .quantity-selector {
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .quantity-selector select {
                width: 60px;
                padding: 5px;
                font-size: 1em;
            }

            .product-actions {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            hr.custom-hr {
                border: 0;
                height: 1px;
                background: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
                margin: 20px 0;
            }

            .product-specs {
                margin-top: 20px;
                font-size: 1.1em;
                color: #333;
            }

            .product-specs h3 {
                margin-bottom: 10px;
                font-size: 1.5em;
            }
        </style>

        <main style="margin-top:8%; margin-bottom:5%;">
            <div class="product-detail-container">
                <div class="product-detail">
                    <div class="product-image" style="background-image: url('<?php echo $imagePath; ?>');"></div>
                    <div class="product-info">
                        <div>
                            <h1 class="product-title"><?php echo htmlspecialchars($prod['nomProduit']); ?></h1>
                            <p class="product-description"><?php echo htmlspecialchars($prod['descProduit']); ?></p>
                            <p class="product-price"><?php echo htmlspecialchars($prod['prixProduit']); ?> €</p>
                            <div class="product-specs">
                                <h3>Spécifications du produit :</h3>
                                <p><?php echo htmlspecialchars($prod['specProduit']); ?></p>
                            </div>
                            <div class="quantity-selector">
                                <label for="quantity">Sélectionner la quantité :</label>
                                <select id="quantity" name="quantity" class="form-select">
                                    <?php for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-custom" onclick="ajouterAuPanier(<?php echo htmlspecialchars($prod['idProduit']); ?>)">Ajouter au panier</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr class="custom-hr"><br>
        </main>
<?php
    } else {
        echo "<h1 style='text-align: center;'>Produit non trouvé.</h1>";
    }
} else {
    echo "<h1 style='text-align: center;'>Aucun produit sélectionné.</h1>";
}
?>

<script>
    function ajouterAuPanier(idProduit) {
        const quantity = document.getElementById('quantity').value;
        fetch('ajouter_au_panier.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `idProduit=${idProduit}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Produit ajouté au panier',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    if (data.message === 'Vous devez être connecté pour ajouter un produit au panier.') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Non connecté',
                            text: data.message,
                            showCancelButton: true,
                            confirmButtonText: 'Se connecter',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'connexion.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message,
                        });
                    }
                }
            });
    }
</script>

<?php include_once('include/footer.php'); ?>