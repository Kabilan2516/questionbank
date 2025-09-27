<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TestController;

Route::get('/', function(){ return redirect()->route('subjects.index'); });

Route::resource('subjects', SubjectController::class);
Route::resource('chapters', ChapterController::class);
Route::resource('questions', QuestionController::class);
Route::resource('tests', TestController::class);
Route::post('/questions/import', [QuestionController::class, 'importJson'])->name('questions.importJson');
