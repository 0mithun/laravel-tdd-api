<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{


    public function index(TodoList $todo_list)
    {
        $tasks = $todo_list->tasks;

        return response($tasks , Response::HTTP_OK);
    }

    public function show(Task $task)
    {
        # code...
    }


    public function store(TodoList $todo_list, TaskRequest $request)
    {
       $task = $todo_list->tasks()->create($request->validated());

        return response($task, Response::HTTP_CREATED);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return response($task->fresh(), Response::HTTP_OK);
    }


    public function destroy(Task $task)
    {
       $task->delete();

       return response(null, Response::HTTP_NO_CONTENT);
    }
}
