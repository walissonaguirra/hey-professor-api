<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreQuestionRequest, UpdateQuestionRequest};
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Support\Facades\{Auth, Gate};
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    public function index()
    {
        //
    }

    public function store(StoreQuestionRequest $request)
    {
        $question = Auth::user()->questions()->create([
            'question' => $request->question,
            'draft'    => true,
        ]);

        return QuestionResource::make($question);
    }

    public function show(Question $question)
    {
        //
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->question = $request->question;
        $question->save();

        return QuestionResource::make($question);
    }

    public function destroy(Question $question)
    {
        Gate::authorize('forceDelete', $question);

        $question->forceDelete();

        return response()->noContent();
    }

    public function archive(Question $question)
    {
        Gate::authorize('archive', $question);

        $question->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $question = Question::onlyTrashed()->findOrFail($id);

        Gate::authorize('restore', $question);

        $question->restore();

        return response()->noContent();
    }

    public function publish(Question $question)
    {
        abort_if($question->draft == false, Response::HTTP_NOT_FOUND);

        Gate::authorize('publish', $question);

        $question->update(['draft' => false]);

        return response()->noContent();
    }
}
