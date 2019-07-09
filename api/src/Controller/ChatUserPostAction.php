<?php

namespace App\Controller;

use App\Entity\ChatUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class ChatUserPostAction
{
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(ChatUser $data, UserPasswordEncoderInterface $encoder): ChatUser
    {
        return $this->encodePassword($data);
    }

    protected function encodePassword(ChatUser $user): ChatUser
    {
        if ($user->getPlainPassword()) {
            $encoded = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
        }

        return $user;
    }
}
