# PHP-FileFlow-Manager


A complete exercise to create a responsive, well-organized, and secure file management system using PHP. This project includes file creation, file uploads, image resizing, and file deletion functionalities.

---

## Features

1. **File Creation**:
   - Create a file named `mon_fichier.txt`.
   - Write the text: `"Bienvenue dans l'apprentissage des fichiers en PHP!"`.
   - Read and display the file content.

2. **File Upload**:
   - Create an HTML form for file uploads.
   - Handle file uploads with PHP and save files to the `uploads/` directory.
   - Display a success message after upload.

3. **Image Resizing**:
   - Resize an image while maintaining its aspect ratio.
   - Use `image.jpg` and resize it to a width of 300 pixels.

4. **File Deletion**:
   - Allow users to select and delete files from the `uploads/` directory.
   - Use a form to specify the file to delete.

5. **Responsive Design**:
   - The website is fully responsive and works seamlessly on all devices.

6. **Security**:
   - Validate file types and sizes during upload.
   - Prevent unauthorized file access and deletion.

---

## Code Implementation

### 1. File Creation (`create_file.php`)
```php
<?php
// Create a file and write content
$file = "mon_fichier.txt";
$content = "Bienvenue dans l'apprentissage des fichiers en PHP!";
file_put_contents($file, $content);

// Read and display the file content
echo file_get_contents($file);
?>
```

---

### 2. File Upload (`upload.php`)

#### HTML Form
```html
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="file">Sélectionnez un fichier :</label>
    <input type="file" name="file" id="file" required>
    <button type="submit">Télécharger</button>
</form>
```

#### PHP Upload Handler
```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir . basename($_FILES['file']['name']);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file type and size
    $allowed_types = ['txt', 'pdf', 'jpg', 'png'];
    if (in_array($file_type, $allowed_types) && $_FILES['file']['size'] < 5000000) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            echo "Le fichier " . htmlspecialchars(basename($_FILES['file']['name'])) . " a été téléchargé avec succès.";
        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        echo "Type de fichier non supporté ou taille trop grande.";
    }
}
?>
```

---

### 3. Image Resizing (`resize_image.php`)

```php
<?php
$source_image = "image.jpg";
$target_width = 300;

// Get image dimensions
list($width, $height) = getimagesize($source_image);
$aspect_ratio = $height / $width;
$target_height = $target_width * $aspect_ratio;

// Create a new image
$new_image = imagecreatetruecolor($target_width, $target_height);
$source = imagecreatefromjpeg($source_image);

// Resize the image
imagecopyresampled($new_image, $source, 0, 0, 0, 0, $target_width, $target_height, $width, $height);

// Save the resized image
imagejpeg($new_image, "resized_image.jpg", 90);

echo "L'image a été redimensionnée avec succès.";
?>
```

---

### 4. File Deletion (`delete_file.php`)

#### HTML Form
```html
<form action="delete_file.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
    <label for="file">Sélectionnez un fichier à supprimer :</label>
    <select name="file" id="file" required>
        <?php
        $files = scandir("uploads/");
        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                echo "<option value='$file'>$file</option>";
            }
        }
        ?>
    </select>
    <button type="submit">Supprimer</button>
</form>
```

#### PHP Deletion Handler
```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_to_delete = "uploads/" . $_POST['file'];
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        echo "Le fichier " . htmlspecialchars($_POST['file']) . " a été supprimé avec succès.";
    } else {
        echo "Le fichier n'existe pas.";
    }
}
?>
```

---

### 5. Responsive Design (`styles.css`)

```css
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 0 auto;
}

h1, h2 {
    color: #333;
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

input, select, textarea, button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: #28a745;
    color: white;
    cursor: pointer;
}

button:hover {
    background-color: #218838;
}

@media (max-width: 600px) {
    .container {
        padding: 10px;
    }
}
```

---

### Security Measures

1. **File Upload Validation**:
   - Check file types and sizes.
   - Use `basename()` to prevent directory traversal attacks.

2. **File Deletion Confirmation**:
   - Use a confirmation dialog before deleting files.

3. **Directory Protection**:
   - Add an `.htaccess` file in the `uploads/` directory to prevent direct access:
     ```apache
     Deny from all
     ```

---

## How to Run

1. Clone the repository.
2. Set up a local PHP server (e.g., XAMPP or WAMP).
3. Place the files in the server's root directory.
4. Access the project via `http://localhost/PHP-FileFlow-Manager`.

---

## Conclusion

This project demonstrates how to create a secure and responsive file management system using PHP. It covers file creation, uploads, image resizing, and file deletion, making it a great exercise for learning PHP file handling and web development.
![image](https://github.com/user-attachments/assets/e9c35488-1ab7-452b-8159-929406ec6927)
![image](https://github.com/user-attachments/assets/1a2dc6c2-5520-4d4b-a66e-6bdd0e22520a)
