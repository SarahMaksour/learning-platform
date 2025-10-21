@extends('new-dashboard.layouts.app_dashborad')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Section 1: Summary Cards -->
  <section>
    <div class="row g-3 mb-4">
      @php
        $cards = [
          ['icon' => 'bx-book', 'color' => 'text-info', 'count' => $counts['courses'], 'label' => 'Courses'],
          ['icon' => 'bx-user', 'color' => 'text-success', 'count' => $counts['students'], 'label' => 'Students'],
          ['icon' => 'bx-chalkboard', 'color' => 'text-warning', 'count' => $counts['instructors'], 'label' => 'Instructors'],
          ['icon' => 'bx-dollar-circle', 'color' => 'text-danger', 'count' => '$' . number_format($counts['sales'], 2), 'label' => 'Sales'],
        ];
      @endphp

      @foreach ($cards as $card)
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <i class="bx {{ $card['icon'] }} {{ $card['color'] }} bx-lg"></i>
              <div class="text-end">
                <h3 class="mb-1">{{ $card['count'] }}</h3>
                <p class="mb-0">{{ $card['label'] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </section>

  <!-- Section 2: Charts / Highlights -->
  <section>
    <div class="row g-3 mb-4">
      <div class="col-xl-6 col-md-12">
        <div class="card shadow-sm">
          <div class="card-body">
          <h5 class="mb-3">reecent Sales</h5>
        @if(array_sum($monthlyStudents) + array_sum($monthlySales) > 0)
            @foreach(range(1,12) as $i)
            <div class="mb-3">
                <strong>{{ date('F', mktime(0,0,0,$i,1)) }}</strong>
                <div class="progress mb-1" style="height: 25px;">
                    <div class="progress-bar bg-info" role="progressbar"
                        style="width: {{ min($monthlyStudents[$i-1],100) }}%"
                        aria-valuenow="{{ $monthlyStudents[$i-1] }}"
                        aria-valuemin="0" aria-valuemax="100">
                        {{ $monthlyStudents[$i-1] }} طلاب
                    </div>
                    <div class="progress-bar bg-success" role="progressbar"
                        style="width: {{ min($monthlySales[$i-1]/100,100) }}%"
                        aria-valuenow="{{ $monthlySales[$i-1] }}"
                        aria-valuemin="0" aria-valuemax="100">
                        ${{ $monthlySales[$i-1] }}
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-info text-center">
                لا توجد بيانات لعرضها لهذا العام. ابدأ الآن لإضافة الطلاب والمبيعات!
            </div>
        @endif       </div>
        </div>
      </div>

      <div class="col-xl-6 col-md-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Recent Enrollments</h5>
            @forelse ($recentEnrollments as $enroll)
            <div class="mb-3 border-bottom pb-2">
              <strong>{{ $enroll->student->name }}</strong> enrolled in <em>{{ $enroll->course->title }}</em><br>
              <small class="text-muted">{{ $enroll->created_at->diffForHumans() }}</small>

              <!-- عرض التقدم المحسوب مسبقًا -->
              <div class="progress mt-1">
                <div class="progress-bar" role="progressbar" style="width: {{ $enroll->progress }}%;" aria-valuenow="{{ $enroll->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $enroll->progress }}%</div>
              </div>
            </div>
            @empty
              <p class="text-muted">No recent enrollments.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 3: Quick Links -->

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>const ctx = document.getElementById('dashboardChart').getContext('2d');

const dashboardChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyLabels) !!},
        datasets: [
            {
                label: 'Students',
                data: {!! json_encode($monthlyStudents) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Sales ($)',
                data: {!! json_encode($monthlySales) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            // إضافة نص عند عدم وجود بيانات
            tooltip: {
                enabled: true
            },
        },
        scales: {
            y: { beginAtZero: true }
        }
    },
    plugins: [{
        id: 'emptyChartText',
        afterDraw: (chart) => {
            if (chart.data.datasets.every(ds => ds.data.length === 0)) {
                const ctx = chart.ctx;
                const width = chart.width;
                const height = chart.height;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#666';
                ctx.font = '16px sans-serif';
                ctx.fillText('لا توجد بيانات لعرضها', width / 2, height / 2);
                ctx.restore();
            }
        }
    }]
});

</script>
@endsection
