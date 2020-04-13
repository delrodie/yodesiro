<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRoleCommand extends Command
{
    protected static $defaultName = 'app:user:role';

    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription("Ajouter le nouveau role de l'utilsateur")
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, "Le nom utilsateur"),
                new InputArgument('role', InputArgument::REQUIRED, "Le role de l'utilsateur")
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $user = $this->em->getRepository(User::class)->findOneByUsername($username);

        /*if ($username) {
            $io->note(sprintf('You passed an argument: %s', $username));
        }*/

        if (!$user){
            $message = $username.' ne correspond a aucun utilisateur';
            $io->error($message);
            return  2;
        }

        $role = $input->getArgument('role');

        $roles = $user->setRoles([$role]);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->flush();

        $message = "Nouveau role definit pour l'utilisateur ".$username;
        $io->success($message);

        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        // Le nom utilisatur
        if (!$input->getArgument('username')){
            $question = new Question("Veuillez entrer le utilisateur : ");
            $questions['username'] = $question;
        }

        // Le role
        if (!$input->getArgument('role')){
            $question = new Question("Veuillez entrer le nouveau role : ");
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question){
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
