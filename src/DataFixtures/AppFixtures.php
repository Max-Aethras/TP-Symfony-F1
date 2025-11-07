<?php

namespace App\DataFixtures;

use App\Entity\Ecurie;
use App\Entity\Pilote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const ECURIE_REF = 'ecurie-';

    public function load(ObjectManager $manager): void
    {
        $ecuriesData = [
            'MERCEDES' => ['nom' => 'Mercedes-AMG F1', 'moteur' => 'Mercedes'],
            'RED_BULL' => ['nom' => 'Red Bull Racing', 'moteur' => 'Honda RBPT'],
            'FERRARI' => ['nom' => 'Scuderia Ferrari', 'moteur' => 'Ferrari'],
            'MCLAREN' => ['nom' => 'McLaren F1 Team', 'moteur' => 'Mercedes'],
            'ASTON_MARTIN' => ['nom' => 'Aston Martin Aramco F1 Team', 'moteur' => 'Mercedes'],
            'ALPINE' => ['nom' => 'BWT Alpine F1 Team', 'moteur' => 'Renault'],
            'WILLIAMS' => ['nom' => 'Williams Racing', 'moteur' => 'Mercedes'],
            'HAAS' => ['nom' => 'Haas F1 Team', 'moteur' => 'Ferrari'],
            'RB' => ['nom' => 'Visa Cash App RB F1 Team', 'moteur' => 'Honda RBPT'],
            'KICK_SAUBER' => ['nom' => 'Stake F1 Team Kick Sauber', 'moteur' => 'Ferrari'],
        ];

        $ecuries = [];
        foreach ($ecuriesData as $key => $e) {
            $ecurie = new Ecurie();
            $ecurie->setNom($e['nom'])->setMoteur($e['moteur']);
            $manager->persist($ecurie);
            $ecuries[$key] = $ecurie;
            $this->addReference(self::ECURIE_REF . $key, $ecurie);
        }

        $pilotesData = [
            ['prenom' => 'George', 'nom' => 'Russell', 'ecurie' => 'MERCEDES', 'points' => 12, 'statut' => 'actif', 'start' => '2019-03-17'],
            ['prenom' => 'Andrea', 'nom' => 'Kimi Antonelli', 'ecurie' => 'MERCEDES', 'points' => 12, 'statut' => 'actif', 'start' => '2025-03-16'],
            ['prenom' => 'Mick', 'nom' => 'Schumacher', 'ecurie' => 'MERCEDES', 'points' => 12, 'statut' => 'reserve', 'start' => '2021-03-28'],

            ['prenom' => 'Max', 'nom' => 'Verstappen', 'ecurie' => 'RED_BULL', 'points' => 12, 'statut' => 'actif', 'start' => '2015-03-15'],
            ['prenom' => 'Sergio', 'nom' => 'Perez', 'ecurie' => 'RED_BULL', 'points' => 12, 'statut' => 'actif', 'start' => '2011-03-27'],
            ['prenom' => 'Liam', 'nom' => 'Lawson', 'ecurie' => 'RED_BULL', 'points' => 12, 'statut' => 'reserve', 'start' => '2023-08-27'],

            ['prenom' => 'Charles', 'nom' => 'Leclerc', 'ecurie' => 'FERRARI', 'points' => 12, 'statut' => 'actif', 'start' => '2018-03-25'],
            ['prenom' => 'Lewis', 'nom' => 'Hamilton', 'ecurie' => 'FERRARI', 'points' => 12, 'statut' => 'actif', 'start' => '2007-03-18'],
            ['prenom' => 'Oliver', 'nom' => 'Bearman', 'ecurie' => 'FERRARI', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],

            ['prenom' => 'Lando', 'nom' => 'Norris', 'ecurie' => 'MCLAREN', 'points' => 12, 'statut' => 'actif', 'start' => '2019-03-17'],
            ['prenom' => 'Oscar', 'nom' => 'Piastri', 'ecurie' => 'MCLAREN', 'points' => 12, 'statut' => 'actif', 'start' => '2023-03-05'],
            ['prenom' => 'Pato', 'nom' => 'O\'Ward', 'ecurie' => 'MCLAREN', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],

            ['prenom' => 'Fernando', 'nom' => 'Alonso', 'ecurie' => 'ASTON_MARTIN', 'points' => 12, 'statut' => 'actif', 'start' => '2001-03-04'],
            ['prenom' => 'Lance', 'nom' => 'Stroll', 'ecurie' => 'ASTON_MARTIN', 'points' => 12, 'statut' => 'actif', 'start' => '2017-03-26'],
            ['prenom' => 'Felipe', 'nom' => 'Drugovich', 'ecurie' => 'ASTON_MARTIN', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],

            ['prenom' => 'Pierre', 'nom' => 'Gasly', 'ecurie' => 'ALPINE', 'points' => 12, 'statut' => 'actif', 'start' => '2017-10-01'],
            ['prenom' => 'Jack', 'nom' => 'Doohan', 'ecurie' => 'ALPINE', 'points' => 12, 'statut' => 'actif', 'start' => '2025-03-16'],
            ['prenom' => 'Victor', 'nom' => 'Martins', 'ecurie' => 'ALPINE', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],

            ['prenom' => 'Alex', 'nom' => 'Albon', 'ecurie' => 'WILLIAMS', 'points' => 12, 'statut' => 'actif', 'start' => '2019-03-17'],
            ['prenom' => 'Carlos', 'nom' => 'Sainz', 'ecurie' => 'WILLIAMS', 'points' => 12, 'statut' => 'actif', 'start' => '2015-03-15'],
            ['prenom' => 'Franco', 'nom' => 'Colapinto', 'ecurie' => 'WILLIAMS', 'points' => 12, 'statut' => 'reserve', 'start' => '2024-09-01'],

            ['prenom' => 'Kevin', 'nom' => 'Magnussen', 'ecurie' => 'HAAS', 'points' => 12, 'statut' => 'actif', 'start' => '2014-03-16'],
            ['prenom' => 'Esteban', 'nom' => 'Ocon', 'ecurie' => 'HAAS', 'points' => 12, 'statut' => 'actif', 'start' => '2016-08-28'],
            ['prenom' => 'Pietro', 'nom' => 'Fittipaldi', 'ecurie' => 'HAAS', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],

            ['prenom' => 'Yuki', 'nom' => 'Tsunoda', 'ecurie' => 'RB', 'points' => 12, 'statut' => 'actif', 'start' => '2021-03-28'],
            ['prenom' => 'Daniel', 'nom' => 'Ricciardo', 'ecurie' => 'RB', 'points' => 12, 'statut' => 'actif', 'start' => '2011-07-24'],
            ['prenom' => 'Isack', 'nom' => 'Hadjar', 'ecurie' => 'RB', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],

            ['prenom' => 'Nico', 'nom' => 'Hülkenberg', 'ecurie' => 'KICK_SAUBER', 'points' => 12, 'statut' => 'actif', 'start' => '2010-03-14'],
            ['prenom' => 'Gabriel', 'nom' => 'Bortoleto', 'ecurie' => 'KICK_SAUBER', 'points' => 12, 'statut' => 'actif', 'start' => '2025-03-16'],
            ['prenom' => 'Théo', 'nom' => 'Pourchaire', 'ecurie' => 'KICK_SAUBER', 'points' => 12, 'statut' => 'reserve', 'start' => '2025-03-16'],
        ];

        foreach ($pilotesData as $data) {
            $pilote = new Pilote();
            $pilote->setPrenom($data['prenom'])
                   ->setNom($data['nom'])
                   ->setPoints($data['points'])
                   ->setDateStart(new \DateTime($data['start']))
                   ->setStatut($data['statut'])
                   ->setEcurie($ecuries[$data['ecurie']]);

            $manager->persist($pilote);
        }

        $manager->flush();
    }
}