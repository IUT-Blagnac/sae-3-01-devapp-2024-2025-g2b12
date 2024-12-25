<?php
require_once("../include/Connect.inc.php");

echo "<form method='post'>";

echo "<fieldset>";
$catSelectionne = isset($_POST['idCateg_Produit']) ? htmlspecialchars($_POST['idCateg_Produit']) : '';

$pdostat = $conn->query("SELECT idCategorie, nomCategorie, idParent FROM CATEGORIE");

echo "<select name='idCateg_Produit'>";
while ($ligne = $pdostat->fetch()) {
    $optionValue = $ligne["idCategorie"] . "|" . ($ligne["idParent"] ?? "NULL");
    $isSelected = ($catSelectionne == $optionValue) ? " selected='selected'" : "";
    echo "<option value='" . htmlspecialchars($optionValue) . "' $isSelected>" . htmlspecialchars($ligne["nomCategorie"]) . "</option>";
}
echo "</select><br/><br/>";
echo "<input type='submit' name='Afficher' value='Afficher'/><br/><br/>";
echo "</fieldset>";
echo "</form>";

if (isset($_POST['idCateg_Produit']) && isset($_POST['Afficher'])) {
    if (strpos($_POST['idCateg_Produit'], "|") !== false) {
        list($idCategorie, $idParent) = explode("|", $_POST['idCateg_Produit']);
        $idParent = ($idParent === "NULL") ? null : $idParent;
 

    if ($idParent === null) {
        $produits = $conn->prepare('SELECT * FROM CATEGORIE C, PRODUIT P WHERE :idCategorie = C.idParent AND P.idCategorie = C.idCategorie');
        $produits->execute(array('idCategorie' => $idCategorie));
        echo "<h3>Produits associes a la categorie parent :</h3>";
    } else {
        $produits = $conn->prepare('SELECT * FROM PRODUIT WHERE idCategorie = :idCategorie');
        $produits->execute(array('idCategorie' => $idCategorie));
        echo "<h3>Produits associes a la categorie enfant :</h3>";
    }

    if ($produits->rowCount() > 0) {
        while ($produit = $produits->fetch(PDO::FETCH_ASSOC)) {
            echo "<a href='../index.php?idProduit=" . htmlspecialchars($produit["idProduit"]) . "'>" . htmlspecialchars($produit["idProduit"]) . "</a><br>";
        }
    } else {
        echo "Aucun produit trouve.";
    }

    $produits->closeCursor();
    }
}
?>