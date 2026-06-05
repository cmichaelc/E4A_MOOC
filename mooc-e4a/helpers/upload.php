<?php
/**
 * Gestion des uploads de fichiers — E4Afrika MOOC
 */

require_once __DIR__ . '/../config/config.php';

class UploadHandler {

    private array $errors = [];

    /**
     * Valide et déplace un fichier uploadé (devoir)
     * @param  array  $file   $_FILES['fichier']
     * @param  string $destDir Répertoire de destination absolu
     * @return string|false  Nom du fichier enregistré ou false
     */
    public function handleDevoir(array $file, string $destDir = ''): string|false {
        $this->errors = [];

        if ($destDir === '') {
            $destDir = UPLOAD_PATH;
        }

        // Vérification erreur upload PHP
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->phpUploadError($file['error']);
            return false;
        }

        // Taille
        if ($file['size'] > MAX_FILE_SIZE) {
            $this->errors[] = 'Le fichier dépasse la taille maximale autorisée (5 Mo).';
            return false;
        }

        // Type MIME (vérification réelle, pas juste l'extension)
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, ALLOWED_MIME_TYPES, true)) {
            $this->errors[] = 'Type de fichier non autorisé. Formats acceptés : PDF, DOC, DOCX, ZIP, JPG, PNG.';
            return false;
        }

        // Générer un nom unique
        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = uniqid('devoir_', true) . '.' . strtolower($ext);
        $dest     = rtrim($destDir, '/') . '/' . $safeName;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->errors[] = 'Erreur lors du déplacement du fichier.';
            return false;
        }

        return $safeName;
    }

    /**
     * Supprime un fichier uploadé
     */
    public function delete(string $filename, string $dir = ''): bool {
        $dir  = $dir !== '' ? $dir : UPLOAD_PATH;
        $path = rtrim($dir, '/') . '/' . basename($filename);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function hasErrors(): bool {
        return !empty($this->errors);
    }

    private function phpUploadError(int $code): string {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'Le fichier dépasse la limite php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'Le fichier dépasse la limite du formulaire.',
            UPLOAD_ERR_PARTIAL    => 'Le fichier n\'a été que partiellement uploadé.',
            UPLOAD_ERR_NO_FILE    => 'Aucun fichier n\'a été uploadé.',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
            UPLOAD_ERR_CANT_WRITE => 'Échec d\'écriture sur le disque.',
            UPLOAD_ERR_EXTENSION  => 'Extension PHP a stoppé l\'upload.',
        ];
        return $messages[$code] ?? 'Erreur inconnue lors de l\'upload.';
    }
}
