<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchesRepository::class)]
class Matches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'matches1')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team1 = null;

    #[ORM\ManyToOne(inversedBy: 'matches2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team2 = null;

    #[ORM\Column]
    private ?int $score1 = null;

    #[ORM\Column]
    private ?int $score2 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,nullable: true)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $referee = null;

    #[ORM\ManyToOne(inversedBy: 'matches')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Standings $standings = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam1(): ?Team
    {
        return $this->team1;
    }

    public function setTeam1(?Team $team1): static
    {
        $this->team1 = $team1;

        return $this;
    }

    public function getTeam2(): ?Team
    {
        return $this->team2;
    }

    public function setTeam2(?Team $team2): static
    {
        $this->team2 = $team2;

        return $this;
    }

    public function getScore1(): ?int
    {
        return $this->score1;
    }

    public function setScore1(int $score1): static
    {
        $this->score1 = $score1;

        return $this;
    }

    public function getScore2(): ?int
    {
        return $this->score2;
    }

    public function setScore2(int $score2): static
    {
        $this->score2 = $score2;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getReferee(): ?string
    {
        return $this->referee;
    }

    public function setReferee(string $referee): static
    {
        $this->referee = $referee;

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }

    public function getStandings(): ?Standings
    {
        return $this->standings;
    }

    public function setStandings(?Standings $standings): static
    {
        $this->standings = $standings;

        return $this;
    }

}
