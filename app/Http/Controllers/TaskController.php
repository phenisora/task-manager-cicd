<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TodoService $service
    ) {
    }

    public function index(Request $request)
    {
        $tasks = $this->service->listTasks($request->status);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high',
        ]);

        $this->service->createTask($data);

        return redirect()->route('tasks.index');
    }
    public function edit(int $id)
    {
        // On passe par le service pour récupérer la tâche
        $task = $this->service->getTaskById($id);

        return view('tasks.edit', compact('task'));
    }


    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $this->service->updateTask($id, $data);

        return redirect()->route('tasks.index')
            ->with('success', 'La tâche a été mise à jour avec succès.');
    }


    public function destroy(int $id)
    {
        $this->service->deleteTask($id);

        return redirect()->route('tasks.index')
            ->with('success', 'La tâche a été supprimée.');
    }

}
