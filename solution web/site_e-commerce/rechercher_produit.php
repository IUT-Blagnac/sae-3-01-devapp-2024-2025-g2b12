<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>

<?php require_once('./include/Connect.inc.php'); ?>

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- partie contenu principal -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto;">
            <div class="search-popup.is-visible" style="margin-top: 20px;">
                <!-- Bouton Retour -->
                <a href="index.php" class="btn btn-secondary retour-btn">Retour</a>

                <!-- Formulaire de recherche -->
                <div class="search-popup-container">
                    <form role="search" method="post" class="search-form">
                        <input type="search" id="search-form" class="search-field" placeholder="Type and press enter"
                            value="<?= isset($_POST['s']) ? htmlentities($_POST['s']) : '' ?>" name="s">
                        <button type="submit" name="Submit" class="search-submit">
                            <svg class="search-icon">
                                <use xlink:href="#search"></use>
                            </svg>
                        </button>
                    </form>

                    <!-- Liste des catégories -->
                    <h5 class="cat-list-title">Browse Categories</h5>
                    <ul class="cat-list">
                        <li class="cat-list-item"><a href="#" title="Mobile Phones">Mobile Phones</a></li>
                        <li class="cat-list-item"><a href="#" title="Smart Watches">Smart Watches</a></li>
                        <li class="cat-list-item"><a href="#" title="Headphones">Headphones</a></li>
                        <li class="cat-list-item"><a href="#" title="Accessories">Accessories</a></li>
                        <li class="cat-list-item"><a href="#" title="Monitors">Monitors</a></li>
                        <li class="cat-list-item"><a href="#" title="Speakers">Speakers</a></li>
                        <li class="cat-list-item"><a href="#" title="Memory Cards">Memory Cards</a></li>
                    </ul>

                    <!-- Résultats de la recherche -->
                    <div class="results">
                        <?php
                        if (isset($_POST["s"]) && isset($_POST["Submit"])) {
                            $pdostat = $conn->prepare("SELECT * FROM PRODUIT WHERE nomProduit LIKE :nomP");
                            $pdostat->execute([':nomP' => '%' . htmlentities($_POST['s']) . '%']);
                            echo "<div class='row'>";
                            while ($produits = $pdostat->fetch(PDO::FETCH_ASSOC)) {
                                echo "<div class='col-md-4'>";
                                echo "<div class='card mb-4 shadow-sm'>";
                                echo "<div class='card-img-top' style='height: 200px; background-color: #f0f0f0;'></div>";
                                echo "<div class='card-body'>";
                                echo "<p>" . htmlspecialchars($produits['nomProduit']) . "<p>";
                                echo "<p>" . htmlspecialchars($produits['prixProduit']) . "<p>";
                                echo "<p>" . htmlspecialchars($produits['specProduit']) . "<p>";
                                echo "<button class='btn btn-custom' onclick='ajouterAuPanier(" . htmlspecialchars($produits['idProduit']) . ")'>Ajouter au panier</button>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    function ajouterAuPanier(idProduit) {
        fetch('ajouter_au_panier.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `idProduit=${idProduit}`
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

<style>
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
    /* Bouton avec la loupe */
    .search-submit {
        position: absolute;
        right: 10px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .search-icon {
        width: 25px; /* Taille proportionnellement plus grande */
        height: 25px;
        fill: #aaa; /* Couleur initiale de la loupe */
        transition: fill 0.3s ease;
    }

    .search-field {
        width: 100%;
        padding: 15px 45px 15px 20px; /* Plus de padding pour rendre la barre plus grande */
        font-size: 18px; /* Texte légèrement agrandi */
        border: 2px solid #ccc;
        border-radius: 30px; /* Coins arrondis plus doux */
        outline: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }
</style>