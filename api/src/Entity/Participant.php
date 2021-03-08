<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ParticipantRepository;
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
 * A Participant is a person who participates in a Course or an Program.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 *
 * @ApiFilter(BooleanFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(SearchFilter::class, properties={
 *     "person":"exact",
 *     "course.id":"exact",
 *     "program.id":"exact",
 *     "results.id":"exact",
 *     "status":"exact",
 *     "mentor":"exact"
 * })
 */
class Participant
{
    /**
     * @var UuidInterface The UUID identifier of this Participant.
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
     * @var string The contact of this Participant.
     *
     * @example https://cc.zuid-drecht.nl/people/{{uuid}]
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $person;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="participants")
     * @MaxDepth(1)
     */
    private ?Program $program;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="participants")
     * @MaxDepth(1)
     */
    private ?Course $course;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="participant", cascade={"remove"})
     * @MaxDepth(1)
     */
    private Collection $results;

    /**
     * @var Datetime The moment this Participant was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this Participant was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @var string The status of this Participant.
     *
     * @example pending
     *
     * @Groups({"read", "write"})
     * @Assert\Choice({"pending", "accepted", "rejected", "completed", "active"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var Datetime The date of acceptance of this Participant.
     *
     * @example 15-10-2020
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOfAcceptance;

    /**
     * @var string The motivation of this Participant.
     *
     * @example I love learning.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motivation;

    /**
     * @var string The mentor of this Participant.
     *
     * @example https://cc.zuid-drecht.nl/people/{{uuid}]
     *
     * @Assert\Url
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentor;

    /**
     * @var DateTime The moment this participation starts.
     *
     * @example 13-07-2020 13:00:00
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $startDate;

    /**
     * @var DateTime The moment this participation ends.
     *
     * @example 13-07-2020 13:00:00
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $endDate;

    /**
     * @var string the Organization that referred the participant
     *
     *  @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referredBy;

    /**
     * @var string The type of this Participant.
     *
     *  @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="participants")
     * @MaxDepth(1)
     */
    private $participantGroups;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=EducationEvent::class, mappedBy="participants")
     * @MaxDepth(1)
     */
    private $educationEvents;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->participantGroups = new ArrayCollection();
        $this->educationEvents = new ArrayCollection();
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

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(string $person): self
    {
        $this->person = $person;

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

    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram(Program $program): self
    {
        $this->program = $program;

        return $this;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setParticipant($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getParticipant() === $this) {
                $result->setParticipant(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateOfAcceptance(): ?\DateTimeInterface
    {
        return $this->dateOfAcceptance;
    }

    public function setDateOfAcceptance(?\DateTimeInterface $dateOfAcceptance): self
    {
        $this->dateOfAcceptance = $dateOfAcceptance;

        return $this;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(?string $motivation): self
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getMentor(): ?string
    {
        return $this->mentor;
    }

    public function setMentor(?string $mentor): self
    {
        $this->mentor = $mentor;

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

    public function getReferredBy(): ?string
    {
        return $this->referredBy;
    }

    public function setReferredBy(?string $referredBy): self
    {
        $this->referredBy = $referredBy;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getParticipantGroups(): Collection
    {
        return $this->participantGroups;
    }

    public function addParticipantGroup(Group $participantGroup): self
    {
        if (!$this->participantGroups->contains($participantGroup)) {
            $this->participantGroups[] = $participantGroup;
            $participantGroup->addParticipant($this);
        }

        return $this;
    }

    public function removeParticipantGroup(Group $participantGroup): self
    {
        if ($this->participantGroups->removeElement($participantGroup)) {
            $participantGroup->removeParticipant($this);
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
            $educationEvent->addParticipant($this);
        }

        return $this;
    }

    public function removeEducationEvent(EducationEvent $educationEvent): self
    {
        if ($this->educationEvents->removeElement($educationEvent)) {
            $educationEvent->removeParticipant($this);
        }

        return $this;
    }
}
