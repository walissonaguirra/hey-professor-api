<?php

use App\Exports\QuestionExport;
use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;
use Maatwebsite\Excel\Facades\Excel;

use function Pest\Laravel\getJson;

it('should be able to exports questions', function () {

    Sanctum::actingAs(User::factory()->create());

    Question::factory()->create(['question' => 'Question exported in excel!']);

    Excel::fake();

    getJson(route('question.export'));

    Excel::assertDownloaded('questions.xlsx', function (QuestionExport $questionExport) {
        return $questionExport->collection()->contains('question', '=', 'Question exported in excel!');
    });

});
