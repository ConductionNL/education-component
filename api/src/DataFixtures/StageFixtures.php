<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Course;
use App\Entity\Participant;
use App\Entity\Program;
use App\Entity\Question;
use App\Entity\Stage;
use App\Entity\Test;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StageFixtures extends Fixture
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
            $this->params->get('app_domain') != 'zuiddrecht.nl' && strpos($this->params->get('app_domain'), 'zuiddrecht.nl') == false &&
            $this->params->get('app_domain') != 'zuid-drecht.nl' && strpos($this->params->get('app_domain'), 'zuid-drecht.nl') == false &&
            $this->params->get('app_domain') != 'conduction.academy' && strpos($this->params->get('app_domain'), 'conduction.academy') == false
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
        $id = Uuid::fromString('d7c49d9f-5e16-4035-8558-9b2aa007aabe');
        $program = new Program();
        $program->setName('Test programma');
        $program->setDescription('Dit is een programma om mee te testen.');
        $program->setProvider($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'ff0662b1-8393-467d-bddb-8a3d4ae521a5']));
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        $prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'0bf92c4a-0ef3-4184-a14a-4356f735498e']);
        $program->setProgramPrerequisites($prerequisites);
        $manager->persist($program);
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
        $manager->persist($program);
        $program->setId($id);
        $manager->persist($program);
        $manager->flush();
        $program = $manager->getRepository('App:Program')->findOneBy(['id'=> $id]);

        // Test Tutorial
        $course = new Course();
        $course->setName('Test Tutorial');
        $course->setDescription('Dit is een tutorial om mee te testen.');
        $course->setCourseCode('TT1');
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        $course->setCoursePrerequisites($prerequisites);
        $course->setNumberOfCredits(5);
        $course->setOccupationalCredentialAwarded('Een mooie Conduction sticker en een high five');
        $program->addCourse($course);

        $activity = new Activity();
        $activity->setName('Test Activiteit');
        $activity->setDescription('Test beschrijving');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        $activity = new Activity();
        $activity->setName('Test Activiteit2');
        $activity->setDescription('Test beschrijving');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        $activity = new Activity();
        $activity->setName('Test Activiteit3');
        $activity->setDescription('Test beschrijving');
        $activity->setEducationalUse('test');
        $course->addActivity($activity);

        // Test Test
        $test = new Test();
        $test->setName('Test test');
        $test->setDescription('Dit is een test om tests mee te testen ;)');
        $activity->addTest($test);

        $stage = new Stage();
        $stage->setName('Vragenlijst 1');
        $stage->setDescription('Beantwoord deze vragen');
        $stage->setOrderNumber(0);

        $question = new Question();
        $question->setName('Vraag 1');
        $question->setDescription('Wat is het antwoord op deze vraag?');
        $question->setAnswer('Wat');
        $answerOptions = [];
        $answerOptions[0] = 'het antwoord';
        $answerOptions[1] = 'Welke vraag?';
        $answerOptions[2] = 'Wat';
        $question->setAnswerOptions($answerOptions);
        $question->setOrderNumber(0);
        $stage->addQuestion($question);

        $question = new Question();
        $question->setName('Vraag 2');
        $question->setDescription('Is dit een goeie vraag?');
        $question->setAnswer('ja');
        $answerOptions = [];
        $answerOptions[0] = 'ja';
        $answerOptions[1] = 'nee';
        $question->setAnswerOptions($answerOptions);
        $question->setOrderNumber(1);
        $stage->addQuestion($question);

        $question = new Question();
        $question->setName('Vraag 3');
        $question->setDescription('Is dit een multiple choice vraag?');
        $question->setAnswer('nee');
        $question->setOrderNumber(2);
        $stage->addQuestion($question);

        $test->addStage($stage);

        $stage = new Stage();
        $stage->setName('Vragenlijst 2');
        $stage->setDescription('Beantwoord ook deze vragen');
        $stage->setOrderNumber(1);

        $question = new Question();
        $question->setName('Vraag 1');
        $question->setDescription('Wie heeft deze slechte vragen bedacht?');
        $question->setAnswer('Wilco Louwerse');
        $question->setOrderNumber(0);
        $stage->addQuestion($question);

        $question = new Question();
        $question->setName('Vraag 2');
        $question->setDescription('Wie heeft deze slechte vragen beantwoord?');
        $question->setAnswer('ik');
        $question->setOrderNumber(1);
        $stage->addQuestion($question);

        $question = new Question();
        $question->setName('Vraag 3');
        $question->setDescription('Geven de mogelijke antwoorden op deze vraag je keuzestress?');
        $question->setAnswer('ja');
        $answerOptions = [];
        $answerOptions[0] = 'ja';
        $answerOptions[1] = 'nee';
        $answerOptions[2] = 'misschien';
        $answerOptions[3] = 'ja, dit zijn echt te veel opties';
        $answerOptions[4] = 'niet zeker, even aan mijn therapist vragen';
        $answerOptions[5] = 'nee, dit antwoord is duidelijk de juiste';
        $answerOptions[6] = '?¿?';
        $question->setAnswerOptions($answerOptions);
        $question->setOrderNumber(2);
        $stage->addQuestion($question);

        $question = new Question();
        $question->setName('Vraag 4');
        $question->setDescription('Wat is 1+1?');
        $question->setAnswer('2');
        $answerOptions = [];
        $answerOptions[0] = '0';
        $answerOptions[1] = '1';
        $answerOptions[2] = '2';
        $answerOptions[3] = '3';
        $question->setAnswerOptions($answerOptions);
        $question->setOrderNumber(3);
        $stage->addQuestion($question);

        $test->addStage($stage);

        $manager->persist($program);
        $manager->flush();

        // Test programma deadline
        $id = Uuid::fromString('cd399f79-ac21-4a4e-ab3a-7ecc536fc8ca');
        $program = new Program();
        $program->setName('Deadline test programma');
        $program->setDescription('Dit is een programma om mee te testen of de deadline is verlopen melding goed werkt.');
        $program->setProvider($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'ff0662b1-8393-467d-bddb-8a3d4ae521a5']));
        $manager->persist($program);
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));
        $program->setApplicationDeadline($date);
        $program->setStartDate($date);
        $manager->persist($program);
        $program->setId($id);
        $manager->persist($program);
        $manager->flush();
        $program = $manager->getRepository('App:Program')->findOneBy(['id'=> $id]);
        $manager->persist($program);
        $manager->flush();

        // Het online stage programma
        $id = Uuid::fromString('6f408aae-4a35-4ad3-a829-a87627714bca');
        $program = new Program();
        $program->setName('Voorbereiding online stage');
        $program->setDescription('Tijdens dit programma wordt je voorbereid op het online lopen van een stage bij een commonground gemeente of organisatie.');
        $program->setProvider($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'ff0662b1-8393-467d-bddb-8a3d4ae521a5']));
        $manager->persist($program);
        $program->setId($id);
        $manager->persist($program);
        $manager->flush();
        $program = $manager->getRepository('App:Program')->findOneBy(['id'=> $id]);

        // W
        $id = Uuid::fromString('4bb8034c-2f74-4637-801d-9c2c0cb43b92');
        $course = new Course();
        $course->setName('Introductie');
        $course->setDescription('Hier komt een introductie over de tutorials.');
        //$prerequisites = [];
        //$prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        //$course->setCoursePrerequisites($prerequisites);
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
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        //$prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        $course->setCoursePrerequisites($prerequisites);
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
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        //$prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        $course->setCoursePrerequisites($prerequisites);
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
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        //$prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        $course->setCoursePrerequisites($prerequisites);
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
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        //$prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        $course->setCoursePrerequisites($prerequisites);
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
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        //$prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        $course->setCoursePrerequisites($prerequisites);
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
        $prerequisites = [];
        $prerequisites[0] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'courses', 'id'=>'4bb8034c-2f74-4637-801d-9c2c0cb43b92']);
        //$prerequisites[1] = $this->commonGroundService->cleanUrl(['component'=>'edu', 'type'=>'programs', 'id'=>'6f408aae-4a35-4ad3-a829-a87627714bca']);
        $course->setCoursePrerequisites($prerequisites);
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
