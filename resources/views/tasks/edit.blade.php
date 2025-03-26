@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Редактировать задачу</h1>
    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Название</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description">{{ $task->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Срок выполнения</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value=" @if ($task->due_date instanceof \Carbon\Carbon)
                {{ $task->due_date->format('Y-m-d') }}
            @elseif ($task->due_date instanceof \DateTime)
                {{ $task->due_date->format('Y-m-d') }}
            @elseif ($task->due_date)
            @php
                try {
                    $dueDate = \Carbon\Carbon::parse($task->due_date);
                    echo $dueDate->format('Y-m-d');
                } catch (\Exception $e) {
                    echo 'Не указан';
                }
            @endphp
            @else
                Не указан
            @endif">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Не выполнено</option>
                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>В процессе</option>
                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Выполнено</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Обновить задачу</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection