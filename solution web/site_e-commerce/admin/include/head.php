<!--
Tête HTML des pages du back-office.

Date de dernière modification :
- Lundi 30 décembre 2024 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : "Back-office" ; ?></title>
    <link rel="icon" type="image/png" href="<?= isset($pageIcon) ? $pageIcon : "image/icon/back-office-favicon.png" ; ?>">
    <link rel="stylesheet" type="text/css" href="style/admin_space.css">
</head>