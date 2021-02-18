<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\EducationEventRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An EducationEvent is a (online) meeting, college etc. of a course.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=EducationEventRepository::class)
 * @ApiFilter(SearchFilter::class, properties={
 *     "person": "exact"
 * })
 */
class EducationEvent
{
    /**
     * @var UuidInterface The UUID identifier of this EducationEvent.
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
     * @var string The name of this EducationEvent.
     *
     * @example Les 1 over Github
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
     * @var string The description of this EducationEvent.
     *
     * @example Dit is de eerste online les over Github.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string The assesses of this EducationEvent.
     *
     * @example beoordelingscriteria
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $assesses;

    /**
     * @var string The educationalLevel of this EducationEvent.
     *
     * @example beginner
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $educationalLevel;

    /**
     * @var string The teaches of this EducationEvent.
     *
     * @example In deze eerste les zal je de basics leren van Github zoals het clonen van en repository.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $teaches;

    /**
     * @var DateTime The moment this EducationEvent starts.
     *
     * @example 13-07-2020 13:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var DateTime The moment this EducationEvent ends.
     *
     * @example 13-07-2020 15:00:00
     *
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var string The person of this education event.
     *
     * @example https://cc.zuid-drecht.nl/people/{{uuid}}
     *
     * @Assert\Url
     * @Assert\NotNull()
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $person;

    /**
     * @var Datetime The moment this EducationEvent was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this EducationEvent was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="educationEvents")
     * @MaxDepth(1)
     */
    private $course;

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

    public function getAssesses(): ?string
    {
        return $this->assesses;
    }

    public function setAssesses(?string $assesses): self
    {
        $this->assesses = $assesses;

        return $this;
    }

    public function getEducationalLevel(): ?string
    {
        return $this->educationalLevel;
    }

    public function setEducationalLevel(?string $educationalLevel): self
    {
        $this->educationalLevel = $educationalLevel;

        return $this;
    }

    public function getTeaches(): ?string
    {
        return $this->teaches;
    }

    public function setTeaches(?string $teaches): self
    {
        $this->teaches = $teaches;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }
}
