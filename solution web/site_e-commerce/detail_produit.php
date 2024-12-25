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

    // Récupérer les variétés du produit
    $varietes = $conn->prepare("SELECT * FROM VARIETE WHERE idProduit = :idProduit");
    $varietes->execute(['idProduit' => $idProduit]);
    $varList = $varietes->fetchAll();

    if ($prod && $varList) {
        // Par défaut, afficher la première variété
        $currentVariete = $varList[0];
        $imagePath = "image/produits/prod" . htmlspecialchars($currentVariete['idVariete']) . ".png";

        ?>

        <main style="margin-top:8%; margin-bottom:5%;">
            <div class="product-detail-container">
                <div class="product-detail">
                    <div class="product-image" id="product-image" style="background-image: url('<?php echo $imagePath; ?>');">
                    </div>
                    <div class="product-info">
                        <div>
                            <h1 class="product-title"><?php echo htmlspecialchars($prod['nomProduit']); ?></h1>
                            <p class="product-description"><?php echo htmlspecialchars($prod['descProduit']); ?></p>
                            <p class="product-price" id="product-price">
                                <?php echo htmlspecialchars($currentVariete['prixVariete']); ?> €
                            </p>
                            <div class="quantity-selector">
                                <label for="quantity">Sélectionner la quantité :</label>
                                <select id="quantity" name="quantity" class="form-select">
                                    <?php for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="variete-buttons">
                                <?php foreach ($varList as $variete) { ?>
                                    <?php if (!is_null($variete['specVariete'])): ?>
                                        <button class="variete-button"
                                            onclick="selectVariete(<?php echo htmlspecialchars($variete['idVariete']); ?>, '<?php echo htmlspecialchars($variete['prixVariete']); ?>', '<?php echo htmlspecialchars($variete['specVariete']); ?>')">
                                            <?php echo htmlspecialchars($variete['specVariete']); ?>
                                        </button>
                                    <?php endif; ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-custom" id="add-to-cart-button"
                                onclick="ajouterAuPanier(<?php echo htmlspecialchars($currentVariete['idVariete']); ?>)">Ajouter
                                au panier</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr class="custom-hr"><br>
            <center>
                <div class="avis-container">
                    <div class="avis-form">
                        <h3>Écrire un avis</h3>
                        <form method="POST" id="avisForm">
                            <div class="star-rating">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>
                            <input type="hidden" id="noteAvis" name="noteAvis" value="0">
                            <textarea id="contenuAvis" name="ContenuAvis" rows="4" placeholder="Votre avis ici..."
                                class="avis-textarea"></textarea><br>
                            <button type="submit" class="btn btn-custom" name="Soumettre">Soumettre l'avis</button>
                        </form>
                    </div>
                </div>
            </center>

            <?php
            if (isset($_POST["ContenuAvis"], $_POST["noteAvis"], $_POST["Soumettre"], $_SESSION["user_id"])) {
                $userId = $_SESSION["user_id"];
                $noteAvis = (int) $_POST["noteAvis"]; // Note soumise par l'utilisateur
    
                // Vérifier si une note valide a été donnée
                if ($noteAvis < 1 || $noteAvis > 5) {
                    echo "<p style='color: red;'>Veuillez donner une note entre 1 et 5 étoiles.</p>";
                } else {
                    $avisQuery = $conn->prepare("SELECT * FROM AVIS WHERE idClient = :idClient AND idProduit = :idProduit");
                    $avisQuery->execute(['idClient' => $userId, 'idProduit' => $idProduit]);
                    $existingAvis = $avisQuery->fetch();

                    if ($existingAvis) {
                        echo "<p style='color: red;'>Vous avez déjà laissé un avis pour ce produit.</p>";
                    } else {
                        $req = $conn->prepare("INSERT INTO AVIS(idClient, idProduit, noteAvis, commentaireAvis, dateAvis, reponseAvis, dateReponseAvis) 
                                   VALUES(:pIdClient, :pIdProduit, :pNoteAvis, :pCommentaireAvis, CURDATE(), :pReponseAvis, :pDateReponseAvis)");
                        $req->execute([
                            "pIdClient" => $userId,
                            "pIdProduit" => $idProduit,
                            "pNoteAvis" => $noteAvis,
                            "pCommentaireAvis" => $_POST["ContenuAvis"],
                            "pReponseAvis" => NULL,
                            "pDateReponseAvis" => NULL
                        ]);
                        echo "<p style='color: green;'>Votre avis a été ajouté avec succès.</p>";
                    }
                }
            }
            ?>

            <!-- Affichage des avis -->
            <div id="avisList">
                <?php
                $avisQuery = $conn->prepare("SELECT * FROM AVIS WHERE idProduit = :idProduit");
                $avisQuery->execute(['idProduit' => $idProduit]);
                $avisList = $avisQuery->fetchAll();

                if (count($avisList) > 0) {
                    foreach ($avisList as $avis) {
                        echo "<div class='avis-item'>";
                        echo "<p><strong>" . htmlspecialchars($avis['dateAvis']) . " / " 
                        . htmlspecialchars($avis['idClient']) . " :</strong> " 
                        . htmlspecialchars($avis['noteAvis'])
                        . htmlspecialchars($avis['commentaireAvis']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucun avis pour ce produit.</p>";
                }
                ?>
            </div>
        </main>

        <?php
    } else {
        echo "<h1 style='text-align: center;'>Produit non trouvé.</h1>";
    }
} else {
    echo "<h1 style='text-align: center;'>Aucun produit sélectionné.</h1>";
}
?>

<style>
    .star-rating {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
        cursor: pointer;
    }

    .star {
        font-size: 2em;
        color: #ddd;
        /* Couleur des étoiles non sélectionnées */
        transition: color 0.3s ease;
    }

    .star.selected {
        color: #ffcc00;
        /* Couleur des étoiles sélectionnées */
    }

    .star:hover {
        color: #ffcc00;
    }

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

    .variete-buttons {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .variete-button {
        padding: 10px 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        font-size: 1em;
    }

    .variete-button.active {
        background-color: rgba(136, 172, 223);
        color: #fff;
        border-color: rgba(136, 172, 223);
    }

    .variete-button:hover {
        background-color: #e9ecef;
        color: #007bff;
    }

    hr.custom-hr {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
        margin: 20px 0;
    }
</style>
<script>
    let currentVarieteId = <?php echo htmlspecialchars($currentVariete['idVariete']); ?>;

    function selectVariete(idVariete, prixVariete, specVariete) {
        currentVarieteId = idVariete;
        document.getElementById('product-price').innerText = prixVariete + ' €';
        document.getElementById('product-image').style.backgroundImage = 'url(image/produits/prod' + idVariete + '.png)';
        document.getElementById('add-to-cart-button').setAttribute('onclick', 'ajouterAuPanier(' + idVariete + ')');

        // Mettre à jour les boutons de variété pour refléter la sélection actuelle
        const buttons = document.querySelectorAll('.variete-button');
        buttons.forEach(button => {
            button.classList.remove('active');
            if (button.innerText === specVariete) {
                button.classList.add('active');
            }
        });
    }

    function ajouterAuPanier(idVariete) {
        const quantity = document.getElementById('quantity').value;
        fetch('ajouter_au_panier.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `idVariete=${idVariete}&quantity=${quantity}`
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

    // Initialiser le premier bouton de variété comme actif
    document.addEventListener('DOMContentLoaded', () => {
        const firstButton = document.querySelector('.variete-button');
        if (firstButton) {
            firstButton.classList.add('active');
        }
    });




</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stars = document.querySelectorAll('.star');
        const noteAvisInput = document.getElementById('noteAvis');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-value');
                noteAvisInput.value = rating;

                // Mettre à jour les étoiles sélectionnées
                updateStars(rating);
            });

            star.addEventListener('mouseover', () => {
                const rating = star.getAttribute('data-value');
                highlightStars(rating); // Montre temporairement la sélection
            });

            star.addEventListener('mouseout', () => {
                // Revenir à la sélection réelle après survol
                updateStars(noteAvisInput.value);
            });
        });

        function updateStars(rating) {
            stars.forEach(star => {
                star.classList.remove('selected');
                if (parseInt(star.getAttribute('data-value')) <= parseInt(rating)) {
                    star.classList.add('selected');
                }
            });
        }

        function highlightStars(rating) {
            stars.forEach(star => {
                star.classList.remove('selected');
                if (parseInt(star.getAttribute('data-value')) <= parseInt(rating)) {
                    star.classList.add('selected');
                }
            });
        }
    });
</script>

<?php include_once('include/footer.php'); ?>