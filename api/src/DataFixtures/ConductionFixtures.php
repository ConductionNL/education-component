<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConductionFixtures extends Fixture
{
    private $params;
    /**
     * @var CommonGroundService
     */
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->commonGroundService = $commonGroundService;
    }

    public function load(ObjectManager $manager)
    {
        if (
            $this->params->get('app_domain') != 'conduction.nl' &&
            strpos($this->params->get('app_domain'), 'conduction.nl') == false
        ) {
            return false;
        }
        // Test Student
        $id = Uuid::fromString('d961291d-f5c1-46f4-8b4a-6abb41df88db');
        $testStudent = new Participant();
        $testStudent->setPerson($this->commonGroundService->cleanUrl(['component'=>'cc', 'type'=>'people', 'id'=>'d961291d-f5c1-46f4-8b4a-6abb41df88db']));
        $manager->persist($testStudent);
        $testStudent->setId($id);
        $manager->persist($testStudent);
        $manager->flush();
        $testStudent = $manager->getRepository('App:Participant')->findOneBy(['id'=> $id]);

        $manager->flush();
    }
}
