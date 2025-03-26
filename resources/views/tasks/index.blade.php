@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Список задач</h1>

    <div class="mb-3">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Добавить задачу</a>
    </div>

    <div class="mb-3">
        <form method="GET" class="form-inline">
            <select name="status" class="form-control mr-2">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Все статусы</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Не выполнено</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>В процессе
                </option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Выполнено</option>
            </select>
            <select name="sort" class="form-control mr-2">
                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Сортировать по дате
                    создания</option>
                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Сортировать по алфавиту</option>
            </select>
            <button type="submit" class="btn btn-outline-secondary">Фильтровать</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Срок выполнения</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>
                            @if ($task->due_date instanceof \Carbon\Carbon)
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
                            @endif
                        </td>
                        <td>
  @if($task->status == 'pending')
       Не выполнено
   @elseif($task->status == 'in_progress')
        В процессе
    @elseif($task->status == 'completed')
        Выполнено
   @endif
</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Вы уверены, что хотите удалить эту задачу?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
            @empty
                <tr>
                    <td colspan="5">Нет задач</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection