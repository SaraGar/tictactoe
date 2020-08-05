<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Turn::class, mappedBy="player")
     */
    private $turns;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="winner")
     */
    private $gamesWon;

    public function __construct()
    {
        $this->turns = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->gamesWon = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Turn[]
     */
    public function getTurns(): Collection
    {
        return $this->turns;
    }

    public function addTurn(Turn $turn): self
    {
        if (!$this->turns->contains($turn)) {
            $this->turns[] = $turn;
            $turn->setPlayer($this);
        }

        return $this;
    }

    public function removeTurn(Turn $turn): self
    {
        if ($this->turns->contains($turn)) {
            $this->turns->removeElement($turn);
            // set the owning side to null (unless already changed)
            if ($turn->getPlayer() === $this) {
                $turn->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setWinner($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getWinner() === $this) {
                $game->setWinner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesWon(): Collection
    {
        return $this->gamesWon;
    }

    public function addGamesWon(Game $gamesWon): self
    {
        if (!$this->gamesWon->contains($gamesWon)) {
            $this->gamesWon[] = $gamesWon;
            $gamesWon->setWinner($this);
        }

        return $this;
    }

    public function removeGamesWon(Game $gamesWon): self
    {
        if ($this->gamesWon->contains($gamesWon)) {
            $this->gamesWon->removeElement($gamesWon);
            // set the owning side to null (unless already changed)
            if ($gamesWon->getWinner() === $this) {
                $gamesWon->setWinner(null);
            }
        }

        return $this;
    }

}
