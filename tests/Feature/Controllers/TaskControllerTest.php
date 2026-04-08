<?php

namespace Tests\Feature\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    // ── index ──────────────────────────────────────────────────────────────

    public function test_index_displays_tasks(): void
    {
        Task::create($this->taskData(['title' => 'Tâche 1']));
        Task::create($this->taskData(['title' => 'Tâche 2']));

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks');
    }

    public function test_index_filters_tasks_by_status(): void
    {
        Task::create($this->taskData(['status' => 'todo']));
        Task::create($this->taskData(['status' => 'done']));

        $response = $this->get(route('tasks.index', ['status' => 'todo']));

        $response->assertStatus(200);
    }

    // ── create ─────────────────────────────────────────────────────────────

    public function test_create_displays_form(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.create');
    }

    // ── store ──────────────────────────────────────────────────────────────

    public function test_store_creates_task_and_redirects(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => 'Nouvelle tâche',
            'description' => 'Description',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => null,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Nouvelle tâche']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->post(route('tasks.store'), []);

        $response->assertSessionHasErrors(['title', 'status', 'priority']);
    }

    // ── edit ───────────────────────────────────────────────────────────────

    public function test_edit_displays_form(): void
    {
        $task = Task::create($this->taskData());

        $response = $this->get(route('tasks.edit', $task->id));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.edit');
        $response->assertViewHas('task');
    }

    // ── update ─────────────────────────────────────────────────────────────

    public function test_update_modifies_task_and_redirects(): void
    {
        $task = Task::create($this->taskData(['title' => 'Ancien titre']));

        $response = $this->put(route('tasks.update', $task->id), [
            'title' => 'Nouveau titre',
            'description' => 'Nouvelle description',
            'status' => 'done',
            'priority' => 'high',
            'due_date' => null,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Nouveau titre']);
    }

    // ── destroy ────────────────────────────────────────────────────────────

    public function test_destroy_deletes_task_and_redirects(): void
    {
        $task = Task::create($this->taskData());

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    // ── helper ─────────────────────────────────────────────────────────────

    private function taskData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Tâche test',
            'description' => 'Description test',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => null,
        ], $overrides);
    }
}
