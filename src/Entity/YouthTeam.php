<?php

namespace App\Entity;

use App\Repository\YouthTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=YouthTeamRepository::class)
 */
class YouthTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Team::class, inversedBy="youthTeam", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity=YouthPlayer::class, mappedBy="youthTeam", orphanRemoval=true, cascade={"persist"}, indexBy="id")
     */
    private $youthPlayers;

    public function __construct()
    {
        $this->youthPlayers = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team $team
     * @return $this
     */
    public function setTeam(Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @param $id
     * @return YouthPlayer|null
     */
    public function getYouthPlayer($id): ?YouthPlayer
    {
        if (isset($this->youthPlayers[$id])) {
            return $this->youthPlayers[$id];
        }

        return null;
    }

    /**
     * @return Collection|YouthPlayer[]
     */
    public function getYouthPlayers(): Collection
    {
        return $this->youthPlayers;
    }

    /**
     * @param YouthPlayer $youthPlayer
     * @return $this
     */
    public function addYouthPlayer(YouthPlayer $youthPlayer): self
    {
        $this->youthPlayers[$youthPlayer->getId()] = $youthPlayer;
        $youthPlayer->setYouthTeam($this);

        return $this;
    }

    /**
     * @param YouthPlayer $youthPlayer
     * @return $this
     */
    public function removeYouthPlayer(YouthPlayer $youthPlayer): self
    {
        if ($this->youthPlayers->removeElement($youthPlayer)) {
            // set the owning side to null (unless already changed)
            if ($youthPlayer->getYouthTeam() === $this) {
                $youthPlayer->setYouthTeam(null);
            }
        }

        return $this;
    }
}
