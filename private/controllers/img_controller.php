<?php
/**
 */
class ImgController extends AppController
{
    protected function before_filter()
    {
        View::select(null, null);
    }

    public function __call($action_name, $params)
    {
        if (empty($params[0])) {
            throw new KumbiaException('No es una imagen');
        }

        // ID de usuario (si existe)
        $usuarios_idu = empty($params[1]) ? '' : '/' . $params[0];

        // Nombre de la imagen
        $nombre = empty($params[1]) ? $params[0] : $params[1];

        // Separar el tamaño de la miniatura del nombre dado
        preg_match('/(320w|576w|768w|992w|1200w|1400w|1920w)\.(.+)/i', $nombre, $partes);
        if (empty($partes[1])) {
            throw new KumbiaException('Miniatura no aceptada');
        }

        // Tamaño de la miniatura y nombre original de la imagen
        $miniatura = trim($partes[1], 'w');
        $nombre_original = trim($partes[2]);

        // Eliminar la extensión si ya existe en el nombre original
        $nombre_sin_extension = preg_replace('/\.webp$/i', '', $nombre_original);

        // Ruta de la imagen original
        $imagen_original = "img/$action_name$usuarios_idu/$nombre_original";

        // Si se solicitó una miniatura
        if ($imagen_original && $miniatura) {
            // Crear una instancia del procesador de imágenes
            $imageProcessor = new _img();

            // Procesar la imagen al vuelo
            if ($imageProcessor->load($imagen_original)) {
                $imageProcessor->resizeToWidth($miniatura);
                $miniatura_nombre = "{$miniatura}w.$nombre_sin_extension.webp";
                $ruta_miniatura = "img/$action_name$usuarios_idu/$miniatura_nombre";
                $imageProcessor->save($ruta_miniatura, IMAGETYPE_WEBP);

                // Redirigir a la miniatura generada
                Redirect::to($ruta_miniatura);
            } else {
                throw new KumbiaException('No se pudo cargar la imagen original');
            }
        } else {
            throw new KumbiaException('Faltan parámetros para generar la miniatura');
        }
    }

}
