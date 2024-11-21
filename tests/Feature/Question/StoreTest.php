<?php

it('should be able to store a new question')->todo();

test('with the creation of the question, we need to make sure that it creates with status _draft_')->todo();

describe('validation rules', function () {
    test('question::required')->todo();
    test('question::eding with question mark')->todo();
    test('question::min character should be 10')->todo();
    test('question::should be unique')->todo();
});

test('after creating we should return a status 201 with the created question')->todo();
