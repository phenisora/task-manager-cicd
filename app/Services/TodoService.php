<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TodoRepository;

class TodoService
{
    public function __construct(
        protected TodoRepository $repository
    ) {
    }

    public function listTasks(?string $status)
    {
        return $this->repository->getAll($status);
    }

    public function createTask(array $data): Task
    {
        // Logique métier supplémentaire ici (ex: envoyer un mail)
        return $this->repository->create($data);
    }

    public function updateTask(int $id, array $data): bool
    {
        $task = $this->repository->findById($id);

        return $this->repository->update($task, $data);
    }

    public function deleteTask(int $id): bool
    {
        $task = $this->repository->findById($id);

        return $this->repository->delete($task);
    }

    public function getTaskById(int $id)
    {
        return $this->repository->findById($id);
    }
}
