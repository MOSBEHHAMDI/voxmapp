<?php

namespace App\Service;

use App\Entity\DataPoint;
use App\Entity\MediaType;
use App\Entity\Media;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;

class Utils
{
    private EntityManagerInterface $em;
    private Security $security;
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
        $this->params = $params;
    }

    public function getCurrentUser()
    {
        return $this->security->getUser();
    }

    public function getCurrenttClient($client_name = null)
    {
        if ($client_name && $client = $this->em->getRepository(Client::class)->findOneBy(['name' => $client_name])) {
            return $client;
        }
        if (($user = $this->getCurrentUser()) && count($user->getClients())) {
            return $user->getClients()[0];
        }
        return null;
    }

     /**
     * @throws MappingException
     */
    public function getDataPointMetadata()
    {
        $classMetadata = $this->em->getClassMetadata(DataPoint::class);

        // Get column names
        $columns = $classMetadata->getFieldNames();

        // Get association mappings
        $associationMappings = $classMetadata->getAssociationMappings();

        // Combine both mappings
        $metadata = [];

        foreach ($columns as $column) {
            $data = $classMetadata->getFieldMapping($column);
            $metadata[$data['fieldName']] = [
                'type' => $data['type']
            ];
        }

        foreach ($associationMappings as $associationName => $associationMapping) {
            $metadata[$associationName] = [
                'type' => $associationMapping['type'],
                'association' => true,
                'targetEntity' => $associationMapping['targetEntity'],
            ];
        }

        return $metadata;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function createQuery($dql, $client_name = null)
    {
        $query = $this->em->createQuery($dql);
        $user = $this->getCurrentUser();
        if ($user && !$user->hasRole("ROLE_SUPER_ADMIN")) {
            $alias = $query->getRootAliases()[0];
            if ($client = $this->getCurrentClient($client_name)) {
                $query->andWhere($alias . '.client = :c')
                    ->setParameter(':c', $client);
            }
        }
        return $query;
    }

    public function logFile($msg, $name)
    {
        $path = $this->params->get('kernel.project_dir');
        $log_file = "$path/var/log/$name.log";
        $date = new \DateTime();
        //Create log file if it does not exist
        if (!file_exists($log_file)) {
            touch($log_file);
        }
        if ($user = $this->getCurrentUser()) {
            $msg = $user->getUserIdentifier() . ": " . $msg;
        }
        error_log($date->format('c') . " " . $msg . "\n", 3, $log_file);
    }

    public function saveFile(UploadedFile $uploadedFile, $subDir): ?Media
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $mimeType = $uploadedFile->getMimeType();
        $filesystem = new Filesystem();
        $uploadDir = $this->params->get('uploads_directory');
        try {
            $destination = $uploadDir . "/" . $subDir;
            if (!$filesystem->exists($destination)) {
                $filesystem->mkdir($destination, 0700);
            }
            $uploadedFile->move($destination, $fileName);

            // Create a new Media entity
            $media = new Media();
            $media->setFilePath($subDir.'/'.$fileName);
            $media->setName($fileName);
            // Create a new MediaType entity
            /** @var MediaType $mediaType */
            $mediaType = $this->em->getRepository(MediaType::class)->findOneBy(['label' => $mimeType]);
            if (!$mediaType) {
                $mediaType = new MediaType();
                $mediaType->setLabel($mimeType);
                $this->em->persist($mediaType);
            }
            $media->setMediaType($mediaType);
            $this->em->persist($media);
            $this->em->flush();
            return $media;
        } catch (\Exception $e) {
            throw new \Exception('Failed to save file: ' . $e->getMessage());
        }
    }

    public function getFile($filePath)
    {
        $uploadDir = $this->params->get('uploads_directory');
        $filePath = $uploadDir."/".$filePath;
        $filesystem = new Filesystem();
        if ($filesystem->exists($filePath)) {
            return $filePath;
        }
        return null;
    }

}