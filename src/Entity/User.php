<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="user", orphanRemoval=true, indexBy="id")
     */
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
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
     * @param $id
     * @return Team|null
     */
    public function getTeam($id): ?Team
    {
        if (isset($this->teams[$id])) {
            return $this->teams[$id];
        }

        return null;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @param Team $team
     * @return $this
     */
    public function addTeam(Team $team): self
    {
        $this->teams[$team->getId()] = $team;
        $team->setUser($this);

        return $this;
    }

    /**
     * @param Team $team
     * @return $this
     */
    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getUser() === $this) {
                $team->setUser(null);
            }
        }

        return $this;
    }
}
