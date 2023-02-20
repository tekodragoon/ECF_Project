<?php

namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GalleryService
{
    private string $directory;

    public function __construct(ParameterBagInterface $bag)
    {
        $this->directory = $bag->get('app.gallery_dir').DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }


}