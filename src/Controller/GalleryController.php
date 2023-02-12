<?php

namespace App\Controller;

use FilesystemIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(): Response
    {
        $imgDir = $this->getParameter('gallery_dir').DIRECTORY_SEPARATOR;
        $iterator = new FilesystemIterator($imgDir);
        $images = [];
        foreach ($iterator as $fileInfo) {
            $images[] = $fileInfo->getFilename();
        }
        return $this->render('gallery/index.html.twig', [
            'images' => $images,
        ]);
    }
}
