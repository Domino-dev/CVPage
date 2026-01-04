<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name:'projects')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(name:'short_description',length: 255)]
    private ?string $shortDescription = null;

    #[ORM\Column(name:'long_description',type: Types::TEXT)]
    private ?string $longDescription = null;

    #[ORM\Column(name:'s_image',length: 255)]
    private ?string $sImage = null;

    #[ORM\Column(name:'m_image',length: 255)]
    private ?string $mImage = null;

    #[ORM\Column(name:'is_school_project', options:["default" => false])]
    private ?bool $isSchoolProject = null;

    #[ORM\Column(name:'date_from',type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateFrom = null;

    #[ORM\Column(name:'date_to',type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateTo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getSImage(): ?string
    {
        return $this->sImage;
    }

    public function setSImage(string $sImage): static
    {
        $this->sImage = $sImage;

        return $this;
    }

    public function getMImage(): ?string
    {
        return $this->mImage;
    }

    public function setMImage(string $mImage): static
    {
        $this->mImage = $mImage;

        return $this;
    }

    public function getIsSchoolProject(): bool
    {
        return $this->isSchoolProject;
    }

    public function setIsSchoolProject(bool $isSchoolProject): static
    {
        $this->isSchoolProject = $isSchoolProject;

        return $this;
    }

    public function getDateFrom(): ?DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(DateTime $dateFrom): static
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    public function getDateTo(): ?DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(DateTime $dateTo): static
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): static
    {
        $this->created = $created;

        return $this;
    }
}
