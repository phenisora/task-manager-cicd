@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-700">Liste des tâches</h2>
    <a href="{{ route('tasks.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
        + Créer une tâche
    </a>
</div>

<div class="mb-6 flex space-x-2">
    <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-white rounded-md text-sm border hover:bg-gray-50">Toutes</a>
    <a href="{{ route('tasks.index', ['status' => 'todo']) }}" class="px-4 py-2 bg-white rounded-md text-sm border hover:bg-gray-50">À faire</a>
    <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="px-4 py-2 bg-white rounded-md text-sm border hover:bg-gray-50">En cours</a>
    <a href="{{ route('tasks.index', ['status' => 'done']) }}" class="px-4 py-2 bg-white rounded-md text-sm border hover:bg-gray-50">Terminées</a>
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead>
            <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Titre</th>
                <th class="py-3 px-6 text-center">Statut</th>
                <th class="py-3 px-6 text-center">Priorité</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @forelse($tasks as $task)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left whitespace-nowrap font-medium">{{ $task->title }}</td>
                <td class="py-3 px-6 text-center">
                    <span class="py-1 px-3 rounded-full text-xs @if($task->status == 'done') bg-green-200 text-green-800 @else bg-yellow-200 text-yellow-800 @endif">
                        {{ $task->status }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    <span class="font-bold @if($task->priority == 'high') text-red-500 @elseif($task->priority == 'medium') text-orange-500 @else text-blue-500 @endif">
                        {{ strtoupper($task->priority) }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center space-x-4">
                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-6 text-center text-gray-500">Aucune tâche trouvée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection