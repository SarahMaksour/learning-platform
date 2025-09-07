<?php

namespace App\Services\Quiz;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\CourseContent;
use App\Models\StudentAnswer;
use App\Models\Student_Answer;
use App\Models\PlacementAttempt;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentLessonProgress;

class QuizService
{
    /*  public function getQuiz($quiz_id)
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
    }*/

    public function getQuestion($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $questions = Question::where('quiz_id', $quiz_id)->get();

        $formattedQuestions = [];
        foreach ($questions as  $question) {

           /* $options = $question->option;
            $correct_answer = $question->correct_answer;
*/
            $options = json_decode($question->option, true) ?? [];
        $correct_answer =(int) $question->correct_answer;

            $answers = [];
            foreach ($options as $key => $value) {
                $answers[] = [
                    'answer' => $value,
                    'is_correct' => ($key == $correct_answer)
             //   'is_correct' => ($key == $correct_answer - 1)
                ];
            }
            $formattedQuestions[] = [
                'question_id' => $question->id,
                'question' => $question->text,
                'answer' => $answers,
            ];
        }
        return [
            'quiz_id' => $quiz->id,
            'questions' => $formattedQuestions
        ];
    }
public function submitQuizAnswer(array $data)
{
    $user_id = Auth::id();
    $quiz_id = $data['quiz_id'];
    $answers = $data['answers'] ?? [];

    $correctCount = 0;
    $incorrectCount = 0;

    foreach ($answers as $answer) {
        $question = Question::where('id', $answer['question_id'])
            ->where('quiz_id', $quiz_id)
            ->first();

        if (!$question) continue;

        $options = json_decode($question->option, true) ?? [];

        // correct stored as text
        $correctText = trim(strtolower($question->correct_answer));

        // student answer index
        $studentIndex = isset($answer['student_answer']) ? (int) $answer['student_answer'] : null;
        $studentAnswerText = $options[$studentIndex] ?? null;

        if ($studentAnswerText === null) {
            $incorrectCount++;
            StudentAnswer::updateOrCreate(
                ['user_id' => $user_id, 'question_id' => $question->id],
                ['student_answer' => null, 'is_correct' => false]
            );
            continue;
        }

        $isCorrect = ($correctText === trim(strtolower($studentAnswerText)));

        if ($isCorrect) $correctCount++;
        else $incorrectCount++;

        StudentAnswer::updateOrCreate(
            ['user_id' => $user_id, 'question_id' => $question->id],
            ['student_answer' => $studentAnswerText, 'is_correct' => $isCorrect]
        );
    }

    $totalQuestion = Question::where('quiz_id', $quiz_id)->count();
    $score = $totalQuestion > 0 ? round(($correctCount / $totalQuestion) * 100, 2) : 0;
    $status = ($score >= 60) ? 'passed' : 'failed';

    return [
        'score'         => $score,
        'status'        => $status,
        'correctCount'  => $correctCount,
        'incorrectCount'=> $incorrectCount,
    ];
}

    public function updatePlacementAttempt($quizId, $score)
    {
        $status = $score >= 60 ? 'passed' : 'failed';

    $quiz = Quiz::find($quizId);
    if (!$quiz) {
        return;
    }

    $lesson = $quiz->content;
    if (!$lesson) {
        return;
    }

    // تحديث أو إنشاء التقدّم، بغض النظر عن النتيجة
    StudentLessonProgress::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'content_id' => $lesson->id,
        ],
        [
            'is_passed' => $status === 'passed',
            'score'     => $score,
        ]
    );
            
    }
}
