<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ProgramRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Program is a EducationalOccupationalProgram offered by an institution which determines the learning progress to achieve an outcome, usually a credential like a degree or certificate. Based on https://schema.org/EducationalOccupationalProgram.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=ProgramRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"provider": "iexact"})
 */
class Program
{
    /**
     * @var UuidInterface The UUID identifier of this Program.
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
     * @var string The name of this Program.
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
     * @var string The description of this Program.
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
     * @var DateTime The day that people can start to apply for this Program.
     *
     * @example 13-07-2020 12:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $applicationStartDate;

    /**
     * @var DateTime The day that people can no longer apply for this Program.
     *
     * @example 25-09-2020 20:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $applicationDeadline;

    /**
     * @var DateTime The moment this Program starts.
     *
     * @example 25-09-2020 13:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var DateTime The moment this Program ends.
     *
     * @example 25-09-2022 15:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var string The financialAidEligible of this Program.
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
     * @var int The numberOfCredits of this Program.
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
     * @example HBO
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
     * @var string The educationalProgramMode of this Program.
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
     * @var string The offers of this Program.
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
     * @var array The programPrerequisites of this Program.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $programPrerequisites;

    /**
     * @var string The programType of this Program.
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
     * @var string The provider of this Program.
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
     * @var string The salaryUponCompletion of this Program.
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
     * @var string The termDuration of this Program.
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
     * @var int The termsPerYear of this Program.
     *
     * @example 4
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $termsPerYear;

    /**
     * @var string The dayOfWeek of this Program.
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
     * @var string The timeOfDay of this Program.
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
     * @var string The timeToComplete of this Program.
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
     * @var string The trainingSalary of this Program.
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
     * @var int The typicalCreditsPerTerm of this Program.
     *
     * @example 15
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $typicalCreditsPerTerm;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="program", cascade={"remove"})
     * @MaxDepth(1)
     */
    private Collection $participants;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=Course::class, inversedBy="programs", cascade={"persist","remove"})
     * @MaxDepth(1)
     */
    private Collection $courses;

    /**
     * @var Datetime The moment this Program was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this Program was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

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

    public function getProgramPrerequisites(): ?array
    {
        return $this->programPrerequisites;
    }

    public function setProgramPrerequisites(?array $programPrerequisites): self
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

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setProgram($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->setElement(null);
            $participant->Program($this);
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
        }

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
