<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TestRepository;
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
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    /**
     * @var UuidInterface The UUID identifier of this Test.
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
     * @var string The name of this Test.
     *
     * @example Test1
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
     * @var string The description of this Test.
     *
     * @example Beschrijving van Test1.
     *
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Datetime The moment this Test was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var Datetime The moment this Test was last Modified
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @MaxDepth(1)
     * @Groups({"read", "write"})
     * @ORM\OneToMany(targetEntity=TestResult::class, mappedBy="test")
     */
    private $testResults;

    /**
     * @var Activity The activity that this test belongs to
     *
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=Activity::class, inversedBy="tests")
     */
    private $activity;

    /**
     * @var array The stages of this test
     *
     * @MaxDepth(1)
     * @Groups({"read", "write"})
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="test", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     * @ORM\OrderBy({"orderNumber" = "ASC"})
     */
    private $stages;

    public function __construct()
    {
        $this->testResults = new ArrayCollection();
        $this->stages = new ArrayCollection();
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

    /**
     * @return Collection|TestResult[]
     */
    public function getTestResults(): Collection
    {
        return $this->testResults;
    }

    public function addTestResult(TestResult $testResult): self
    {
        if (!$this->testResults->contains($testResult)) {
            $this->testResults[] = $testResult;
            $testResult->setTest($this);
        }

        return $this;
    }

    public function removeTestResult(TestResult $testResult): self
    {
        if ($this->testResults->contains($testResult)) {
            $this->testResults->removeElement($testResult);
            // set the owning side to null (unless already changed)
            if ($testResult->getTest() === $this) {
                $testResult->setTest(null);
            }
        }

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection|Stage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->setTest($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->contains($stage)) {
            $this->stages->removeElement($stage);
            // set the owning side to null (unless already changed)
            if ($stage->getTest() === $this) {
                $stage->setTest(null);
            }
        }

        return $this;
    }

    // Stages logic

    public function getFirstStage()
    {
        return $this->getStages()->first();
    }

    public function getLastStage()
    {
        return $this->getStages()->last();
    }

    public function getPreviousStage($stage)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->lt('orderNumber', $stage->getOrderNumber()));

        return $this->getStages()->matching($criteria)->last();
    }

    public function getNextStage($stage)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gt('orderNumber', $stage->getOrderNumber()));

        return $this->getStages()->matching($criteria)->first();
    }

    public function getMaxStage()
    {
        if ($this->getLastStage() && $this->getLastStage()->getOrderNumber()) {
            return $this->getLastStage()->getOrderNumber();
        }

        return 0;
    }
}
