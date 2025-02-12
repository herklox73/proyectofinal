<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        // Genera un nombre único para el archivo
        $filename = uniqid().'.'.$file->guessExtension();

        // Mueve el archivo al directorio de destino
        $file->move($this->getTargetDirectory(), $filename);

        return $filename;  // Devuelve el nombre del archivo guardado
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;  // Retorna el directorio de destino
    }
}
