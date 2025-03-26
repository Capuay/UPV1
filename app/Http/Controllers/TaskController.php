<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Запрос на отображение списка задач.');

        $query = Task::query();

        // Фильтрация по статусу
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
            Log::debug('Применен фильтр по статусу: ' . $request->status);
        }

        // Сортировка
        if ($request->has('sort')) {
            $sortField = $request->sort === 'title' ? 'title' : 'created_at';
            $sortDirection = $request->sort === 'title' ? 'asc' : 'desc';
            $query->orderBy($sortField, $sortDirection);
            Log::debug('Применена сортировка по ' . $sortField . ' ' . $sortDirection);
        }

        $tasks = $query->get();

        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        try {
            Task::create($request->all());
            Log::info('Задача успешно создана: ' . $request->title);

            return redirect()->route('tasks.index')->with('success', 'Задача успешно создана.');
        } catch (\Exception $e) {
            Log::error('Ошибка создания задачи: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка при создании задачи.');
        }
    }

    public function edit(Task $task)
    {
        Log::info('Отображение формы редактирования задачи: ' . $task->id);
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        try {
            $task->update($request->all());
            Log::info('Задача успешно обновлена: ' . $task->id);
            return redirect()->route('tasks.index')->with('success', 'Задача успешно обновлена.');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления задачи: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка при обновлении задачи.');
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
            Log::info('Задача успешно удалена: ' . $task->id);
            return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена.');
        } catch (\Exception $e) {
            Log::error('Ошибка удаления задачи: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка при удалении задачи.');
        }
    }
}