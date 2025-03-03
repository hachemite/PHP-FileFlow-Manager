<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Fichiers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Téléchargement de fichier</h2>
        <form action="traitement_upload.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fichier">Sélectionnez un fichier :</label>
                <input type="file" id="fichier" name="fichier" required>
            </div>
            <div class="form-group">
                <label for="description">Description (optionnelle) :</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn-submit">Télécharger</button>
        </form>
    </div>

    <div class="container">
        <h1>Redimensionnement d'image</h1>
        <form action="redimensionner.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Sélectionnez une image :</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <div class="note">Formats acceptés : JPG, PNG, GIF</div>
            </div>
            <div class="form-group">
                <label for="largeur">Nouvelle largeur (en pixels) :</label>
                <input type="number" id="largeur" name="largeur" min="50" max="1200" value="300" required>
                <div class="note">La hauteur sera ajustée automatiquement pour conserver les proportions</div>
            </div>
            <button type="submit">Redimensionner l'image</button>
        </form>
        <div id="preview" class="preview">
            <h3>Aperçu de l'image sélectionnée</h3>
            <img id="image-preview" src="#" alt="Aperçu">
        </div>
    </div>

    <div class="container">
        <h1>Suppression de fichiers</h1>
        <div class="warning">
            <strong>Attention :</strong> La suppression d'un fichier est définitive et ne peut pas être annulée.
        </div>
        <?php
        $dossier_uploads = 'uploads/';
        if (!is_dir($dossier_uploads)) {
            mkdir($dossier_uploads, 0755, true);
            echo "<p>Le dossier uploads/ a été créé avec succès.</p>";
        }
        $fichiers = scandir($dossier_uploads);
        $fichiers = array_diff($fichiers, array('.', '..'));
        if (empty($fichiers)) {
            echo '<div class="empty-folder"><p>Aucun fichier dans le répertoire uploads/</p></div>';
        } else {
            echo '<form action="supprimer_fichier.php" method="post" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ce fichier ?\');">
                    <div class="file-list">';
            foreach ($fichiers as $fichier) {
                $chemin_complet = $dossier_uploads . $fichier;
                $taille = filesize($chemin_complet);
                $date_modification = date("d/m/Y H:i", filemtime($chemin_complet));
                $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
                $icon = '📄';
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) $icon = '🖼️';
                elseif (in_array($extension, ['doc', 'docx', 'txt', 'pdf', 'odt'])) $icon = '📝';
                elseif (in_array($extension, ['mp3', 'wav', 'ogg', 'flac'])) $icon = '🎵';
                elseif (in_array($extension, ['mp4', 'avi', 'mov', 'mkv'])) $icon = '🎬';
                elseif (in_array($extension, ['zip', 'rar', '7z', 'tar', 'gz'])) $icon = '📦';
                $taille_format = $taille < 1024 ? $taille . " o" : ($taille < 1024 * 1024 ? round($taille / 1024, 2) . " Ko" : round($taille / (1024 * 1024), 2) . " Mo");
                echo '<div class="file-item">
                        <div class="file-info">
                            <div class="file-icon">' . $icon . '</div>
                            <div class="file-details">
                                <span class="file-name">' . htmlspecialchars($fichier) . '</span>
                                <span class="file-meta">' . $taille_format . ' - Modifié le ' . $date_modification . '</span>
                            </div>
                        </div>
                        <input type="radio" name="fichier" value="' . htmlspecialchars($fichier) . '" class="file-select" required>
                      </div>';
            }
            echo '</div><button type="submit" class="button">Supprimer le fichier sélectionné</button></form>';
        }
        ?>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            var preview = document.getElementById('preview');
            var previewImg = document.getElementById('image-preview');
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>