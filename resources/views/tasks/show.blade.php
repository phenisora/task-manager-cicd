@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded px-8 py-6">
    <div class="flex justify-between items-start mb-4">
        <h2 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h2>
        <span class="px-3 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700 uppercase">
            {{ $task->priority }}
        </span>
    </div>

    <p class="text-gray-600 mb-6 italic">
        Statut actuel : <span class="font-semibold">{{ $task->status }}</span>
    </p>

    <div class="border-t border-gray-100 pt-4 mb-6">
        <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Description</h3>
        <p class="text-gray-700 leading-relaxed">
            {{ $task->description ?? 'Aucune description fournie.' }}
        </p>
    </div>

    <div class="flex justify-between items-center text-sm text-gray-400">
        <p>Créée le : {{ $task->created_at->format('d/m/Y') }}</p>
        <a href="{{ route('tasks.index') }}" class="text-indigo-600 font-bold hover:underline">← Retour à la liste</a>
    </div>
</div>
@endsection