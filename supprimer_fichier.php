<?php

if (!isset($_POST['fichier']) || empty($_POST['fichier'])) {
    header('Location: index.html?erreur=aucun_fichier');
    exit;
}

$nom_fichier = $_POST['fichier'];

$nom_fichier = basename($nom_fichier);

$dossier_uploads = 'uploads/';

$chemin_fichier = $dossier_uploads . $nom_fichier;

if (!file_exists($chemin_fichier) || !is_file($chemin_fichier)) {
    $message = "Erreur : Le fichier spécifié n'existe pas ou n'est pas valide.";
    $statut = "erreur";
} else {
    if (unlink($chemin_fichier)) {
        $message = "Le fichier \"$nom_fichier\" a été supprimé avec succès.";
        $statut = "succes";
    } else {
        $message = "Erreur : Impossible de supprimer le fichier. Vérifiez les permissions.";
        $statut = "erreur";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de la suppression</title>
</head>
<body>
    <div class="container">
        <h1>Résultat de la suppression</h1>
        
        <div class="message <?php echo $statut; ?>">
            <?php echo $message; ?>
        </div>
        
        <a href="index.html" class="button">Retour à la liste des fichiers</a>
    </div>
</body>
</html>