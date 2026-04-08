<?php

namespace Tests\Unit\Services;

use App\Models\Task;
use App\Repositories\TodoRepository;
use App\Services\TodoService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TodoServiceTest extends TestCase
{
    private TodoService $service;

    /**
     * @var MockInterface&TodoRepository
     */
    private MockInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(TodoRepository::class);
        $this->service = new TodoService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_list_tasks_returns_repository_result(): void
    {
        $expectedTasks = new Collection([
            $this->makeTask(1, [
                'title' => 'Tâche 1',
                'status' => 'done',
            ]),
            $this->makeTask(2, [
                'title' => 'Tâche 2',
                'status' => 'done',
            ]),
        ]);

        $this->repository
            ->shouldReceive('getAll')
            ->once()
            ->with('done')
            ->andReturn($expectedTasks);

        $result = $this->service->listTasks('done');

        $this->assertSame($expectedTasks, $result);
        $this->assertCount(2, $result);
    }

    public function test_create_task_delegates_creation_to_repository(): void
    {
        $data = [
            'title' => 'Nouvelle tâche',
            'description' => 'Description',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => '2026-05-01',
        ];

        $createdTask = $this->makeTask(10, $data);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($createdTask);

        $result = $this->service->createTask($data);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertSame(10, $result->id);
        $this->assertSame('Nouvelle tâche', $result->title);
    }

    public function test_update_task_finds_task_then_updates_it(): void
    {
        $task = $this->makeTask(5, [
            'title' => 'Avant mise à jour',
            'status' => 'todo',
        ]);

        $data = [
            'title' => 'Après mise à jour',
            'status' => 'in_progress',
            'priority' => 'high',
        ];

        $this->repository
            ->shouldReceive('findById')
            ->once()
            ->with(5)
            ->ordered()
            ->andReturn($task);

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($task, $data)
            ->ordered()
            ->andReturn(true);

        $result = $this->service->updateTask(5, $data);

        $this->assertTrue($result);
    }

    public function test_delete_task_finds_task_then_deletes_it(): void
    {
        $task = $this->makeTask(8, [
            'title' => 'Tâche à supprimer',
        ]);

        $this->repository
            ->shouldReceive('findById')
            ->once()
            ->with(8)
            ->ordered()
            ->andReturn($task);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($task)
            ->ordered()
            ->andReturn(true);

        $result = $this->service->deleteTask(8);

        $this->assertTrue($result);
    }

    public function test_get_task_by_id_returns_repository_task(): void
    {
        $task = $this->makeTask(12, [
            'title' => 'Tâche détaillée',
            'status' => 'todo',
        ]);

        $this->repository
            ->shouldReceive('findById')
            ->once()
            ->with(12)
            ->andReturn($task);

        $result = $this->service->getTaskById(12);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertSame(12, $result->id);
        $this->assertSame('Tâche détaillée', $result->title);
    }

    private function makeTask(int $id, array $attributes = []): Task
    {
        $task = new Task();
        $task->id = $id;
        $task->fill(array_merge([
            'title' => 'Tâche de test',
            'description' => 'Description de test',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => '2026-05-01',
        ], $attributes));

        return $task;
    }
}
