<?php

namespace App\Service\questionnaire;

use Symfony\Component\Serializer\SerializerInterface;

class ExportService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function exportDataAsXLSForm($data)
    {
          $serializedData = $this->serializer->serialize($data, 'json');

        return $serializedData;
    }
}
