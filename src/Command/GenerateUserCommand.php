<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GenerateUserCommand extends Command
{
    private $entityManager;
    private $passwordEncoder;
    protected static $defaultName = 'app:generate:user';

    public function __construct($name = null,EntityManagerInterface $interface,UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($name);
        $this->entityManager = $interface;
        $this->passwordEncoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('username', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('password',InputArgument::REQUIRED,'param 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $arg1 = $input->getArgument('username');
        $user->setUsername($arg1);
        $arg2 = $input->getArgument('password');
        $user->setPassword($this->passwordEncoder->encodePassword($user,$arg2));
        $user->setRoles(array('ROLE_USER'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        if ($arg1) {
            $output->writeln('arg 1 is passed !! ');
        }
        if ($arg2){
            $output->writeln('arg2 is passed');
        }

        return 0;
    }
}
