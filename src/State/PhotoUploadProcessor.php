<?php

namespace App\State;

use App\Entity\Photo;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Storage\StorageInterface;

class PhotoUploadProcessor implements ProcessorInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private StorageInterface $storage,
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): Photo
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request && $request->files->has('photoFile')) {
            $uploadedFile = $request->files->get('photoFile');
            $data->setPhotoFile($uploadedFile);
        }

        if ($request && $request->request->has('estPrincipale')) {
            $data->setEstPrincipale($request->request->getBoolean('estPrincipale'));
        }

        if ($request && $request->request->has('article')) {
            // The article should be set in the denormalization
        }

        return $data;
    }
}
