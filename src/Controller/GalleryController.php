<?php

namespace App\Controller;

use App\Repository\GalleryImagesRepository;
use FilesystemIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(GalleryImagesRepository $repository): Response
    {
        $images = $repository->findAll();

        return $this->render('gallery/index.html.twig', [
            'images' => $images,
        ]);
    }
}
