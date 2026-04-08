<?php

namespace Tests\Feature\Repositories;

use App\Models\Task;
use App\Repositories\TodoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TodoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TodoRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TodoRepository();
    }

    public function test_get_all_returns_tasks_sorted_by_latest_first(): void
    {
        $olderTask = Task::query()->create($this->taskData([
            'title' => 'Ancienne tâche',
            'status' => 'todo',
        ]));

        $newerTask = Task::query()->create($this->taskData([
            'title' => 'Nouvelle tâche',
            'status' => 'done',
        ]));

        DB::table('tasks')->where('id', $olderTask->id)->update([
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        DB::table('tasks')->where('id', $newerTask->id)->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = $this->repository->getAll();

        $this->assertCount(2, $result);
        $this->assertSame($newerTask->id, $result->first()->id);
        $this->assertSame($olderTask->id, $result->last()->id);
    }

    public function test_get_all_can_filter_tasks_by_status(): void
    {
        Task::query()->create($this->taskData([
            'title' => 'À faire',
            'status' => 'todo',
        ]));

        $doneTask = Task::query()->create($this->taskData([
            'title' => 'Terminée',
            'status' => 'done',
        ]));

        $result = $this->repository->getAll('done');

        $this->assertCount(1, $result);
        $this->assertSame($doneTask->id, $result->first()->id);
        $this->assertSame('done', $result->first()->status);
    }

    public function test_create_task(): void
    {
        $data = $this->taskData([
            'title' => 'Créer une tâche',
            'status' => 'in_progress',
        ]);

        $result = $this->repository->create($data);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertSame('Créer une tâche', $result->title);
        $this->assertSame('in_progress', $result->status);
        $this->assertDatabaseHas('tasks', [
            'id' => $result->id,
            'title' => 'Créer une tâche',
            'status' => 'in_progress',
        ]);
    }

    public function test_find_task_by_id(): void
    {
        $task = Task::query()->create($this->taskData([
            'title' => 'Tâche recherchée',
        ]));

        $result = $this->repository->findById($task->id);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertSame($task->id, $result->id);
        $this->assertSame('Tâche recherchée', $result->title);
    }

    public function test_find_task_by_id_throws_exception_when_task_does_not_exist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->findById(99999);
    }

    public function test_update_task(): void
    {
        $task = Task::query()->create($this->taskData([
            'title' => 'Titre initial',
            'status' => 'todo',
            'priority' => 'low',
        ]));

        $result = $this->repository->update($task, [
            'title' => 'Titre mis à jour',
            'status' => 'done',
            'priority' => 'high',
        ]);

        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Titre mis à jour',
            'status' => 'done',
            'priority' => 'high',
        ]);
    }

    public function test_delete_task(): void
    {
        $task = Task::query()->create($this->taskData([
            'title' => 'Tâche à supprimer',
        ]));

        $result = $this->repository->delete($task);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    private function taskData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Tâche de test',
            'description' => 'Description de test',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => '2026-05-01',
        ], $overrides);
    }
}

