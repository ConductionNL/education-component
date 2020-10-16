<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StageRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A stage within a test.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
{
    /**
     * @var UuidInterface The UUID identifier of this Stage.
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
     * @var string The name of this Stage.
     *
     * @example Stage1
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
     * @var string The description of this Stage.
     *
     * @example Beschrijving van Stage1.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Datetime The moment this Stage was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this Stage was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @var bool Whether or not this stage is the starting point of a test
     *
     * @example true
     *
     * @Groups({"read"})
     */
    private $start = false;

    /**
     * @var bool Whether or not this stage is the last point of a test
     *
     * @example true
     *
     * @Groups({"read"})
     */
    private $end = false;

    /**
     * @param Stage $next The next stage from this one
     *
     * @MaxDepth(1)
     * @Groups({"read"})
     */
    private $next;

    /**
     * @param Stage $previous The previous stage from this one
     *
     * @MaxDepth(1)
     * @Groups({"read"})
     */
    private $previous;

    /**
     * @var Test The test that this stage belongs to
     *
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=Test::class, inversedBy="stages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $test;

    /**
     * @var ArrayCollection the questions of this stage
     *
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="stage", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"orderNumber" = "ASC"})
     */
    private $questions;

    /**
     * @var int The place in the order where the stage should be rendered
     *
     * @Assert\NotNull
     * @Groups({"read","write"})
     * @ORM\Column(type="integer")
     */
    private $orderNumber = 0;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    public function getStart(): ?bool
    {
        if ($this->getTest()->getFirstStage() == $this) {
            return true;
        }

        return false;
    }

    public function getEnd(): ?bool
    {
        if ($this->getTest()->getLastStage() == $this) {
            return true;
        }

        return false;
    }

    public function getPrevious()
    {
        return $this->getTest()->getPreviousStage($this);
    }

    public function getNext()
    {
        return $this->getTest()->getNextStage($this);
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setStage($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getStage() === $this) {
                $question->setStage(null);
            }
        }

        return $this;
    }

    // Question logic

    public function getFirstQuestion()
    {
        return $this->getQuestions()->first();
    }

    public function getLastQuestion()
    {
        return $this->getQuestions()->last();
    }

    public function getPreviousQuestion($question)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->lt('orderNumber', $question->getOrderNumber()));

        return $this->getQuestions()->matching($criteria)->last();
    }

    public function getNextQuestion($question)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gt('orderNumber', $question->getOrderNumber()));

        return $this->getQuestions()->matching($criteria)->first();
    }

    public function getMaxQuestion()
    {
        if ($this->getLastQuestion() && $this->getLastQuestion()->getOrderNumber()) {
            return $this->getLastQuestion()->getOrderNumber();
        }

        return 0;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function preFillOrderNumber()
    {
        if (!$this->orderNumber || $this->orderNumber <= 0) {
            $this->orderNumber = $this->getTest()->getStages()->indexOf($this) + 1;
        }
    }
}
