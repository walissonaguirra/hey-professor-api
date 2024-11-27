<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyQuestionController extends Controller
{
    public function __invoke(Request $req)
    {
        Validator::validate(
            [
                'status' => $req->status,
            ],
            [
                'status' => 'required|in:published,draft,archived',
            ]
        );

        $questions = $req->user()
            ->questions()
            ->when(
                $req->status == 'archived',
                fn ($q) => $q->onlyTrashed(),
                fn ($q) => $q->where('draft', $req->status != 'published')
            )
            ->get();

        return QuestionResource::collection($questions);
    }
}
