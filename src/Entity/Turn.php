<?php

namespace App\Entity;

use App\Repository\TurnRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TurnRepository::class)
 */
class Turn
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="turns")
     */
    private $player;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="turns")
     */
    private $game;

    /**
     * @ORM\Column(type="integer")
     */
    private $columnPosition;

    /**
     * @ORM\Column(type="integer")
     */
    private $rowPosition;
    
    public function __construct()
    {
        $this->datetime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getColumnPosition(): ?int
    {
        return $this->columnPosition;
    }

    public function setColumnPosition(int $columnPosition): self
    {
        $this->columnPosition = $columnPosition;

        return $this;
    }

    public function getRowPosition(): ?int
    {
        return $this->rowPosition;
    }

    public function setRowPosition(int $rowPosition): self
    {
        $this->rowPosition = $rowPosition;

        return $this;
    }

}
