<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CourseRepository;
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
 * A Course is a course within a program in which participants can participate. Based on https://schema.org/Course.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "additionalType": "iexact",
 *     "organization": "iexact"
 * })
 */
class Course
{
    /**
     * @var UuidInterface The UUID identifier of this Course.
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
    private UuidInterface $id;

    /**
     * @var string The name of this Course.
     *
     * @example Werken met scrum en Github
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @var string The uri of the submitter (organization)
     *
     * @example https://dev.zuid-drecht.nl/api/v1/wrc/organizations/c571bdad-f34c-4e24-94e7-74629cfaccc9
     *
     * @Assert\Url
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private string $organization;

    /**
     * @var string The description of this Course.
     *
     * @example Deze cursus leert je de basics van werken met scrum en Github.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @var string The actual content of this Course.
     *
     * @example Github is echt awsome
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $text;

    /**
     * @var string The courseCode of this Course.
     *
     * @example SG123
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $courseCode;

    /**
     * @var array The coursePrerequisites of this Course.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $coursePrerequisites;

    /**
     * @var string An instance of a Course which is distinct from other instances because it is offered at a different time or location or through different media or modes of study or to a specific section of students.
     *
     * @example https://edu.conduction.nl/courses
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $hasCourseInstance;

    /**
     * @var int The numberOfCredits of this Course.
     *
     * @example 5
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $numberOfCredits;

    /**
     * @var string A description of the qualification, award, certificate, diploma or other occupational credential awarded as a consequence of successful completion of this course or program.
     *
     * @example Beschrijving van wat je krijgt bij het halen van deze cursus, bijvoorbeeld een certificaat.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $occupationalCredentialAwarded;

    /**
     * @var string A description of the qualification, award, certificate, diploma or other educational credential awarded as a consequence of successful completion of this course or program.
     *
     * @example Beschrijving van wat je krijgt bij het halen van deze cursus, bijvoorbeeld een certificaat.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $educationalCredentialAwarded;

    /**
     * @var Datetime The moment this Course was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $dateCreated;

    /**
     * @var Datetime The moment this Course was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $dateModified;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="course")
     * @MaxDepth(1)
     */
    private Collection $participants;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=Program::class, mappedBy="courses", cascade={"persist"})
     * @MaxDepth(1)
     */
    private Collection $programs;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity=EducationEvent::class, mappedBy="course", orphanRemoval=true, cascade={"persist"})
     * @MaxDepth(1)
     */
    private Collection $educationEvents;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="course", orphanRemoval=true,cascade={"persist"})
     * @MaxDepth(1)
     */
    private Collection $activities;

    /**
     * @var array An array of URLs pointing to skills related to this course
     *
     * @ORM\Column(type="simple_array", nullable=true)
     * @Groups({"read","write"})
     */
    private ?array $skills = [];

    /**
     * @var array An array of URLs pointing to competences this course teaches the participant
     *
     * @ORM\Column(type="simple_array", nullable=true)
     * @Groups({"read","write"})
     */
    private ?array $competences = [];

    /**
     * @var array An array of URLs pointing to products from the pdc related to this course
     *
     * @ORM\Column(type="simple_array", nullable=true)
     * @Groups({"read","write"})
     */
    private ?array $products = [];

    /**
     * @var string The Type of this course.
     *
     * @example Elearning, Readthrough, Skilltest.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $additionalType;

    /**
     * @var string The url linking to a video which belongs to this course
     *
     * @example https://dev.zuid-drecht.nl/api/v1/wrc/organizations/c571bdad-f34c-4e24-94e7-74629cfaccc9
     *
     * @Assert\Url
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $video;

    /**
     * @var string The time Required to complete this Course.
     *
     * @example Deze cursus leert je de basics van werken met scrum en Github. Based on schema.org
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $timeRequired;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->programs = new ArrayCollection();
        $this->educationEvents = new ArrayCollection();
        $this->activities = new ArrayCollection();
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

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCourseCode(): ?string
    {
        return $this->courseCode;
    }

    public function setCourseCode(?string $courseCode): self
    {
        $this->courseCode = $courseCode;

        return $this;
    }

    public function getCoursePrerequisites(): ?array
    {
        return $this->coursePrerequisites;
    }

    public function setCoursePrerequisites(?array $coursePrerequisites): self
    {
        $this->coursePrerequisites = $coursePrerequisites;

        return $this;
    }

    public function getHasCourseInstance(): ?string
    {
        return $this->hasCourseInstance;
    }

    public function setHasCourseInstance(?string $hasCourseInstance): self
    {
        $this->hasCourseInstance = $hasCourseInstance;

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

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(?array $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function getCompetences(): ?array
    {
        return $this->competences;
    }

    public function setCompetences(?array $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(?array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function getAdditionalType(): ?string
    {
        return $this->additionalType;
    }

    public function setAdditionalType(?string $additionalType): self
    {
        $this->additionalType = $additionalType;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getTimeRequired(): ?string
    {
        return $this->timeRequired;
    }

    public function setTimeRequired(?string $timeRequired): self
    {
        $this->timeRequired = $timeRequired;

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
            $participant->setCourse($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->setCourse(null);
        }

        return $this;
    }

    /**
     * @return Collection|program[]
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->addCourse($this);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->programs->contains($program)) {
            $this->programs->removeElement($program);
            $program->removeCourse($this);
        }

        return $this;
    }

    /**
     * @return Collection|EducationEvent[]
     */
    public function getEducationEvents(): Collection
    {
        return $this->educationEvents;
    }

    public function addEducationEvent(EducationEvent $educationEvent): self
    {
        if (!$this->educationEvents->contains($educationEvent)) {
            $this->educationEvents[] = $educationEvent;
            $educationEvent->setCourse($this);
        }

        return $this;
    }

    public function removeEducationEvent(EducationEvent $educationEvent): self
    {
        if ($this->educationEvents->contains($educationEvent)) {
            $this->educationEvents->removeElement($educationEvent);
            // set the owning side to null (unless already changed)
            if ($educationEvent->getCourse() === $this) {
                $educationEvent->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setCourse($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getCourse() === $this) {
                $activity->setCourse(null);
            }
        }

        return $this;
    }
}
