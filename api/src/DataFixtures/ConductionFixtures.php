<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Course;
use App\Entity\Participant;
use App\Entity\Program;
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
            // If build all fixtures is true we build all the fixtures
            !$this->params->get('app_build_all_fixtures') &&
            // Specific domain names
            $this->params->get('app_domain') != 'conduction.nl' && strpos($this->params->get('app_domain'), 'conduction.nl') == false
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

        // Test programma
        $program = new Program();
        $program->setName('Test programma');
        $program->setDescription('Dit is een programma om mee te testen.');
        $program->setProvider($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'6a001c4c-911b-4b29-877d-122e362f519d']));
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        $prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'0bf92c4a-0ef3-4184-a14a-4356f735498e']);
        $program->setProgramPrerequisites($prerequisites);
        $date = new \DateTime();
        $date->add(new \DateInterval('P5W'));
        $program->setApplicationDeadline($date);
        $program->setStartDate($date);
        $program->setOccupationalCategory('MBO');
        $program->setTimeToComplete('1 jaar');
        $program->setEducationalProgramMode('full-time');
        $program->setTrainingSalary('€123 per maand');
        $program->setNumberOfCredits('25');
        $program->setOccupationalCredentialAwarded('Software Developer');
        $program->setEducationalCredentialAwarded('Beschrijving van wat je krijgt bij het halen van dit programma, bijvoorbeeld een diploma, certificaat en/of titel.');

        // Test Cursus
        $course = new Course();
        $course->setName('Scrum gericht werken en Github');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $course->setCourseCode('SG1');
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        $course->setCoursePrerequisites($prerequisites);
        $course->setNumberOfCredits(5);
        $course->setOccupationalCredentialAwarded('Een mooie Conduction sticker en een high five');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        $manager->persist($program);
        $manager->flush();

        // Het online stage programma
        $program = new Program();
        $program->setName('Voorbereiding online stage');
        $program->setDescription('Tijdens dit programma wordt je voorbereid op het online lopen van een stage bij een commonground gemeente of organisatie.');

        // W
        $id = Uuid::fromString('4bb8034c-2f74-4637-801d-9c2c0cb43b92');
        $course = new Course();
        $course->setName('Introductie');
        $course->setDescription('Hier komt een introductie over de tutorials.');
        $manager->persist($course);
        $course->setId($id);
        $manager->persist($course);
        $manager->flush();
        $course = $manager->getRepository('App:Course')->findOneBy(['id'=> $id]);
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Maak een acount aan voor Github');
        $activity->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $activity->setEducationalUse('assignment');
        $course->addActivity($activity);

        $activity = new Activity();
        $activity->setName('Maak een acount aan voor Docker');
        $activity->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $activity->setEducationalUse('assignment');
        $course->addActivity($activity);

        $activity = new Activity();
        $activity->setName('Wat gaan we doen?');
        $activity->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // W
        $id = Uuid::fromString('0bf92c4a-0ef3-4184-a14a-4356f735498e');
        $course = new Course();
        $course->setName('Agile en Scrum');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $manager->persist($course);
        $course->setId($id);
        $manager->persist($course);
        $manager->flush();
        $course = $manager->getRepository('App:Course')->findOneBy(['id'=> $id]);
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // W
        $course = new Course();
        $course->setName('Git en versiebeheer');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // W
        $course = new Course();
        $course->setName('Userinterface en NL Design');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // W
        $course = new Course();
        $course->setName('Architectuur en componenten');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // W
        $course = new Course();
        $course->setName('API Design en Datamodellen');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // W
        $course = new Course();
        $course->setName('Protocomponent');
        $course->setDescription('Deze tutorial leert je scrum gericht werken door onder andere Github.');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Afsluitende test');
        $activity->setDescription('');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        $manager->persist($program);

        $manager->flush();
    }
}
