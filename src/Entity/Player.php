<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="player_name_idx", fields={"name", "game"})})
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $name;

    /**
     * @var Game $game
     * @ORM\ManyToOne(targetEntity="App\Entity\Game")
     */
    private $game;

    /**
     * @param string $name
     * @param Game   $game
     */
    public function __construct(string $name, Game $game)
    {
        $this->id = Uuid::v4();
        $this->name = $name;
        $this->game = $game;
    }

    public function getId(): string
    {
        return $this->id->toBase58();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}