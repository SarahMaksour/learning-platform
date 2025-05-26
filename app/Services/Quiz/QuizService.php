<?php

namespace App\Services\Quiz;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\Student_Answer;
use App\Models\PlacementAttempt;
use Illuminate\Support\Facades\Auth;

class QuizService
{
    public function getQuiz($quiz_id)
    {
        $quiz = Quiz::with('questions')->findOrFail($quiz_id);
        return $quiz;
    }

    public function submitAnswer($id_question, $student_answer)
    {
        $question = Question::findOrFail($id_question);
        $is_correct = ($question->correct_answer === $student_answer);
        $answer = StudentAnswer::create([
            'question_id' => $id_question,
            'user_id' => Auth::id(),
            'student_answer' => $student_answer,
            'is_correct' => $is_correct,

        ]);
        return $answer;
    }
    public function calculateQuizScore($quiz_id)
    {
        $user_id = Auth::id();
        $questions = Question::where('quiz_id', $quiz_id)->get();
        $score = 0;
        foreach ($questions as $question) {
            $studentAnswer = StudentAnswer::where('question_id', $question->id)
                ->where('user_id', $user_id)
                ->where('is_correct', true)->first();
            if ($studentAnswer) {
                $score += $question->points;
            }
        }
        return $score;
    }

    public function updatePlacementAttempt($quiz_id, $course_id)
    {
        $score = $this->calculateQuizScore($quiz_id);
        $status = $score >= 60 ? 'completed' : 'failed';
        $attempt = PlacementAttempt::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'quiz_id' => $quiz_id,
                'course_id' => $course_id,
            ],
            [
                'score' => $score,
                'status' => $status,
            ]
        );
        return $attempt;
    }
}
