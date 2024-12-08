<?php

// Plus d'information ici : https://fakerphp.github.io/
// Dans le r�pertoire o� on met ce script, il faut lancer, depuis la console SSH : composer require fakerphp/faker
// Cela installe un r�pertoire vendor avec le package fakerphp, on peut d�sormais l'utiliser 
require_once('/home/R2024SAE3002/vendor/autoload.php'); // pour charger automatiquement les classes du Vendor dans notre script PHP
// on cr�e un objet $faker 'version fran�aise' (pour les noms de famille, ...)
$faker = Faker\Factory::create('fr_FR');
// on g�n�re un faux nom 'fran�ais'
//echo $faker->name()."</BR>";
//echo $faker->email()."</BR>";

require_once("../include/Connect.inc.php");

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion Client -------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['Valider1'])) {
   
    $fp = fopen('file.csv','a');
    fputcsv($fp,array('Login','Email','Mot de passe'), ';');
    for ($i = 0; $i < 50; $i++) {
        $login = $faker->userName() ;
        $mdp = $faker->password() ;
        $hashMdp = password_hash($mdp, PASSWORD_BCRYPT); 
        $nomComplet = $faker->name(); 
        $split = explode(' ', $nomComplet); 
        $nom = $split[0];
        $prenom = implode(' ', array_slice($split, 1));
        $telephone = $faker->unique()->numerify('07########') ;
        $email = $faker->email() ;

        echo $login . "</BR>";
        echo $mdp . "</BR>";
        echo $hashMdp . "</BR>";
        echo $nom . "</BR>";
        echo $prenom . "</BR>";
        echo $telephone . "</BR>";
        echo $email . "</BR>";
        echo "</BR>";

        $conn->beginTransaction();
        $client = $conn->prepare('INSERT INTO CLIENT (loginClient, mdpClient, nomClient, prenomClient, telephoneClient, emailClient) 
                                VALUES(:loginClient, :mdpClient, :nomClient, :prenomClient, :telephoneClient, :emailClient)');
        $client->execute(array(
            'loginClient' => $login,
            'mdpClient' => $hashMdp,
            'nomClient' => $nom, 
            'prenomClient' => $prenom,
            'telephoneClient' => $telephone, 
            'emailClient' => $email
        ));
        $conn->commit();

        fputcsv($fp,array($login, $email, $mdp), ';');
    }
    fclose($fp);
}

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion Adresse ------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['Valider2'])) {
    for($i = 0; $i < 50; $i++){
        $Adresse = $faker->streetAddress(); 
        if(strpos($Adresse, ',') != false){
            $split = explode(',', $Adresse); 
            $Num = $split[0];
            $Rue = implode(' ', array_slice($split, 1));
        } else {
            $NewNum=$faker->randomNumber(2);
            $Num = $NewNum ;
            $Rue = $Adresse ; 
        }
        $CodePostale = $faker-> postcode();
        $ville = $faker->city(); 
        //$pays = $faker-> country();
        $pays = "France" ;

        echo $Num . "</BR>";
        echo $Rue . "</BR>";
        echo $CodePostale . "</BR>";
        echo $ville . "</BR>";
        echo $pays . "</BR>";
        echo "</BR>";

        $conn->beginTransaction();
        $adresse = $conn->prepare('INSERT INTO ADRESSE (numRueAdresse, rueAdresse, cdPostalAdresse, villeAdresse, paysAdresse) 
                                 VALUES(:numRueAdresse, :rueAdresse, :cdPostalAdresse, :villeAdresse, :paysAdresse)');
        $adresse->execute(array(
            'numRueAdresse' => $Num,
            'rueAdresse' => $Rue,
            'cdPostalAdresse' => $CodePostale, 
            'villeAdresse' => $ville,
            'paysAdresse' => $pays
        ));
        $conn->commit();
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion Avis ---------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['Valider3'])) {
    for($i = 0; $i < 20; $i++){
        $idClient = $faker->numberBetween(547, 596);
        $idProduit = $faker->numberBetween(1, 114); 
        $noteAvis = $faker->numberBetween(1, 5);

        $dateAvis = $faker->dateTimeBetween($dateCommande, '2024-12-31');
        $dateReponseAvis = $faker->dateTimeBetween($dateAvis, '2024-12-31');
        $dateAvis = $dateAvis->format('Y-m-d');
        $dateReponseAvis = $dateReponseAvis->format('Y-m-d');
       
        echo $idClient . "</BR>" ; 
        echo $idProduit . "</BR>" ; 
        echo $noteAvis . "</BR>" ; 
        echo $dateAvis . "</BR>" ; 
        echo $dateReponseAvis . "</BR>" ; 
        echo "</BR>" ;

        $conn->beginTransaction();
        $adresse = $conn->prepare('INSERT INTO AVIS (idClient, idProduit, noteAvis, dateAvis, dateReponseAvis) 
                                 VALUES(:idClient, :idProduit, :noteAvis, :dateAvis, :dateReponseAvis)');
        $adresse->execute(array(
            'idClient' => $idClient,
            'idProduit' => $idProduit,
            'noteAvis' => $noteAvis,
            'dateAvis' => $dateAvis, 
            'dateReponseAvis' => $dateReponseAvis
        ));
        $conn->commit();
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion Commande -----------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['Valider4'])) {
    for ($i = 0; $i < 1; $i++) {
        $dateCommande = $faker->dateTimeBetween('2024-01-01', '2024-12-31')->format('d/m/Y'); 

    // Mode de Livraison 
        $modeLivraison = $faker->randomElement(['DOMICILE', 'RELAIS']);
        // Domicile :
        $Adresse = $faker->streetAddress(); 
        if(strpos($Adresse, ',') != false){
            $split = explode(',', $Adresse); 
            $Num = $split[0];
            $Rue = implode(' ', array_slice($split, 1));
        } else {
            $NewNum=$faker->randomNumber(2);
            $Num = $NewNum ;
            $Rue = $Adresse ; 
        }
        $CodePostale = $faker-> postcode();
        $ville = $faker->city(); 
        $pays = "France" ;

        // Point Relais : 
        $nomRelais = "Point relais de " . $faker->company() ; 
        $AdressePR = $faker->streetAddress(); 
        if(strpos($AdressePR, ',') != false){
            $split = explode(',', $AdressePR); 
            $NumRueRelais = $split[0];
            $RueRelais = implode(' ', array_slice($split, 1));
        } else {
            $NewNum=$faker->randomNumber(2);
            $NumRueRelais = $NewNum ;
            $RueRelais = $AdressePR ; 
        }
        $CodePostalPR = $faker-> postcode();
        $villeRelais = $faker->city(); 

    // Mode de Paiement
        $modePaiement = $faker->randomElement(['PAYPAL', 'CB', 'VIREMENT']); 
        
        $numPaypal = $modePaiement === 'PAYPAL' ? $faker->numberBetween(1000000000, 9999999999) : null;
        //$numPaypal = $faker-> numberBetween(1, 100);
       
        $numVirement = $modePaiement === 'VIREMENT' ? $faker->numberBetween(1000000000, 9999999999) : null;
        //$numVirement = $faker->numberBetween(1, 100); 
        
        //Carte Bancaire
        //$numCB = $faker->creditCardNumber();
        //$dateExpCB = $faker->creditCardExpirationDateString();
        //if (strlen($numCB) < 16) {
        //    $numCB = str_pad($numCB, 16, $faker->randomNumber(1), STR_PAD_RIGHT);
        //}   
        //$numCB = substr($numCB, 0, 16); 

        $numCB = $modePaiement === 'CB' ? substr(str_pad($faker->creditCardNumber(), 16, '0', STR_PAD_RIGHT), 0, 16) : null;
        $dateExpCB = $modePaiement === 'CB' ? $faker->creditCardExpirationDateString() : null;
    
        $idClient = $faker->numberBetween(647, 696); 

        // Commander 
        $idProduit = $faker->numberBetween(1, 114); 
        $quantiteCommande = $faker->numberBetween(1, 3);

        echo "date de Commande : " . $dateCommande . "</BR>";
        echo "mode de Livraison : " . $modeLivraison . "</BR>";
        echo "mode de Paiement : " . $modePaiement . "</BR>";
        echo "idClient : " . $idClient . "</BR>";

         try {
            $conn->beginTransaction();

            // AVIS
            // $noteAvis = $faker->numberBetween(1, 5);
            // $dateAvis = $faker->dateTimeBetween($dateCommande, '2024-12-31');
            // $dateReponseAvis = $faker->dateTimeBetween($dateAvis, '2024-12-31');
            // $dateAvis = $dateAvis->format('Y-m-d');
            // $dateReponseAvis = $dateReponseAvis->format('Y-m-d'); 
            // $avis = $conn->prepare('INSERT INTO AVIS (idClient, idProduit, noteAvis, dateAvis, dateReponseAvis) 
            //                     VALUES(:idClient, :idProduit, :noteAvis, :dateAvis, :dateReponseAvis)');
            // $avis->execute(array(
            //     'idClient' => $idClient,
            //     'idProduit' => $idProduit,
            //     'noteAvis' => $noteAvis,
            //     'dateAvis' => $dateAvis, 
            //     'dateReponseAvis' => $dateReponseAvis
            // ));

            if ($modePaiement == 'CB'){
                echo $numCB . "</BR>" ;
                $CB = $conn->prepare('INSERT INTO CARTE_BANCAIRE (numCB, dateExpCB) VALUES(:numCB, :dateExpCB)');
                $CB->execute(array(
                'numCB' => $numCB,
                'dateExpCB' => $dateExpCB
                ));
            }

         if($modeLivraison == 'DOMICILE'){
             $adresse = $conn->prepare('INSERT INTO ADRESSE (numRueAdresse, rueAdresse, cdPostalAdresse, villeAdresse, paysAdresse) 
                                      VALUES(:numRueAdresse, :rueAdresse, :cdPostalAdresse, :villeAdresse, :paysAdresse)');
             $adresse->execute(array(
                 'numRueAdresse' => $Num,
                 'rueAdresse' => $Rue,
                 'cdPostalAdresse' => $CodePostale, 
                 'villeAdresse' => $ville,
                 'paysAdresse' => $pays
             ));
             $idAdresse = $conn->lastInsertId();
             echo "idAdresse : " . $idAdresse . "</BR>" ; 
         } else if ($modeLivraison == 'RELAIS'){
            $pointRelais = $conn->prepare('INSERT INTO POINT_RELAIS (nomRelais, numRueRelais, rueRelais, cdPostalRelais, villeRelais, paysRelais) 
                                    VALUES(:nomRelais, :numRueRelais, :rueRelais, :cdPostalRelais, :villeRelais, :paysRelais)');
            $pointRelais->execute(array(
                'nomRelais' => $nomRelais,
                'numRueRelais' => $NumRueRelais,
                'rueRelais' => $RueRelais,
                'cdPostalRelais' => $CodePostalPR,
                'villeRelais' => $villeRelais, 
                'paysRelais' => $pays
            ));
            $idRelais = $conn->lastInsertId();
            echo "idRelais : " . $idRelais . "</BR>" ;
         }
         echo "quantiteCommande : " . $quantiteCommande . "</BR>" ; 
         echo "idProduit : " . $idProduit . "</BR>";
         echo "</BR>";

        $commande = $conn->prepare('INSERT INTO COMMANDE (dateCommande, modeLivraison, modePaiement, numPaypal, numVirement, idClient, idAdresse, idRelais, numCB) 
                                  VALUES(:dateCommande, :modeLivraison, :modePaiement, :numPaypal, :numVirement, :idClient, :idAdresse, :idRelais, :numCB)');
        $commande->execute(array(
             'dateCommande' => $dateCommande,
             'modeLivraison' => $modeLivraison,
             'modePaiement' => $modePaiement, 
             'numPaypal' => $numPaypal,
             'numVirement' => $numVirement,
             'idClient' => $idClient,
             'idAdresse' => $idAdresse ?? null,
             'idRelais' => $idRelais ?? null,
             'numCB' => $numCB
         ));
         $idCommande = $conn->lastInsertId();
         $commander = $conn->prepare('INSERT INTO COMMANDER (idCommande, idProduit, qteCommandee) 
         VALUES(:idCommande, :idProduit, :qteCommandee)');
         $commander->execute(array(
            'idCommande' => $idCommande,
            'idProduit' => $idProduit,
            'qteCommandee' => $quantiteCommande
        ));
        $conn->commit();
    } catch(Exception $e){
        $conn->rollBack();
        echo "Erreur : " . $e->getMessage(); 
    }     
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion Point Relais -------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------
if (isset($_POST['Valider5'])) {
    for($i = 0; $i < 1; $i++){
        $nomRelais = "Point relais de " . $faker->company() ; 
        $AdressePR = $faker->streetAddress(); 
        if(strpos($AdressePR, ',') != false){
            $split = explode(',', $AdressePR); 
            $NumRueRelais = $split[0];
            $RueRelais = implode(' ', array_slice($split, 1));
        } else {
            $NewNum=$faker->randomNumber(2);
            $NumRueRelais = $NewNum ;
            $RueRelais = $AdressePR ; 
        }
        $CodePostalPR = $faker-> postcode();
        $villeRelais = $faker->city(); 
        $paysRelais = "France" ;

        echo $nomRelais . "</BR>" ; 
        echo $NumRueRelais . "</BR>";
        echo $RueRelais . "</BR>";
        echo $CodePostalPR . "</BR>";
        echo $villeRelais . "</BR>";
        echo $paysRelais . "</BR>";
        echo "</BR>";

        $conn->beginTransaction();
        $PR = $conn->prepare('INSERT INTO POINT_RELAIS (nomRelais, numRueRelais, rueRelais, cdPostalRelais, villeRelais, paysRelais) 
                                 VALUES(:nomRelais, :numRueRelais, :rueRelais, :cdPostalRelais, :villeRelais, :paysRelais)');
        $PR->execute(array(
            'nomRelais' => $nomRelais,
            'numRueRelais' => $NumRueRelais,
            'rueRelais' => $RueRelais,
            'cdPostalRelais' => $CodePostalPR,
            'villeRelais' => $villeRelais, 
            'paysRelais' => $paysRelais
        ));
        $conn->commit();
    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion Carte Bancaire -----------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['Valider6'])) {
    for($i = 0; $i < 20; $i++){
        $numCB = $faker->creditCardNumber();
        $dateExpCB = $faker->creditCardExpirationDateString();

        if (strlen($numCB) < 16) {
            $numCB = str_pad($numCB, 16, $faker->randomNumber(1), STR_PAD_RIGHT);
        }   

        $numCB = substr($numCB, 0, 16); 

        echo $numCB . "</BR>" ; 
        echo $dateExpCB . "</BR>" ; 
        echo "</BR>";

        $conn->beginTransaction();
        $CB = $conn->prepare('INSERT INTO CARTE_BANCAIRE (numCB, dateExpCB) 
                                  VALUES(:numCB, :dateExpCB)');
        $CB->execute(array(
            'numCB' => $numCB,
            'dateExpCB' => $dateExpCB
        ));
        $conn->commit();
     }
}

// --------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------- Insertion REGROUPER ----------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------------------
if (isset($_POST['Valider7'])) {
    for($i = 0 ; $i < 5 ; $i++){
        $idProduit = $faker->numberBetween(1, 114); 
        $idRegroupement = $faker->numberBetween(1, 6); 

        $conn->beginTransaction();
            $Regrouper = $conn->prepare('INSERT INTO REGROUPER (idRegroupement, idProduit) 
                                    VALUES(:idRegroupement, :idProduit)');
            $Regrouper->execute(array(
                'idRegroupement' => $idRegroupement,
                'idProduit' => $idProduit
            ));
            $conn->commit();  
    }
}


    echo '</BR></BR>';
    echo '<form method="POST" enctype="multipart/form-data">';
    echo '<center><table border="10">';

    echo '</table></center>';
    echo '</BR><center><input type="submit" name="Valider1" value="Inserer 50 clients dans la base de donnée"/> </center>';
    //echo '</BR><center><input type="submit" name="Valider2" value="Inserer 50 adresses dans la base de donnée"/> </center>';
    //echo '</BR><center><input type="submit" name="Valider3" value="Inserer 20 Avis dans la base de donnée"/> </center>';
    echo '</BR><center><input type="submit" name="Valider4" value="Inserer 1 Commande dans la base de donnée"/> </center>';
    //echo '</BR><center><input type="submit" name="Valider5" value="Inserer UN Point Relais dans la base de donnée"/> </center>';
    //echo '</BR><center><input type="submit" name="Valider6" value="Inserer 20 Cartes Bancaire dans la base de donnée"/> </center>';
    //echo '</BR><center><input type="submit" name="Valider7" value="Regroupement de produits"/> </center>';
    echo '</form>';

?>
