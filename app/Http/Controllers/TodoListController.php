<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{

    public function index()
    {
        $lists = auth()->user()->lists;

        return response([$lists]);
    }

    public function show(TodoList $todo_list)
    {
       return response($todo_list);
    }

    public function store(TodoListRequest $request)
    {
        $list = auth()->user()->lists()->create($request->all());

        return response($list, Response::HTTP_CREATED);
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function update(TodoListRequest $request, TodoList $todo_list)
    {
        $todo_list->update($request->all());

        return response($todo_list->fresh(), Response::HTTP_OK);
    }
}
