<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Repository\GroupRepository;
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
 * An activity like a class on a cource.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Table(name="group_table")
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 *
 * @ApiFilter(SearchFilter::class, properties={
 *      "mentors": "partial",
 *      "course.id": "partial",
 *      "course.organization": "partial",
 *      "id": "partial"
 * })
 *
 * @ApiFilter(DateFilter::class, properties = {
 *     "startDate", "endDate"
 *     })
 */
class Group
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
     * @var string The name of this Group.
     *
     * @example A213
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
     * @var string The description of this Group.
     *
     * @example Deze group werkt samen aan werken met scrum en Github.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="participantGroups", cascade={"remove"})
     * @MaxDepth(1)
     */
    private Collection $participants;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="courseGroups")
     * @MaxDepth(1)
     */
    private ?Course $course;

    /**
     * @var DateTime The moment this group starts.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $startDate;

    /**
     * @var DateTime The moment this group ends.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $endDate;

    /**
     * @var int The minimum number of participants who may be enrolled in the group.
     *
     * @example 100
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minParticipations;

    /**
     * @var int The maximum number of participants who may be enrolled in the group.
     *
     * @example 120
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxParticipations;

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
     * @var array The mentors of this group.
     *
     * @example https://cc.zuid-drecht.nl/people/{{uuid}]
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $mentors = [];

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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

    public function getMinParticipations(): ?int
    {
        return $this->minParticipations;
    }

    public function setMinParticipations(?int $minParticipations): self
    {
        $this->minParticipations = $minParticipations;

        return $this;
    }

    public function getMaxParticipations(): ?int
    {
        return $this->maxParticipations;
    }

    public function setMaxParticipations(?int $maxParticipations): self
    {
        $this->maxParticipations = $maxParticipations;

        return $this;
    }

    public function getMentors(): ?array
    {
        return $this->mentors;
    }

    public function setMentors(?array $mentors): self
    {
        $this->mentors = $mentors;

        return $this;
    }
}
