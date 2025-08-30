@extends('new-dashboard.layouts.app_dashborad')

@section('title', 'Quizzes Dashboard')

@section('styles')
<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    transition: 0.3s;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Section 1: Summary Cards -->
    <section class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5>Total Quizzes</h5>
                        <h3>{{ $totalQuizzes }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5>Total Completed Students</h5>
                        <h3>{{ $totalCompletedStudents }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Quizzes Cards -->
    <section class="mb-4">
        <div class="row g-3">
            @forelse($quizzes as $quiz)
            @php
                // الطلاب المكتملين وقيد التقدم
                $completedCount = $quiz->placementAttempts->where('status','completed')->count();
                $inProgressCount = $quiz->placementAttempts->where('status','in-progress')->count();
                $studentsCount = $quiz->course->students_count ?? 1;

                $progressCompleted = round(($completedCount / $studentsCount) * 100);
                $progressInProgress = round(($inProgressCount / $studentsCount) * 100);

                // أعلى نتيجة
                $topScore = $quiz->placementAttempts->max('score') ?? 0;

                // متوسط النسبة
                $avgProgress = round(($completedCount / $studentsCount) * 100);

                // لون progress
                $progressColor = $avgProgress < 50 ? 'bg-danger' : ($avgProgress < 80 ? 'bg-warning' : 'bg-success');

            @endphp

            <div class="col-md-4">
                <div class="card shadow-sm hover-shadow">
                    <div class="card-body">
                        <h5>{{ $quiz->title }}</h5>
                       <p>Content: 
    @if($quiz->content && $quiz->content->contentable)
        {{ $quiz->content->contentable->title ?? 'N/A' }} 
        ({{ class_basename($quiz->content->contentable) }})
    @else
        N/A
    @endif
</p>
<p>Course: {{ $quiz->course->title ?? 'N/A' }}</p>
                        <p>Questions: {{ $quiz->questions_count ?? $quiz->questions->count() }}</p>
                        <p>Students Completed: {{ $completedCount }} | In-Progress: {{ $inProgressCount }}</p>

                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $progressCompleted }}%">
                                {{ $progressCompleted }}%
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progressInProgress }}%">
                                {{ $progressInProgress }}%
                            </div>
                        </div>

                        <p>Top Score: {{ $topScore }} / {{ $quiz->total_point }}</p>
                        <p>Average Completion: {{ $avgProgress }}%</p>

                        <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $quiz->is_active ? 'Active' : 'Finished' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    لا توجد كويزات حتى الآن.
                </div>
            </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
