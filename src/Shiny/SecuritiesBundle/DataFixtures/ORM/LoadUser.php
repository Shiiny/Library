<?php

namespace Shiny\SecuritiesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shiny\SecuritiesBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Les noms d'utilisateurs à créer
        $names = [
            ['username' => 'Alexandre',
            'plainPassword' => 'alexandre'],
            ['username' => 'Marine',
            'plainPassword' => 'marine'],
            ['username' => 'Anna',
            'plainPassword' => 'anna'],
            ['username' => 'Shiny',
            'plainPassword' => 'shiny']
        ];

        foreach ($names as $name) {
            // On crée l'utilisateur
            $user = new User();

            $user->setUsername($name['username']);
            $user->setEmail($name['username'].'@test.com');
            $user->setPlainPassword($name['plainPassword']);

            // On la persiste
            $manager->persist($user);
        }

        // On déclenche l'enregistrement de toutes les compétences
        $manager->flush();
    }
}