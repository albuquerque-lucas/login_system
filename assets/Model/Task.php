<?php

namespace LucasAlbuquerque\LoginSystem\Model;

class Task
{
    private ?int $id;
    private string $name;
    private string $description;
    private int $task_status;
    private string $task_staus_name;
    private string $task_creation_date;
    private string $task_conclusion_date;

    public function __construct(
    ?int $id,
    string $name,
    string $description,
    int $task_status,
    string $task_status_name,
    string $task_creation_date
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->task_status = $task_status;
        $this->task_staus_name = $task_status_name;
        $this->task_creation_date = $task_creation_date;
        $this->task_conclusion_date = '---';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): int
    {
        return $this->task_status;
    }

    public function getStatusName(): string
    {
        return $this->task_staus_name;
    }

    public function getCreationDate(): string
    {
        return $this->task_creation_date;
    }

    public function getConclusionDate(): string
    {
        return $this->task_conclusion_date;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(int $status): void
    {
        $this->task_status = $status;
    }

    public function setStatusName(string $statusName): void
    {
        $this->task_staus_name = $statusName;
    }

    public function setCreationDate(string $creationDate): void
    {
        $this->task_creation_date = $creationDate;
    }

    public function setConclusionDate(string $conclusionDate): void
    {
        $this->task_conclusion_date = $conclusionDate;
    }


}