<?php
session_start();
require_once('./include/head.php');
require_once("./include/Connect.inc.php");

// Partie HTML et affichage des produits

require_once('./include/header.php');
require_once('./include/menu.php');

if (isset($_GET['idProduit1'], $_GET['idProduit2'])) {
    $idProduit1 = $_GET['idProduit1'];
    $idProduit2 = $_GET['idProduit2'];

    $stmtProduit = $conn->prepare("SELECT * FROM PRODUIT WHERE idProduit = :idProduit");
    $stmtVariete = $conn->prepare("SELECT * FROM VARIETE WHERE idProduit = :idProduit");

    // Récupération des données du produit 1
    $stmtProduit->execute(['idProduit' => $idProduit1]);
    $prod1 = $stmtProduit->fetch();
    $stmtVariete->execute(['idProduit' => $idProduit1]);
    $varList1 = $stmtVariete->fetchAll();

    // Récupération des données du produit 2
    $stmtProduit->execute(['idProduit' => $idProduit2]);
    $prod2 = $stmtProduit->fetch();
    $stmtVariete->execute(['idProduit' => $idProduit2]);
    $varList2 = $stmtVariete->fetchAll();

    if ($prod1 && $prod2) {
        $currentVariete1 = $varList1[0] ; 
        $imagePath1 = "image/produits/prod" . htmlspecialchars($currentVariete1['idVariete']) . ".png";
        
        $currentVariete2 = $varList2[0] ; 
        $imagePath2 = "image/produits/prod" . htmlspecialchars($currentVariete2['idVariete']) . ".png";

        $prixTotal = floatval($currentVariete1['prixVariete']) + floatval($currentVariete2['prixVariete']); 
        ?>

        <main style="margin-top:8%; margin-bottom:5%;">
            <div class="product-detail-container">
                <div class="product-detail">
                    <div class="product-image" id="product-image" style="display: flex; gap: 10px;">
                        <div class="product-image1" style="background-image: url('<?php echo $imagePath1; ?>'); width: 50%, background-size: cover; background-position: center; height: 300px;"></div> 
                        <div class="product-image2" style="background-image: url('<?php echo $imagePath2; ?>'); width: 50%, background-size: cover; background-position: center; height: 300px;"></div>
                    </div>

                    <div class="product-info">
                            <!-- Produit 1  -->
                            <div>
                                <h1 class="product-title"><?php echo htmlspecialchars($prod1['nomProduit']); ?></h1>
                                <p class="product-description"><?php echo htmlspecialchars($prod1['descProduit']); ?></p>
                                 <!-- Affichage des variétés -->
                                <div class="variete-buttons1">
                                    <?php foreach ($varList1 as $variete) { ?>
                                        <?php if(!is_null($variete['specVariete'])): ?>
                                            <button class="variete-button1" 
                                            onclick="selectVariete(1, <?php echo htmlspecialchars($variete['idVariete']); ?>, '<?php echo htmlspecialchars($variete['prixVariete']); ?>', '<?php echo htmlspecialchars($variete['specVariete']); ?>')">
                                            <?php echo htmlspecialchars($variete['specVariete']); ?> 
                                            </button>
                                        <?php endif; ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Produit 2 -->
                            <div>
                                <h1 class="product-title"><?php echo htmlspecialchars($prod2['nomProduit']); ?></h1>
                                <p class="product-description">Description: <?php echo htmlspecialchars($prod2['descProduit']); ?></p>                           
                                <div class="variete-buttons2">
                                    <?php foreach ($varList2 as $variete) { ?>
                                        <?php if(!is_null($variete['specVariete'])): ?>
                                            <button class="variete-button2" 
                                            onclick="selectVariete(2, <?php echo htmlspecialchars($variete['idVariete']); ?>, '<?php echo htmlspecialchars($variete['prixVariete']); ?>', '<?php echo htmlspecialchars($variete['specVariete']); ?>')">
                                            <?php echo htmlspecialchars($variete['specVariete']); ?> 
                                            </button>
                                        <?php endif; ?>   
                                    <?php } ?>
                                </div>
                            </div>
                        
                            <!-- Prix Total -->
                            <div>
                                <h2> Prix des produits combinés : <span id="total-price"><?php echo $prixTotal; ?></span>€</h2>
                            </div>


                        <div class="product-actions">
                            <button class="btn btn-custom" id="add-to-cart-button"
                                onclick="ajouterAuPanier(<?php echo $currentVariete1['idVariete']; ?>, <?php echo $currentVariete2['idVariete']; ?>)">Ajouter au panier</button>
                        </div>

                    </div>
                </div>
            </div>
            <br>
            <hr class="custom-hr">
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

    .product-image1 {
        flex: 1;
        background-size: cover;
        background-position: center;
        height: 500px;
        position: relative;
    }

    .product-image1::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(200, 200, 200, 0.75), rgba(0, 0, 0, 0));
        /* Couleurs plus claires */
    }

    .product-image2 {
        flex: 1;
        background-size: cover;
        background-position: center;
        height: 500px;
        position: relative;
    }

    .product-image2::after {
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

    .variete-buttons1 {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .variete-buttons2 {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .variete-button1 {
        padding: 10px 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        font-size: 1em;
    }

    .variete-button2 {
        padding: 10px 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        font-size: 1em;
    }

    .variete-button1.active {
        background-color: rgba(136, 172, 223);
        color: #fff;
        border-color: rgba(136, 172, 223);
    }

    .variete-button2.active {
        background-color: rgba(136, 172, 223);
        color: #fff;
        border-color: rgba(136, 172, 223);
    }

    .variete-button1:hover {
        background-color: #e9ecef;
        color: #007bff;
    }

    .variete-button2:hover {
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
    let currentVarieteId1 = <?php echo htmlspecialchars($currentVariete1['idVariete']); ?>;
    let currentVarieteId2 = <?php echo htmlspecialchars($currentVariete2['idVariete']); ?>;
    let currentPrix1 = parseFloat(<?php echo htmlspecialchars($currentVariete1['prixVariete']); ?>);
    let currentPrix2 = parseFloat(<?php echo htmlspecialchars($currentVariete2['prixVariete']); ?>);

    function selectVariete(productNumber, idVariete, prixVariete, specVariete) {
       
        if (productNumber === 1) {
            currentVarieteId1 = idVariete;
            currentPrix1 = parseFloat(prixVariete);
            document.querySelector('.product-image1').style.backgroundImage = 'url(image/produits/prod' + idVariete + '.png)';

            // Mettre à jour les boutons pour le produit 1
            const buttons = document.querySelectorAll('.variete-button1');
            buttons.forEach(button => {
                button.classList.remove('active');
                if (button.innerText === specVariete) {
                    button.classList.add('active');
                }
            });
        
        } else if (productNumber === 2) {
            currentVarieteId2 = idVariete;
            currentPrix2 = parseFloat(prixVariete);
            document.querySelector('.product-image2').style.backgroundImage = 'url(image/produits/prod' + idVariete + '.png)';

            // Mettre à jour les boutons pour le produit 2
            const buttons = document.querySelectorAll('.variete-button2');
            buttons.forEach(button => {
                button.classList.remove('active');
                if (button.innerText === specVariete) {
                    button.classList.add('active');
                }
            });
        }

        updateTotalPrice();
    }

    function updateTotalPrice() {
        const totalPrice = (currentPrix1 + currentPrix2).toFixed(2);
        document.getElementById('total-price').innerText = totalPrice;
        document.getElementById('add-to-cart-button').setAttribute('onclick', `ajouterAuPanier(${currentVarieteId1}, ${currentVarieteId2})`);
    }

    function ajouterAuPanier(idVariete1, idVariete2) {
        fetch('ajouter_au_panier.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `idVariete1=${idVariete1}&idVariete2=${idVariete2}`
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

    // Initialiser les boutons de variété comme actifs
    document.addEventListener('DOMContentLoaded', () => {
        // Produit 1
        const buttons1 = document.querySelector('.variete-button1');
        if (buttons1) {
            buttons1.classList.add('active');
        }

        // Produit 2
        const buttons2 = document.querySelector('.variete-button2');
        if (buttons2) {
            buttons2.classList.add('active');
        }

        // Mettre à jour le prix total au chargement initial
        updateTotalPrice();
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