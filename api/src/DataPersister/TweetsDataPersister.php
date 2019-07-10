<?php

namespace App\DataPersister;

use App\Entity\ClubTweet;
use App\Entity\FanTweet;
use App\Entity\Tweet;
use App\Normalizer\DateTimeStringNormalizer;
use App\Repository\TweetRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class TweetsDataPersister
{
    private $entityManager;
    private $tweetRepository;
    private $serializer;
    public static $PIDFile = '/tmp/PID/UMM';

    public function __construct(
        EntityManagerInterface $entityManager,
        TweetRepository $tweetRepository,
        SerializerInterface $serializer
    )
    {
        $this->entityManager = $entityManager;
        $this->tweetRepository = $tweetRepository;
        $this->serializer = $serializer;
        $encoders = [new JsonEncoder()];
        $dateTimeNormalizer = new DateTimeNormalizer([
            DateTimeNormalizer::FORMAT_KEY => 'D M d H:i:s O Y'
        ]);
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $objectNormalizer = new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter());

        $normalizers = [
            new DateTimeStringNormalizer($objectNormalizer),
            $dateTimeNormalizer,
            $objectNormalizer,
        ];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param array $tweets
     * @param bool $isFanTweet
     * @throws ExceptionInterface
     */
    public function persistTweets(array $tweets, bool $isFanTweet = false): void
    {
        $resourceClass = $isFanTweet ? FanTweet::class : ClubTweet::class;
        foreach ($tweets as $tweetArray) {
            /** @var Tweet $tweet */
            $tweet = $this->serializer->denormalize($tweetArray, $resourceClass, 'json');

            $existingTweet = $this->tweetRepository->findOneBy(
                [
                    'id' => $tweet->getId()
                ]
            );
            if ($existingTweet) {
                continue;
            }
            $this->entityManager->persist($tweet);
        }
        $this->entityManager->flush();
    }
}
