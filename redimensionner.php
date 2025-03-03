<?php
// Check if an image was uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
    die("Erreur : Aucune image n'a été soumise ou une erreur s'est produite lors du téléchargement.");
}

// Validate the uploaded file as an image
$info_image = getimagesize($_FILES['image']['tmp_name']);
if ($info_image === false) {
    die("Le fichier téléchargé n'est pas une image valide.");
}

// Check if the MIME type is supported
$type_mime = $info_image['mime'];
$types_autorises = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/bmp', 'image/webp'];
if (!in_array($type_mime, $types_autorises)) {
    die("Type d'image non supporté. Seuls JPG, PNG, GIF, BMP et WEBP sont acceptés.");
}

// Set new width (default to 300px if invalid)
$nouvelle_largeur = isset($_POST['largeur']) ? intval($_POST['largeur']) : 300;
if ($nouvelle_largeur < 50 || $nouvelle_largeur > 1200) {
    $nouvelle_largeur = 300;
}

// Calculate new height to maintain aspect ratio
$largeur_originale = $info_image[0];
$hauteur_originale = $info_image[1];
$nouvelle_hauteur = round(($nouvelle_largeur / $largeur_originale) * $hauteur_originale);

// Create a new image resource
$nouvelle_image = imagecreatetruecolor($nouvelle_largeur, $nouvelle_hauteur);

// Load the source image based on MIME type
switch ($type_mime) {
    case 'image/jpeg':
    case 'image/jpg':
        $image_source = imagecreatefromjpeg($_FILES['image']['tmp_name']);
        break;
    case 'image/png':
        $image_source = imagecreatefrompng($_FILES['image']['tmp_name']);
        imagealphablending($nouvelle_image, false);
        imagesavealpha($nouvelle_image, true);
        $transparent = imagecolorallocatealpha($nouvelle_image, 0, 0, 0, 127);
        imagefilledrectangle($nouvelle_image, 0, 0, $nouvelle_largeur, $nouvelle_hauteur, $transparent);
        break;
    case 'image/gif':
        $image_source = imagecreatefromgif($_FILES['image']['tmp_name']);
        break;
    case 'image/webp':
        $image_source = imagecreatefromwebp($_FILES['image']['tmp_name']);
        break;
}

// Resize the image
imagecopyresampled(
    $nouvelle_image, $image_source, 0, 0, 0, 0, $nouvelle_largeur, $nouvelle_hauteur, $largeur_originale, $hauteur_originale
);

// Save the resized image
$dossier_images = 'images_redimensionnees/';
if (!is_dir($dossier_images)) {
    mkdir($dossier_images, 0755, true);
}

$nom_fichier_original = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
$extension = str_replace('image/', '.', $type_mime);
$nom_fichier_destination = $dossier_images . $nom_fichier_original . '_' . $nouvelle_largeur . 'px_' . uniqid() . $extension;

switch ($type_mime) {
    case 'image/jpeg':
    case 'image/jpg':
        imagejpeg($nouvelle_image, $nom_fichier_destination, 90);
        break;
    case 'image/png':
        imagepng($nouvelle_image, $nom_fichier_destination, 9);
        break;
    case 'image/gif':
        imagegif($nouvelle_image, $nom_fichier_destination);
        break;
    case 'image/webp':
        imagewebp($nouvelle_image, $nom_fichier_destination);
        break;
}

// Free up memory
imagedestroy($image_source);
imagedestroy($nouvelle_image);

$chemin_relatif = $nom_fichier_destination;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Redimensionnée</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Image redimensionnée avec succès</h1>
        <div class="result">
            <div class="image-container">
                <h3>Image originale</h3>
                <p>Dimensions: <?php echo $largeur_originale; ?> x <?php echo $hauteur_originale; ?> pixels</p>
                <img src="data:<?php echo $type_mime; ?>;base64,<?php echo base64_encode(file_get_contents($_FILES['image']['tmp_name'])); ?>" 
                     alt="Image originale">
            </div>
            <div class="image-container">
                <h3>Image redimensionnée</h3>
                <p>Dimensions: <?php echo $nouvelle_largeur; ?> x <?php echo $nouvelle_hauteur; ?> pixels</p>
                <img src="<?php echo $chemin_relatif; ?>" alt="Image redimensionnée">
            </div>
        </div>
        <div class="actions">
            <a href="<?php echo $chemin_relatif; ?>" download class="button button-download">Télécharger l'image redimensionnée</a>
            <a href="javascript:history.back()" class="button">Retour au formulaire</a>
        </div>
    </div>
</body>
</html>