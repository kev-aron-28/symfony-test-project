<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable:true)]
    private ?bool $attendance = null;

    #[ORM\Column(nullable:true)]
    private ?bool $companion = null;

    #[ORM\Column(nullable:true)]
    private ?string $disability = null;

    #[ORM\ManyToOne(inversedBy: 'yes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Award $award = null;

    #[ORM\Column(length: 255)]
    private ?string $curp = null;

    #[ORM\ManyToOne(inversedBy: 'yes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $school = null;

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

    public function isAttendance(): ?bool
    {
        return $this->attendance;
    }

    public function setAttendance(bool $attendance): static
    {
        $this->attendance = $attendance;

        return $this;
    }

    public function isCompanion(): ?bool
    {
        return $this->companion;
    }

    public function setCompanion(bool $companion): static
    {
        $this->companion = $companion;

        return $this;
    }

    public function isDisability(): ?string
    {
        return $this->disability;
    }

    public function setDisability(string $disability): static
    {
        $this->disability = $disability;

        return $this;
    }

    public function getAward(): ?Award
    {
        return $this->award;
    }

    public function setAward(?Award $award): static
    {
        $this->award = $award;

        return $this;
    }

    public function getCurp(): ?string
    {
        return $this->curp;
    }

    public function setCurp(string $curp): static
    {
        $this->curp = $curp;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }
}
