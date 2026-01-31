<?php
/**
 */
class _file
{
    #
    public static function upload($fileInputName, $i, $outputDir, $randomString = '') {
        if (isset($_FILES[$fileInputName]['name'][$i])) {
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            $randomString = $randomString ?: bin2hex(random_bytes(10));
            $uploadedFile = "$outputDir/$randomString." . pathinfo($_FILES[$fileInputName]['name'][$i], PATHINFO_EXTENSION);

            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'][$i], $uploadedFile)) {
                return $uploadedFile; // Retorna la ruta del archivo subido
            }
        }
        return null;
    }
}
