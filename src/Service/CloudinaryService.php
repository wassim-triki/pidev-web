<?php
namespace App\Service;

use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct(string $cloudinaryUrl)
    {
        $this->cloudinary = new Cloudinary($cloudinaryUrl);
    }

    public function upload(UploadedFile $file): string
    {
        $response = $this->cloudinary->uploadApi()->upload($file->getPathname());

        return $response['secure_url'];
    }
}

