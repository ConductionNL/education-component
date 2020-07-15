<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Participant;
use App\Entity\Program;
use App\Entity\EducationalEvent;

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

        // Het online stage programma
        $program = new Program();
        $program->setName('Online commonground stage');
        $program->setDescription('Tijdens dit programma wordt je voorbereid op het online lopen van een stage bij een commonground gemeente of orgnaisatie.');

        // W
        $course = new Course();
        $course->setName('Introductie');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // W
        $course = new Course();
        $course->setName('Agile en Scrum');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // W
        $course = new Course();
        $course->setName('Git en versiebeheer');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // W
        $course = new Course();
        $course->setName('Userinterface en NL Design');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // W
        $course = new Course();
        $course->setName('Architectuur en componenten');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // W
        $course = new Course();
        $course->setName('API Design en Datamodellen');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // W
        $course = new Course();
        $course->setName('Protocomponent');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        // Test Tutorial
        $course = new Course();
        $course->setName('Scrum gericht werken en Github');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $course->setCourseCode('SG1');
        $course->setCoursePrerequisites('Een vmbo diploma of hoger.');
        $course->setNumberOfCredits(5);
        $course->setOccupationalCredentialAwarded('Een mooie Conduction sticker en een high five');
        $manager->persist($course);
        $program->addCourse($course);


        $manager->flush();
    }
}
