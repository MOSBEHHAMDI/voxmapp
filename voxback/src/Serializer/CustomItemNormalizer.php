<?php

namespace App\Serializer;

use ReflectionClass;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomItemNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        if (!$normalizer instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException('The normalizer must implement the DenormalizerInterface');
        }

        $this->normalizer = $normalizer;
    }

    private function containClassName($haystack, $classArray)
    {
        foreach ($classArray as $class) {
            if (str_contains($haystack, $class) === true) {
                return true;
            }
        }
        return false;
    }


    /**
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $result = $this->normalizer->normalize($object, $format, $context);
        $classArray = ['Questionnaire', 'Question', 'Section', 'Choice', 'Translation'];
        $reflect = new ReflectionClass($object);
        $shortName = $reflect->getShortName();
        if (is_array($result) && $this->containClassName($shortName, $classArray)) {
            $result['id'] = '/api/' . strtolower($shortName) . 's/' . $object->getId();
        }
        return $result;
    }

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($format === 'graphql') {
            return false;
        }
        return $this->normalizer->supportsDenormalization($data, $type, $format);
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($format === 'graphql') {
            return false;
        }
        return $this->normalizer->supportsNormalization($data, $format);
    }
}