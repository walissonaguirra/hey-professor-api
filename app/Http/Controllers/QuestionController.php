<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreQuestionRequest, UpdateQuestionRequest};
use App\Models\Question;

class QuestionController extends Controller
{
    public function index()
    {
        //
    }

    public function store(StoreQuestionRequest $request)
    {
        return Question::create([
            'user_id'  => $request->user()->id,
            'question' => $request->question,
        ]);
    }

    public function show(Question $question)
    {
        //
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        //
    }

    public function destroy(Question $question)
    {
        //
    }
}
