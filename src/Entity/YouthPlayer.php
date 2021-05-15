<?php

namespace App\Entity;

use App\Repository\YouthPlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=YouthPlayerRepository::class)
 */
class YouthPlayer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     */
    private $years;

    /**
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @ORM\Column(type="integer")
     */
    private $speciality;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $matchId;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=YouthTeam::class, inversedBy="youthPlayers", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getYears(): ?int
    {
        return $this->years;
    }

    /**
     * @param int $years
     * @return $this
     */
    public function setYears(int $years): self
    {
        $this->years = $years;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDays(): ?int
    {
        return $this->days;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSpeciality(): ?int
    {
        return $this->speciality;
    }

    /**
     * @param int $speciality
     * @return $this
     */
    public function setSpeciality(int $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMatchId(): ?int
    {
        return $this->matchId;
    }

    /**
     * @param int $matchId
     * @return $this
     */
    public function setMatchId(int $matchId): self
    {
        $this->matchId = $matchId;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRating(): ?float
    {
        return $this->rating;
    }

    /**
     * @param float|null $rating
     * @return $this
     */
    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     * @return $this
     */
    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
     * @param YouthTeam|null $youthTeam
     * @return $this
     */
    public function setYouthTeam(?YouthTeam $youthTeam): self
    {
        $this->youthTeam = $youthTeam;

        return $this;
    }

    /**
     * TODO: [improvement] extract this to a configuration file
     * @return bool
     */
    public function isEligible(): bool
    {
        if ($this->position >= 100 && $this->position <= 110) {
            // we have anything different than a forward player
            if ($this->years == 15 && $this->rating >= 5) {
                return true;
            } elseif ($this->years == 16 && $this->rating >= 6.5) {
                return true;
            } elseif ($this->years == 17 && $this->days <= 30 && $this->rating >= 6.5) {
                return true;
            }
        } elseif ($this->position >= 111 && $this->position <= 113) {
            // we have a forward player
            if ($this->years == 15 && $this->rating >= 5.5) {
                return true;
            } elseif ($this->years == 16 && $this->rating >= 7) {
                return true;
            } elseif ($this->years == 17 && $this->days <= 30 && $this->rating >= 7) {
                return true;
            }
        }

        return false;
    }
}
