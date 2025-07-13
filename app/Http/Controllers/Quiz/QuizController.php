<?php

namespace App\Http\Controllers\Quiz;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Requests\QuizRequest;
use App\Services\Quiz\QuizService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShowQuizResource;

class QuizController extends Controller
{
    protected $quizService;
    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }
/*
    public function getQuizWithQuestion($id)
    {
        $quiz = $this->quizService->getQuiz($id);
        return response()->json([
            'quiz' => new ShowQuizResource($quiz)
        ], 201);
    }

    public function submitAnswer(QuizRequest $request)
    {
        $answer = $this->quizService->submitAnswer($request->question_id, $request->student_answer);
        return response()->json([
            'message' => 'Answer recorded',
            'is_correct' => $answer->is_correct
        ], 201);
    }

    public function finalizeQuiz(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $attempt=$this->quizService->updatePlacementAttempt($request->quiz_id, $request->course_id);
     return response()->json([
            'message' => 'Quiz finalized',
            'score' => $attempt->score,
            'status' => $attempt->status,
        ],201);
    }*/
    public function show($content_id){
            $quiz = Quiz::where('content_id', $content_id)->firstOrFail();
        $data=$this->quizService->getQuestion($quiz->id);
         return response()->json($data);
    }
    public function submit(QuizRequest $request){
        $data=$request->validated();
        $result=$this->quizService->submitQuizAnswer($data);
        return response()->json([
            $result
        ],201);

    }
}
