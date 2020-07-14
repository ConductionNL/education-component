<?php

namespace App\DataFixtures;

use App\Entity\Course;
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
        $id = Uuid::fromString('2b7b60ab-d3db-4164-901d-3c4230e1db82');
        $testStudent = new Participant();
        $testStudent->setPerson($this->commonGroundService->cleanUrl(['component'=>'cc', 'type'=>'people', 'id'=>'d961291d-f5c1-46f4-8b4a-6abb41df88db']));
        $manager->persist($testStudent);
        $testStudent->setId($id);
        $manager->persist($testStudent);
        $manager->flush();
        $testStudent = $manager->getRepository('App:Participant')->findOneBy(['id'=> $id]);

        // Test Tutorial
        $id = Uuid::fromString('f8d6be3c-c985-4a5b-8497-9d03d9a0580a');
        $testTutorial = new Course();
        $testTutorial->setName("Scrum gericht werken en Github");
        $testTutorial->setDescription("Deze tutorial leert je scrum gericht werken door onder andere Github.");
        $testTutorial->setCourseCode("SG1");
        $testTutorial->setCoursePrerequisites("Een vmbo diploma of hoger.");
        $testTutorial->setNumberOfCredits(5);
        $testTutorial->setOccupationalCredentialAwarded("Een mooie Conduction sticker en een high five");
        $manager->persist($testTutorial);
        $testTutorial->setId($id);
        $manager->persist($testTutorial);
        $manager->flush();
        $testTutorial = $manager->getRepository('App:Course')->findOneBy(['id'=> $id]);

        $manager->flush();
    }
}
