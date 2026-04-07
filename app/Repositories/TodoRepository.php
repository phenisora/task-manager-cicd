<?php
namespace App\Repositories;

use App\Models\Task;

class TodoRepository
{
    public function getAll(string $status = null)
    {
        return Task::when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function findById(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}