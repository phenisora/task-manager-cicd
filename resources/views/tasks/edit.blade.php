@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h2 class="text-xl font-bold mb-6 text-gray-700">Modifier la tâche : {{ $task->title }}</h2>
    
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT') <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Titre *</label>
            <input type="text" name="title" value="{{ $task->title }}" required 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">{{ $task->description }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Statut</label>
                <select name="status" class="block w-full border border-gray-400 px-4 py-2 rounded shadow">
                    <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>À faire</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Terminé</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Priorité</label>
                <select name="priority" class="block w-full border border-gray-400 px-4 py-2 rounded shadow">
                    <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>Haute</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Date limite</label>
                <input type="date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="text-gray-500 hover:text-gray-700">Annuler</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:shadow-outline">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection