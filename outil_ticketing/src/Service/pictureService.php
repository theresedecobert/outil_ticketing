<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class pictureService
{
    private $params;
    public function __construct(parameterBagInterface $params) // sert à aller chercher dans le service l'endroit du dossier upload
    {
        $this->params = $params;
    }

    public function add(UploadedFile $file, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {

        $fichier = md5(uniqid(rand(), true)) . '.png';


        // Obtention des informations sur l'image
        $file_infos = getimagesize($file);


        if ($file === false) {
            throw new \Exception('Format d\'image incorrect');
        }

        // Vérification du format de l'image
        switch ($file_infos['mime']) {
            case 'image/png':
                $file_source = imagecreatefrompng($file);
                break;
            case 'image/jpeg':
                $file_source = imagecreatefromjpeg($file);
                break;
            case 'image/jpg':
                $file_source = imagecreatefromwebp($file);
                break;
            default:
                throw new Exception(('Format d\'image incorrect'));
        }
        // Redimensionnement de l'image
        $imageWidth = $file_infos[0];
        $imageHeight = $file_infos[1];

        //on recadre l'image 
        //on récupère les dimensions
        $imageWidth = $file_infos[0];
        $imageHeight = $file_infos[1];

        // on vérifie l'orientation de l'image
        switch($imageWidth <=> $imageHeight){
            case -1: // portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) /2;
                break;

            case 0: // carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;

            case 1: // paysage
                $squareSize = $imageHeight;
                $src_x = 0;
                $src_y = ($imageWidth - $squareSize) /2;
                break;
        }

        //on crée une nouvelle image vierge dans laquelle on vient coller l'image redimensionnée.
        $resized_picture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized_picture, $file_source, 0 ,0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory') . $folder;

        // on crée le dossier de destination s'il n'existe pas. là on va faire un dossier mini pour mettre les miniatures

        if(!file_exists($path . '/mini/')){
            mkdir($path . '/mini/',0755, true);
        }
    // on stocke l'image recadrée
  imagepng($resized_picture, $path . '/mini/' . $width . $height . '-' . $fichier);
    $file->move($path . '/', $fichier);

    return $fichier; 
    }
}