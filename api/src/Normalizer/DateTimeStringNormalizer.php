<?php

namespace App\Normalizer;

use DateTime;
use Exception;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DateTimeStringNormalizer implements DenormalizerInterface
{
    private static $keys = ['created_at'];
    private $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        foreach (self::$keys as $dateTimeKey) {
            if (isset($data[$dateTimeKey]) && is_string($value = $data[$dateTimeKey]))
            {
                try {
                    $data[$dateTimeKey] = new DateTime($value);
                } catch (Exception $e) {
                }
            }
        }
        return $this->objectNormalizer->denormalize($data, $class, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_array($data) && !array_diff_key(array_flip(self::$keys), $data);
    }
}
