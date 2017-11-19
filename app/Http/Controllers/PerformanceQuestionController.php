<?php

namespace App\Http\Controllers;

use App\PerformanceQuestion;
use Illuminate\Http\Request;

class PerformanceQuestionController extends Controller
{
    public function showAll()
    {
        return PerformanceQuestion::all();
    }

    public function create(Request $request)
    {
        //
    }

    public function read($id)
    {
        return $this->getPerformanceQuestion($id);
    }

    public function update()
    {
        //
    }

    public function delete($id)
    {
        $performaceQuestion = $this->getPerformanceQuestion($id);
        $performaceQuestion->delete();
        return $performaceQuestion->id;
    }

    public function query($key, $value)
    {
        return PerformanceQuestion::where($key, $value)->first();
    }

    public function getQuestions($id)
    {
        return PerformanceQuestion::find($id)->question;
    }

    private function getPerformanceQuestion($id)
    {
        return PerformanceQuestion::find($id);
    }
}
