<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
    private $id;

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
    private $name;

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
    private $description;

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
    private $courseCode;

    /**
     * @var array The coursePrerequisites of this Course.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $coursePrerequisites;

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
    private $hasCourseInstance;

    /**
     * @var int The numberOfCredits of this Course.
     *
     * @example 5
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfCredits;

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
    private $occupationalCredentialAwarded;

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
    private $educationalCredentialAwarded;

    /**
     * @var Datetime The moment this Course was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this Course was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="courses")
     * @MaxDepth(1)
     */
    private $participants;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=Program::class, mappedBy="courses", cascade={"persist"})
     * @MaxDepth(1)
     */
    private $programs;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity=EducationEvent::class, mappedBy="course", orphanRemoval=true, cascade={"persist"})
     * @MaxDepth(1)
     */
    private $educationEvents;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="course", orphanRemoval=true,cascade={"persist"})
     * @MaxDepth(1)
     */
    private $activities;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $participant->addCourse($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->removeCourse($this);
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
