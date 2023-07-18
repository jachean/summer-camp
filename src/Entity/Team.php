<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $Nickname = null;

    #[ORM\Column(nullable: true)]
    private ?int $trophies = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Player::class, orphanRemoval: true)]
    private Collection $players;

    #[ORM\OneToMany(mappedBy: 'team1', targetEntity: Matches::class, orphanRemoval: true)]
    private Collection $matches1;

    #[ORM\OneToMany(mappedBy: 'team2', targetEntity: Matches::class, orphanRemoval: true)]
    private Collection $matches2;

    #[ORM\ManyToMany(targetEntity: Sponsor::class, mappedBy: 'team')]
    private Collection $sponsors;

    #[ORM\Column]

    private ?int $Points = 0;

    #[ORM\Column]
    private ?int $goalsscored = 0;

    #[ORM\Column]
    private ?int $goalstaken = 0;

    #[ORM\ManyToOne(inversedBy: 'standings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Standings $standings = null;

    #[ORM\Column]
    private ?int $wins = 0;

    #[ORM\Column]
    private ?int $loses = 0;

    #[ORM\Column]
    private ?int $ties = 0;




    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->matches1 = new ArrayCollection();
        $this->matches2 = new ArrayCollection();
        $this->sponsors = new ArrayCollection();
    }

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(\DateTimeInterface $CreationDate): static
    {
        $this->CreationDate = $CreationDate;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->Nickname;
    }

    public function setNickname(string $Nickname): static
    {
        $this->Nickname = $Nickname;

        return $this;
    }

    public function getTrophies(): ?int
    {
        return $this->trophies;
    }

    public function setTrophies(?int $trophies): static
    {
        $this->trophies = $trophies;

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getMatches1(): Collection
    {
        return $this->matches1;
    }

    public function addMatches1(Matches $matches1): static
    {
        if (!$this->matches1->contains($matches1)) {
            $this->matches1->add($matches1);
            $matches1->setTeam1($this);
        }

        return $this;
    }

    public function removeMatches1(Matches $matches1): static
    {
        if ($this->matches1->removeElement($matches1)) {
            // set the owning side to null (unless already changed)
            if ($matches1->getTeam1() === $this) {
                $matches1->setTeam1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getMatches2(): Collection
    {
        return $this->matches2;
    }

    public function addMatches2(Matches $matches2): static
    {
        if (!$this->matches2->contains($matches2)) {
            $this->matches2->add($matches2);
            $matches2->setTeam2($this);
        }

        return $this;
    }

    public function removeMatches2(Matches $matches2): static
    {
        if ($this->matches2->removeElement($matches2)) {
            // set the owning side to null (unless already changed)
            if ($matches2->getTeam2() === $this) {
                $matches2->setTeam2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sponsor>
     */
    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function addSponsor(Sponsor $sponsor): static
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors->add($sponsor);
            $sponsor->addTeam($this);
        }

        return $this;
    }

    public function removeSponsor(Sponsor $sponsor): static
    {
        if ($this->sponsors->removeElement($sponsor)) {
            $sponsor->removeTeam($this);
        }

        return $this;
    }
    public function __toString(){
        return $this->getName();
    }

    public function getPoints(): ?int
    {
        return $this->Points;
    }

    public function setPoints(int $Points): static
    {
        $this->Points = $Points;

        return $this;
    }

    public function getGoalsscored(): ?int
    {
        return $this->goalsscored;
    }

    public function setGoalsscored(int $goalsscored): static
    {
        $this->goalsscored = $goalsscored;

        return $this;
    }

    public function getGoalstaken(): ?int
    {
        return $this->goalstaken;
    }

    public function setGoalstaken(int $goalstaken): static
    {
        $this->goalstaken = $goalstaken;

        return $this;
    }

    /**
     * @return Standings|null
     */
    public function getStandings(): ?Standings
    {
        return $this->standings;
    }

    /**
     * @param Standings|null $standings
     */
    public function setStandings(?Standings $standings): void
    {
        $this->standings = $standings;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(int $wins): static
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLoses(): ?int
    {
        return $this->loses;
    }

    public function setLoses(int $loses): static
    {
        $this->loses = $loses;

        return $this;
    }

    public function getTies(): ?int
    {
        return $this->ties;
    }

    public function setTies(int $ties): static
    {
        $this->ties = $ties;

        return $this;
    }
    public function GenerateMatches(EntityManager $entityManager){
        $teams = $entityManager->getRepository(Team::class)->findAll();

        $teamsnumb=count($teams);

        for($i=0;$i<$teamsnumb-1;$i++){
            for($j=0;$j<$teamsnumb-1;$j++){
                $ok=0;
                foreach($this->matches1 as $hatz){
                    if($hatz->getTeam1()==$teams[$i]&&$hatz->getTeam2()==$teams[$j]){
                        $ok=1;

                    }
                }
                if($ok=0){
                    $match = new Matches();
                    //$match->setStandings($this);
                    $match->setTeam1($teams[$i]);
                    $match->setTeam2($teams[$j]);
                    $match->setScore1(rand(0,5));
                    $match->setScore2(rand(0,5));
                    $this->matches1->add($match);
                    dd($this->matches1);

                }

            }

        }

    }
}
