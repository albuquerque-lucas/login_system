<?php

namespace LucasAlbuquerque\LoginSystem\Model;

class Task
{

    private ?int $id;
    private string $name;
    private string $description;

    public function __construct(?int $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function id(): int
    {
        return $this->id;
    }


    public function name(): string
    {
        return $this->name;
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function description(): string
    {
        return $this->description;
    }


    public function setDescription(string $description): void
    {
        $this->description = $description;
    }



}