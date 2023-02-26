<?php

namespace App\Service;

use FilesystemIterator;
use Liip\ImagineBundle\Message\WarmupCache;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

class WarmUpCacheService
{
    private GalleryService $galleryService;
    private MessageBusInterface $messageBus;

    public function __construct(ParameterBagInterface $bag, MessageBusInterface $bus)
    {
        $this->galleryService = new GalleryService($bag);
        $this->messageBus = $bus;
    }

    public function createCacheImages(): void
    {
        $imageDir = $this->galleryService->getDirectory();
        $iterator = new FilesystemIterator($imageDir);

        foreach ($iterator as $item) {
            $image = $item->getFilename();
            $relativePath = 'build/images/gallery/'.$image;
            $this->messageBus->dispatch(new WarmupCache($relativePath, [
                'miniature',
                'thumb',
                'watermark',
                'full_watermark',
            ]));
        }
    }
}