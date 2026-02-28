<?php

namespace App\Entity;

use App\Enum\ProjectType;
use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(enumType: ProjectType::class)]
    private ProjectType $type;

    #[ORM\Column(length: 255)]
    private string $skills;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
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

    public function getType(): ProjectType{
        return $this->type;
    }

    public function setType(ProjectType $type): static{
        $this->type = $type;

        return $this;
    }

    public function getSkills(): string{
        return $this->skills;
    }

    public function setSkills(string $skills): static{
        $this->skills = $skills;

        return $this;
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

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;
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
