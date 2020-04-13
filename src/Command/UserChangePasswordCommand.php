<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserChangePasswordCommand extends Command
{
    protected static $defaultName = 'app:user:change-password';

    private $em;
    private $validator;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Modification du mot de passe ')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, "Le nom de l'utilisateur"),
                new InputArgument('password', InputArgument::REQUIRED, "Le mot de passe de l'utilisateur"),

            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $user = $this->em->getRepository(User::class)->findOneByUsername($username);

        // Renvoyer false si l'utilisateur n'existe pas
        if (!$user){
            $message = $username.' ne correspond a aucun utilisateur';
            $io->error($message);
            return  2;
        }
        $password = $input->getArgument('password');
        $password = $this->passwordEncoder->encodePassword($user, $password);

        $user->setPassword($password);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->flush();

        $message = "Le mot de passe de l'utilisateur ".$username." a bien été modifié";

        $io->success($message);

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('username')){
            $question = new Question('Le nom de l\'utilisateur concerné : ');
            $questions['username'] = $question;
        }

        if (!$input->getArgument('password')){
            $question = new Question('Le nouveau mot de passe : ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question){
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
