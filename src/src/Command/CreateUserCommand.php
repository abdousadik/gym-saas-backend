<?php

namespace App\Command;

use App\Module\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-user')]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setFirstName('Admin');
        $user->setLastName('User');
        $user->setPlatformRole(User::PLATFORM_ROLE_SUPER_ADMIN);
        $user->setStatus(User::STATUS_ACTIVE);

        $user->setPassword(
            $this->hasher->hashPassword($user, '123456')
        );

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('User created');

        return Command::SUCCESS;
    }
}