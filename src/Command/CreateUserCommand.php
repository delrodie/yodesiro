<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';

    private $em;
    private $passwordEnconder;
    private $validator;
    private  $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, ValidatorInterface $validator, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->passwordEnconder = $passwordEncoder;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creer un nouvel utilisateur')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, "L'adresse email de l'utilisateur"),
                new InputArgument('username', InputArgument::REQUIRED, 'Le pseudo de  l\'utilisateur'),
                new InputArgument('password', InputArgument::REQUIRED, "Le mot de passe"),
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $user = new User();
        $email = $input->getArgument('email');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $password = $this->passwordEnconder->encodePassword($user, $password);

        // Si le nom utilisateur ou l'adresse email existent alors renvoyer false
        $verifUserUsername = $this->userRepository->findOneBy(['username'=>$username]);
        $verifUserEmail = $this->userRepository->findOneBy(['email'=>$email]);
        if ($verifUserUsername){
            $message = "Attention le nom utilisateur ".$username." existe déjà";
            $io->error($message);

            return 2;
        }
        if ($verifUserEmail){
            $message = "Attention l'adresse email ".$email." existe déjà";
            $io->error($message);

            return 2;
        }

        // verifier le format de l'adresse email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $message = "OUPS!! L'adresse email renseignée n'est pas au bon format";
            $io->error($message);

            return 2;
        }

        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($password);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->persist($user);
        $this->em->flush();

        //$output->writeln(sprintf("Ajout effectué avec succès de l'utilisateur %s", $username));
        $io->success("Ajout effectué avec succès de l'utilisateur");

        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        // Adresse email
        if (!$input->getArgument('email')){
            $question = new Question("Veuillez entrer l'adresse email : ");
            $questions['email'] = $question;
        }

        // Nom utilisateur
        if (!$input->getArgument('username')){
            $question = new Question("Veuillez entrer le pseudo : ");
            $questions['username'] = $question;
        }

        // Mot de passe
        if (!$input->getArgument('password')){
            $question = new Question("Veuillez entrer le mot de passe : ");
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
