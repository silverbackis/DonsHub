<?php

namespace App\Command;

use App\Repository\ChatUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UsersClearCommand extends Command
{
    protected static $defaultName = 'app:users:clear';
    private $entityManager;
    private $chatUserRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ChatUserRepository $chatUserRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->chatUserRepository = $chatUserRepository;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Destroys all current users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $refreshTokenRepository = $this->entityManager->getRepository(RefreshToken::class);
        $users = $this->chatUserRepository->findAll();
        foreach ($users as $user) {
            $refreshToken = $refreshTokenRepository->findOneBy([
                'username' => $user->getUsername()
            ]);
            if (!$refreshToken) {
                $this->entityManager->remove($user);
            }
        }
        $this->entityManager->flush();

        $io->success('Removed all chat users');
    }
}
