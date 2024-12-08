<?php

namespace App\Api\State\Processor;

use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use App\Entity\Card;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class CardImageProcessor extends BaseProcessor
{
    private Filesystem $fs;

    public function __construct(
        private readonly ItemProvider $itemProvider,
        #[Autowire('%kernel.project_dir%/public/uploads/cards/')] private readonly string $publicDirectory,
    ) {
        $this->fs = new Filesystem();
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        /** @var Request|null $request */
        $request = $context['request'] ?? null;
        if (null === $request) {
            throw new \InvalidArgumentException('Request is required');
        }

        /** @var Card $card */
        $card = $this->itemProvider->provide($operation, $uriVariables, $context);

        $file = $this->getRequestFile($request);
        if (null === $file) {
            throw new \InvalidArgumentException('File is required');
        }

        $uuid = Uuid::v4();
        $filename = $uuid.'_'.$file->getClientOriginalName();

        // delete old file
        if (null !== $card->getImageFilename()) {
            $this->fs->remove(\sprintf('%s/%s', $this->publicDirectory, $card->getImageFilename()));
        }

        // add new file
        $from = $file->getPathname();
        $to = \sprintf('%s/%s', $this->publicDirectory, $filename);
        $this->fs->copy($from, $to, true);

        $card->setImageFilename($filename);
        $this->em->flush();

        return null; // 204 No Content
    }

    private function getRequestFile(Request $request): ?UploadedFile
    {
        return $request->files->all()[array_key_first($request->files->all())];
    }
}
