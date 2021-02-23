<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @var UuidInterface The UUID identifier of this Question.
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
     * @var string The name of this Question.
     *
     * @example Question1
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
     * @var string The Question.
     *
     * @example Is dit een vraag?
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var Datetime The moment this Question was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this Question was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @var bool Denotes if this question is the first question of the stage
     *
     * @example true
     *
     * @Groups({"read"})
     */
    private $start = false;

    /**
     * @var bool Denotes if this question is the last question of the stage
     *
     * @example true
     *
     * @Groups({"read"})
     */
    private $end = false;

    /**
     * @param Question $next The next question from this one
     *
     * @MaxDepth(1)
     * @Groups({"read"})
     */
    private $next;

    /**
     * @param Question $previous The previues question from this one
     *
     * @MaxDepth(1)
     * @Groups({"read"})
     */
    private $previous;

    /**
     * @var string The Answer to this Question.
     *
     * @example Dit is het antwoord.
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $answer;

    /**
     * @var array The answerOptions of this Question.
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $answerOptions = [];

    /**
     * @var Stage the stage this stage belongs to
     *
     * @MaxDepth(1)
     * @Groups({"read","write"}))
     * @ORM\ManyToOne(targetEntity=Stage::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Stage $stage;

    /**
     * @var int The place in the order where the question should be rendered
     *
     * @Assert\NotNull
     * @Groups({"read","write"})
     * @ORM\Column(type="integer")
     */
    private $orderNumber = 0;

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
        if ($this->getStage()->getFirstQuestion() == $this) {
            return true;
        }

        return false;
    }

    public function getEnd(): ?bool
    {
        if ($this->getStage()->getLastQuestion() == $this) {
            return true;
        }

        return false;
    }

    public function getPrevious()
    {
        return $this->getStage()->getPreviousQuestion($this);
    }

    public function getNext()
    {
        return $this->getStage()->getNextQuestion($this);
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getAnswerOptions(): ?array
    {
        return $this->answerOptions;
    }

    public function setAnswerOptions(?array $answerOptions): self
    {
        $this->answerOptions = $answerOptions;

        return $this;
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    public function setStage(?Stage $stage): self
    {
        $this->stage = $stage;

        return $this;
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
            $this->orderNumber = $this->getStage()->getQuestions()->indexOf($this) + 1;
        }
    }
}
