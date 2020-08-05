<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminCreateUsersCommand extends Command
{
    protected static $defaultName = 'admin:create:users';
    protected $passwordEncoder;
    protected $em;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->passwordEncoder = $passwordEncoder;
        $this->em = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create an user and an admin.')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option shit values')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reset des tables');
        $this->resetTable($io,'user');

        $users = array(
            [
                'username' => 'shanbo',
                'email' => 'chanbora.chhun@outlook.fr',
                'roles' => ['ROLE_USER','ROLE_ADMIN', 'ROLE_SUPER_ADMIN']
            ],
            [
                'username' => 'staro',
                'email' => 'starozytny@hotmail.fr',
                'roles' => ['ROLE_USER','ROLE_ADMIN']
            ],
            [
                'username' => 'shanks',
                'email' => 'starozytny@hotmail.fr',
                'roles' => ['ROLE_USER']
            ]
        );

        $io->title('Création des utilisateurs');
        foreach ($users as $user) {
            $new = (new User())
                ->setUsername($user['username'])
                ->setEmail($user['email'])
                ->setRoles($user['roles'])
                ->setIsNew(false)
                ->setAvatar('profil.jpg')
            ;

            $new->setPassword($this->passwordEncoder->encodePassword(
                $new, 'azerty'
            ));

            $this->em->persist($new);
            $io->text('USER : ' . $user['username'] . ' créé' );
        }

        if ($input->getOption('option')) {
            $io->title('Création des utilisateurs lambdas');
            for($i=0; $i<110 ; $i++) {
                $new = (new User())
                    ->setUsername("Utilisateur " . $i)
                    ->setEmail("utilisateur".$i."@utilisateur.fr")
                    ->setRoles(['ROLE_USER'])
                    ->setIsNew(false)
                ;

                if ($i<5) {
                    $new->setIsNew(true);
                }

                $new->setPassword($this->passwordEncoder->encodePassword(
                    $new, 'azerty'
                ));

                $this->em->persist($new);
                $io->text('USER : ' . "Utilisateur " . $i . ' créé' );
            }
        }

        
        $this->em->flush();

        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return 0;
    }

    protected function resetTable(SymfonyStyle $io, $item)
    {
        $connection = $this->em->getConnection();

        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->executeUpdate(
                $connection->getDatabasePlatform()->getTruncateTableSQL(
                    $item, true
                )
            );
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();

        } catch (DBALException $e) {
            $io->error('Reset [FAIL] : ' . $e);
        }
        $io->text('Reset [OK]');
    }
}
