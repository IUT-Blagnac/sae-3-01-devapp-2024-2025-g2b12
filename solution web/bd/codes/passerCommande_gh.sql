/*
 * Procédure de traitement d'une nouvelle commande.
 *
 * Date de dernière modification :
 * - Lundi 23 décembre 2024 -
 *
 * Auteur : Victor Jockin
 * - Équipe 2B12 -
 */


-- paramétrage du délimiteur
DELIMITER /

-- suppression de la procédure si existante
DROP PROCEDURE IF EXISTS passerCommande /

-- définition de la procédure
CREATE PROCEDURE passerCommande(
    pIdClient INT,
    pModeLivraison VARCHAR(15),
    pIdAdresseDeLivraison INT,
    pModePaiement VARCHAR(10),
    pNumCB CHAR(16),
    pNumPaypal DECIMAL(10),
    pNumVirement DECIMAL(10)
)
BEGIN
    -- déclaration des variables
    -- -------------------------
    -- variables relatives à la commande à créer
    DECLARE varDateCommande DATE ;                  -- date de la commande
    DECLARE varIdAdresse INT ;                      -- ID d'une adresse client
    DECLARE varIdRelais INT ;                       -- ID d'un point relais
    DECLARE varNumCB CHAR(16) ;                     -- numéro de carte bancaire
    DECLARE varNumPaypal DECIMAL(10) ;              -- numéro de paimenent PayPal
    DECLARE varNumVirement DECIMAL(10) ;            -- numéro de virement
    DECLARE varMontant DECIMAL(22,2) ;              -- montant total de la commande
    -- variables relatives aux produits commandés
    DECLARE varIdCommande INT ;                     -- ID de la commande insérée
    DECLARE varIdVProduit INT ;                     -- ID d'une variante de produit
    DECLARE varPrixVProduit DECIMAL(22,0) ;         -- prix d'une variante de produit
    DECLARE varQteEnregistree DECIMAL(10,0) ;       -- quantité enregistrée pour une variante de produit
    DECLARE varQteStockVariete DECIMAL(10,0) ;      -- quantité en stock d'une variante de produit
    DECLARE isFinCurseur BOOLEAN DEFAULT FALSE ;    -- indique si le curseur a été entièrement lu
    -- compteur de lignes pour exceptions
    DECLARE varCpt INT ;
    -- curseur de récupération des variantes de produits commandés
    DECLARE curseurVProduitsCommandees CURSOR FOR
        SELECT E.idVariete, E.qteEnregistree, V.prixVariete
        FROM ENREGISTRER E INNER JOIN VARIETE V
            ON E.idVariete = V.idVariete
        WHERE E.idClient = pIdClient ;
    -- gestionnaire d'exception pour lecture du curseur
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET isFinCurseur = TRUE ;

    -- initialisation des variables relatives à la commande
    SET varDateCommande = CURDATE() ;
    SET varIdAdresse    = NULL ;
    SET varIdRelais     = NULL ;
    SET varNumCB        = NULL ;
    SET varNumPaypal    = NULL ;
    SET varNumVirement  = NULL ;
    SET varMontant      = 0 ;

    -- traitement des exceptions
    -- -------------------------
    -- vérification de la validité de l'ID Client
    IF pIdClient IS NULL THEN
        SIGNAL SQLSTATE '45010'
        SET MESSAGE_TEXT = 'Erreur : Client non renseigné.',
            MYSQL_ERRNO = 45010 ;
    END IF ;
    SELECT COUNT(*) INTO varCpt FROM CLIENT WHERE idClient = pIdClient ;
    IF varCpt != 1 THEN
        SIGNAL SQLSTATE '45011'
        SET MESSAGE_TEXT = 'Erreur : Client inconnu.',
            MYSQL_ERRNO = 45011 ;
    END IF ;
    -- traitement du mode de livraison
    IF pModeLivraison IS NULL THEN
        SIGNAL SQLSTATE '45020'
        SET MESSAGE_TEXT = 'Erreur : Mode de livraison non renseigné.',
            MYSQL_ERRNO = 45020 ;
    END IF ;
    IF pIdAdresseDeLivraison IS NULL THEN
        SIGNAL SQLSTATE '45030'
        SET MESSAGE_TEXT = 'Erreur : Adresse de livraison non renseignée.',
            MYSQL_ERRNO = 45030 ;
    END IF ;
    CASE WHEN pModeLivraison = 'DOMICILE' THEN
            SELECT COUNT(*) INTO varCpt FROM ADRESSE WHERE idAdresse = pIdAdresseDeLivraison ;
            IF varCpt != 1 THEN
                SIGNAL SQLSTATE '45031'
                SET MESSAGE_TEXT = 'Erreur : Adresse ne correspondant à aucun domicile enregistré.',
                    MYSQL_ERRNO = 45031 ;
            END IF ;
            SET varIdAdresse = pIdAdresseDeLivraison ;
        WHEN pModeLivraison = 'RELAIS' THEN
            SELECT COUNT(*) INTO varCpt FROM POINT_RELAIS WHERE idRelais = pIdAdresseDeLivraison ;
            IF varCpt != 1 THEN
                SIGNAL SQLSTATE '45032'
                SET MESSAGE_TEXT = 'Erreur : Adresse ne correspondant à aucun point relais enregistré.',
                    MYSQL_ERRNO = 45032 ;
            END IF ;
            SET varIdRelais = pIdAdresseDeLivraison ;
        ELSE
            SIGNAL SQLSTATE '45021'
            SET MESSAGE_TEXT = 'Erreur : Mode de livraison invalide.',
                MYSQL_ERRNO = 45021 ;
    END CASE ;
    -- traitement du mode de paiement
    IF pModePaiement IS NULL THEN
        SIGNAL SQLSTATE '45040'
        SET MESSAGE_TEXT = 'Erreur : Mode de paiement non renseigné.',
            MYSQL_ERRNO = 45040 ;
    END IF ;
    CASE WHEN pModePaiement = 'CB' THEN
            IF pNumCB IS NULL THEN
                SIGNAL SQLSTATE '45050'
                SET MESSAGE_TEXT = 'Erreur : Carte bancaire non renseignée.',
                    MYSQL_ERRNO = 45050 ;
            END IF ;
            SELECT COUNT(*) INTO varCpt FROM CARTE_BANCAIRE WHERE numCB = pNumCB ;
            IF varCpt != 1 THEN
                SIGNAL SQLSTATE '45051'
                SET MESSAGE_TEXT = 'Erreur : Carte bancaire inconnue.',
                    MYSQL_ERRNO = 45051 ;
            END IF ;
            SET varNumCB = pNumCB ;
        WHEN pModePaiement = 'PAYPAL' THEN
            IF pNumPaypal IS NULL THEN
                SIGNAL SQLSTATE '45060'
                SET MESSAGE_TEXT = 'Erreur : Numéro PayPal non renseigné.',
                    MYSQL_ERRNO = 45060 ;
            END IF ;
            SET varNumPaypal = pNumPaypal ;
        WHEN pModePaiement = 'VIREMENT' THEN
            IF pNumVirement IS NULL THEN
                SIGNAL SQLSTATE '45070'
                SET MESSAGE_TEXT = 'Erreur : Numéro de virement non renseigné.',
                    MYSQL_ERRNO = 45070 ;
            END IF ;
            SET varNumVirement = pNumVirement ;
        ELSE
            SIGNAL SQLSTATE '45041'
            SET MESSAGE_TEXT = 'Erreur : Mode de paiement invalide.',
                MYSQL_ERRNO = 45041 ;
    END CASE ;

    -- traitement de la commande
    -- -------------------------
    -- insertion de la commande
    INSERT INTO COMMANDE (idClient, dateCommande, modeLivraison, idAdresse, idRelais, modePaiement, numCB, numPaypal, numVirement)
    VALUES (pIdClient, varDateCommande, pModeLivraison, varIdAdresse, varIdRelais, pModePaiement, varNumCB, varNumPaypal, varNumVirement) ;
    -- récupération de l'ID de la commande
    SET varIdCommande = LAST_INSERT_ID() ;
    -- insertion des variantes de produits commandés
    OPEN curseurVProduitsCommandees ;
    BOUCLE: LOOP
        FETCH curseurVProduitsCommandees INTO varIdVProduit, varQteEnregistree, varPrixVProduit ;
        IF isFinCurseur THEN LEAVE BOUCLE ; END IF ;
        -- mise à jour de la quantité commandée en cas de dépassement du stock disponible
        SELECT qteStockVariete INTO varQteStockVariete FROM VARIETE WHERE idVariete = varIdVProduit ;
        IF varQteEnregistree > varQteStockVariete THEN
            SET varQteEnregistree = varQteStockVariete ;
        END IF ;
        -- ajout de la variante du produit à la commande
        INSERT INTO COMMANDER (idCommande, idVariete, qteCommandee)
        VALUES (varIdCommande, varIdVProduit, varQteEnregistree) ;
        -- mise à jour du stock de la variante de produit
        UPDATE VARIETE
        SET qteStockVariete = qteStockVariete - varQteEnregistree
        WHERE idVariete = varIdVProduit ;
        -- calcul du montant de la commande
        SET varMontant = varMontant + varQteEnregistree * varPrixVProduit ;
    END LOOP ;
    CLOSE curseurVProduitsCommandees ;
    -- mise à jour du montant de la commande
    UPDATE COMMANDE SET montantCommande = varMontant WHERE idCommande = varIdCommande ;
    -- nettoyage du panier (suppression des produits commandés)
    DELETE FROM ENREGISTRER WHERE idClient = pIdClient ;
    -- validation des transactions
    COMMIT ;
END /

DELIMITER ;