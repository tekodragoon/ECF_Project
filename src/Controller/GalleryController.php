<?php

namespace App\Controller;

use App\Repository\GalleryImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(GalleryImageRepository $repository): Response
    {
        $images = $repository->findBy(['visible' => true], ['id' => 'ASC']);

        return $this->render('gallery/index.html.twig', [
            'images' => $images,
        ]);
    }
}
