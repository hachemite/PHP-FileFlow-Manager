<?php
if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
    $dossier_destination = "uploads/";
    
    if(!is_dir($dossier_destination)) {
        mkdir($dossier_destination, 0755, true);
    }
    
    $nom_fichier = $_FILES['fichier']['name'];
    $taille_fichier = $_FILES['fichier']['size'];
    $type_fichier = $_FILES['fichier']['type'];
    $tmp_fichier = $_FILES['fichier']['tmp_name'];
    
    $taille_max = 8 * 1024 * 1024; 
    
    if($taille_fichier > $taille_max) {
        echo "<p style='color: red;'>Erreur: Le fichier est trop volumineux (maximum 5 Mo)</p>";
    } else {
        $extension = pathinfo($nom_fichier, PATHINFO_EXTENSION);
        $extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt','json');
        
        if(!in_array(strtolower($extension), $extensions_autorisees)) {
            echo "<p style='color: red;'>Erreur: Ce type de fichier n'est pas autorisé.</p>";
        } else {
            $nouveau_nom = uniqid() . '.' . $extension;
            $chemin_complet = $dossier_destination . $nouveau_nom;
            
            if(move_uploaded_file($tmp_fichier, $chemin_complet)) {
                $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : "Aucune description fournie";
                
                echo "<div style='background-color: #dff0d8; color: #3c763d; padding: 15px; border-radius: 4px; margin: 20px 0;'>";
                echo "<h3>Le fichier a été téléchargé avec succès!</h3>";
                echo "<p><strong>Nom original:</strong> " . htmlspecialchars($nom_fichier) . "</p>";
                echo "<p><strong>Nom sur le serveur:</strong> " . $nouveau_nom . "</p>";
                echo "<p><strong>Taille:</strong> " . round($taille_fichier / 1024, 2) . " Ko</p>";
                echo "<p><strong>Type:</strong> " . $type_fichier . "</p>";
                echo "<p><strong>Description:</strong> " . $description . "</p>";
                echo "</div>";
                
                echo "<a href='javascript:history.back()' style='display: inline-block; padding: 10px 15px; background-color: #337ab7; color: white; text-decoration: none; border-radius: 4px;'>Retour au formulaire</a>";
            } else {
                echo "<p style='color: red;'>Erreur lors du téléchargement du fichier.</p>";
            }
        }
    }
} else {
    if(isset($_FILES['fichier'])) {
        $code_erreur = $_FILES['fichier']['error'];
        $message_erreur = "";
        
        switch($code_erreur) {
            case UPLOAD_ERR_INI_SIZE:
                $message_erreur = "Le fichier dépasse la taille maximale définie dans php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message_erreur = "Le fichier dépasse la taille maximale définie dans le formulaire HTML.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message_erreur = "Le fichier n'a été que partiellement téléchargé.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message_erreur = "Aucun fichier n'a été téléchargé.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message_erreur = "Dossier temporaire manquant.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message_erreur = "Échec de l'écriture du fichier sur le disque.";
                break;
            default:
                $message_erreur = "Une erreur inconnue est survenue.";
        }
        
        echo "<p style='color: red;'>Erreur : " . $message_erreur . "</p>";
    } else {
        echo "<p style='color: red;'>Aucun fichier n'a été soumis. Veuillez utiliser le formulaire de téléchargement.</p>";
    }
    
    echo "<a href='javascript:history.back()' style='display: inline-block; padding: 10px 15px; background-color: #337ab7; color: white; text-decoration: none; border-radius: 4px;'>Retour au formulaire</a>";
}
?>