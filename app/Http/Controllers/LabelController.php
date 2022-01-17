<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{

    public function index()
    {
        $labels = auth()->user()->labels;

        return response($labels, Response::HTTP_OK);
    }


    public function store(LabelRequest $request)
    {
       $label = auth()->user()->labels()->create($request->validated());

        return response($label, Response::HTTP_CREATED);
    }


    public function destroy(Label $label)
    {
        $label->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function update(LabelRequest $request, Label $label)
    {
        $label->update($request->validated());

        return response($label->fresh(), Response::HTTP_OK);
    }
}
