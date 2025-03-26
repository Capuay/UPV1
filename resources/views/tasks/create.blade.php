@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создать задачу</h1>
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Срок выполнения</label>
                <input type="date" class="form-control" id="due_date" name="due_date">
            </div>
            <button type="submit" class="btn btn-primary">Добавить задачу</button>
             <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection