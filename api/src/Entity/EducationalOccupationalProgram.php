<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EducationalOccupationalProgramRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

/**
 * A EducationalOccupationalProgram is a program offered by an institution which determines the learning progress to achieve an outcome, usually a credential like a degree or certificate.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=EducationalOccupationalProgramRepository::class)
 */
class EducationalOccupationalProgram
{
    /**
     * @var UuidInterface The UUID identifier of this participant.
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string The name of this EducationalOccupationalProgram.
     *
     * @example associate degree informatica
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string The description of this EducationalOccupationalProgram.
     *
     * @example Deze studie leert je in 2 jaar tijd informatica skills op HBO denkniveau.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Date The day that people can start to apply for this EducationalOccupationalProgram.
     *
     * @example 13-07-2020
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="date", nullable=true)
     */
    private $applicationStartDate;

    /**
     * @var Date The day that people can no longer apply for this EducationalOccupationalProgram.
     *
     * @example 25-09-2020
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="date", nullable=true)
     */
    private $applicationDeadline;

    /**
     * @var DateTime The moment this EducationalOccupationalProgram starts.
     *
     * @example 25-09-2020 13:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var DateTime The moment this EducationalOccupationalProgram ends.
     *
     * @example 25-09-2022 15:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var string The financialAidEligible of this EducationalOccupationalProgram.
     *
     * @example Een beschrijving of verwijzing naar een programma voor financiële steun dat studenten kunnen gebruiken om het collegegeld of de kosten voor het programma te betalen.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $financialAidEligible;

    /**
     * @var int The maximum number of students who may be enrolled in the program..
     *
     * @example 100
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maximumEnrollment;

    /**
     * @var int The numberOfCredits of this EducationalOccupationalProgram.
     *
     * @example 120
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfCredits;

    /**
     * @var string A category describing the job, preferably using a term from a taxonomy such as BLS O*NET-SOC, ISCO-08 or similar.
     *
     * @example BLS
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $occupationalCategory;

    /**
     * @var string A description of the qualification, award, certificate, diploma or other occupational credential awarded as a consequence of successful completion of this course or program.
     *
     * @example Beschrijving van wat je krijgt bij het halen van deze studie, bijvoorbeeld een diploma.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $occupationalCredentialAwarded;

    /**
     * @var string A description of the qualification, award, certificate, diploma or other educational credential awarded as a consequence of successful completion of this course or program.
     *
     * @example Beschrijving van wat je krijgt bij het halen van deze studie, bijvoorbeeld een diploma.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $educationalCredentialAwarded;

    /**
     * @var string The educationalProgramMode of this EducationalOccupationalProgram.
     *
     * @example full-time
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $educationalProgramMode;

    /**
     * @var string The offers of this EducationalOccupationalProgram.
     *
     * @example https://pdc.conduction.nl/offers
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $offers;

    /**
     * @var string The programPrerequisites of this EducationalOccupationalProgram.
     *
     * @example Minimaal vmbo diploma gehaald.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $programPrerequisites;

    /**
     * @var string The programType of this EducationalOccupationalProgram.
     *
     * @example classroom, internship.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $programType;

    /**
     * @var string The provider of this EducationalOccupationalProgram.
     *
     * @example https://wrc.conduction.nl/organizations
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $provider;

    /**
     * @var string The salaryUponCompletion of this EducationalOccupationalProgram.
     *
     * @example €1000
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salaryUponCompletion;

    /**
     * @var string The termDuration of this EducationalOccupationalProgram.
     *
     * @example 2 maanden.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $termDuration;

    /**
     * @var int The termsPerYear of this EducationalOccupationalProgram.
     *
     * @example 4
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $termsPerYear;

    /**
     * @var string The dayOfWeek of this EducationalOccupationalProgram.
     *
     * @example van maandag tot zaterdag.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dayOfWeek;

    /**
     * @var string The timeOfDay of this EducationalOccupationalProgram.
     *
     * @example Overdag, soms in de avond.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timeOfDay;

    /**
     * @var string The timeToComplete of this EducationalOccupationalProgram.
     *
     * @example 2 jaar.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timeToComplete;

    /**
     * @var string The trainingSalary of this EducationalOccupationalProgram.
     *
     * @example €300 per maand.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trainingSalary;

    /**
     * @var int The typicalCreditsPerTerm of this EducationalOccupationalProgram.
     *
     * @example 15
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $typicalCreditsPerTerm;

    /**
     * @var Datetime The moment this EducationalOccupationalProgram was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this EducationalOccupationalProgram was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getApplicationStartDate(): ?\DateTimeInterface
    {
        return $this->applicationStartDate;
    }

    public function setApplicationStartDate(?\DateTimeInterface $applicationStartDate): self
    {
        $this->applicationStartDate = $applicationStartDate;

        return $this;
    }

    public function getApplicationDeadline(): ?\DateTimeInterface
    {
        return $this->applicationDeadline;
    }

    public function setApplicationDeadline(?\DateTimeInterface $applicationDeadline): self
    {
        $this->applicationDeadline = $applicationDeadline;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getFinancialAidEligible(): ?string
    {
        return $this->financialAidEligible;
    }

    public function setFinancialAidEligible(?string $financialAidEligible): self
    {
        $this->financialAidEligible = $financialAidEligible;

        return $this;
    }

    public function getMaximumEnrollment(): ?int
    {
        return $this->maximumEnrollment;
    }

    public function setMaximumEnrollment(?int $maximumEnrollment): self
    {
        $this->maximumEnrollment = $maximumEnrollment;

        return $this;
    }

    public function getNumberOfCredits(): ?int
    {
        return $this->numberOfCredits;
    }

    public function setNumberOfCredits(?int $numberOfCredits): self
    {
        $this->numberOfCredits = $numberOfCredits;

        return $this;
    }

    public function getOccupationalCategory(): ?string
    {
        return $this->occupationalCategory;
    }

    public function setOccupationalCategory(?string $occupationalCategory): self
    {
        $this->occupationalCategory = $occupationalCategory;

        return $this;
    }

    public function getOccupationalCredentialAwarded(): ?string
    {
        return $this->occupationalCredentialAwarded;
    }

    public function setOccupationalCredentialAwarded(?string $occupationalCredentialAwarded): self
    {
        $this->occupationalCredentialAwarded = $occupationalCredentialAwarded;

        return $this;
    }

    public function getEducationalCredentialAwarded(): ?string
    {
        return $this->educationalCredentialAwarded;
    }

    public function setEducationalCredentialAwarded(?string $educationalCredentialAwarded): self
    {
        $this->educationalCredentialAwarded = $educationalCredentialAwarded;

        return $this;
    }

    public function getEducationalProgramMode(): ?string
    {
        return $this->educationalProgramMode;
    }

    public function setEducationalProgramMode(?string $educationalProgramMode): self
    {
        $this->educationalProgramMode = $educationalProgramMode;

        return $this;
    }

    public function getOffers(): ?string
    {
        return $this->offers;
    }

    public function setOffers(?string $offers): self
    {
        $this->offers = $offers;

        return $this;
    }

    public function getProgramPrerequisites(): ?string
    {
        return $this->programPrerequisites;
    }

    public function setProgramPrerequisites(?string $programPrerequisites): self
    {
        $this->programPrerequisites = $programPrerequisites;

        return $this;
    }

    public function getProgramType(): ?string
    {
        return $this->programType;
    }

    public function setProgramType(?string $programType): self
    {
        $this->programType = $programType;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getSalaryUponCompletion(): ?string
    {
        return $this->salaryUponCompletion;
    }

    public function setSalaryUponCompletion(?string $salaryUponCompletion): self
    {
        $this->salaryUponCompletion = $salaryUponCompletion;

        return $this;
    }

    public function getTermDuration(): ?string
    {
        return $this->termDuration;
    }

    public function setTermDuration(?string $termDuration): self
    {
        $this->termDuration = $termDuration;

        return $this;
    }

    public function getTermsPerYear(): ?int
    {
        return $this->termsPerYear;
    }

    public function setTermsPerYear(?int $termsPerYear): self
    {
        $this->termsPerYear = $termsPerYear;

        return $this;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(?string $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getTimeOfDay(): ?string
    {
        return $this->timeOfDay;
    }

    public function setTimeOfDay(?string $timeOfDay): self
    {
        $this->timeOfDay = $timeOfDay;

        return $this;
    }

    public function getTimeToComplete(): ?string
    {
        return $this->timeToComplete;
    }

    public function setTimeToComplete(?string $timeToComplete): self
    {
        $this->timeToComplete = $timeToComplete;

        return $this;
    }

    public function getTrainingSalary(): ?string
    {
        return $this->trainingSalary;
    }

    public function setTrainingSalary(?string $trainingSalary): self
    {
        $this->trainingSalary = $trainingSalary;

        return $this;
    }

    public function getTypicalCreditsPerTerm(): ?int
    {
        return $this->typicalCreditsPerTerm;
    }

    public function setTypicalCreditsPerTerm(?int $typicalCreditsPerTerm): self
    {
        $this->typicalCreditsPerTerm = $typicalCreditsPerTerm;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }
}
