<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=YouthTeam::class, mappedBy="team", cascade={"persist", "remove"})
     */
    private $youthTeam;

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
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return YouthTeam|null
     */
    public function getYouthTeam(): ?YouthTeam
    {
        return $this->youthTeam;
    }

    /**
     * @param YouthTeam $youthTeam
     * @return $this
     */
    public function setYouthTeam(YouthTeam $youthTeam): self
    {
        // set the owning side of the relation if necessary
        if ($youthTeam->getTeam() !== $this) {
            $youthTeam->setTeam($this);
        }

        $this->youthTeam = $youthTeam;

        return $this;
    }
}
