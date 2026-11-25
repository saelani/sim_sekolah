<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $exam->title }} | {{ config('app.name', 'Sekolah') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8f7f3; margin: 0; color: #1a1a1a; }
        .topbar { position: sticky; top: 0; z-index: 50; background: #1a3a5c; color: #fff; padding: 14px 20px; }
        .topbar-inner { max-width: 960px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .timer-box { background: #fff; color: #1a3a5c; font-weight: 700; padding: 8px 16px; border-radius: 8px; }
        .timer-box.danger { background: #dc3545; color: #fff; }
        .container { max-width: 960px; margin: 0 auto; padding: 24px 20px 60px; }
        .card { background: #fff; border-radius: 12px; padding: 20px 24px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .nav-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .nav-num { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; color: #1a3a5c; font-weight: 600; }
        .nav-num.answered { background: #1a3a5c; color: #fff; border-color: #1a3a5c; }
        .option-item { display: flex; gap: 10px; padding: 12px 14px; border: 1px solid #e3e0d8; border-radius: 10px; margin-top: 8px; cursor: pointer; }
        .essay-input { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px; font-family: inherit; }
        .submit-box { background: #fff; border-radius: 12px; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .btn-submit { background: #198754; color: #fff; border: none; padding: 12px 26px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-back { background: #e2e8f0; color: #1e293b; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; }
    </style>
</head>
<body>
<div class="topbar">
    <div class="topbar-inner">
        <div>
            <div style="font-weight:700;">{{ $exam->title }}</div>
            <div style="font-size:.85rem;opacity:.85;">{{ $exam->subject?->name ?? '-' }} · Kelas: {{ $exam->schoolClass?->name ?? 'Semua Kelas' }}</div>
        </div>
        <div class="timer-box" id="timerBox">Sisa Waktu: <span id="timerText">--:--</span></div>
    </div>
</div>

<div class="container">
    <div class="card" style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div><strong>Peserta:</strong> {{ $student->name }} ({{ $student->nis }})</div>
        <div><strong>Terjawab:</strong> <span id="answeredCount">0</span> / {{ $questions->count() }}</div>
    </div>

    @if ($questions->isNotEmpty())
        <div class="card">
            <strong>Navigasi Soal</strong>
            <div class="nav-grid">
                @foreach ($questions as $i => $q)
                    <a href="#soal-{{ $i + 1 }}" class="nav-num" id="nav-soal-{{ $q->id }}">{{ $i + 1 }}</a>
                @endforeach
            </div>
        </div>
    @endif

    <form id="examForm" method="POST" action="{{ route('student.exam.submit', ['nis' => $student->nis, 'token' => $exam->token, 'id' => $exam->id]) }}">
        @csrf
        @php $letters = ['A', 'B', 'C', 'D', 'E', 'F']; @endphp

        @forelse ($questions as $i => $q)
            <div class="card question-card" id="soal-{{ $i + 1 }}" data-question-id="{{ $q->id }}">
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <strong>Soal No. {{ $i + 1 }}</strong>
                    <span style="font-size:.8rem;color:#64748b;">{{ ($q->type ?? 'multiple_choice') === 'essay' ? 'Uraian / Essay' : 'Pilihan Ganda' }}</span>
                </div>
                <div style="margin-bottom:12px;line-height:1.6;">{!! $q->question_text !!}</div>

                @if ($q->media_path)
                    <div style="margin-bottom:12px;">
                        <img src="{{ str_starts_with($q->media_path, 'http') ? $q->media_path : asset('storage/' . ltrim($q->media_path, '/')) }}" alt="Media Soal" style="max-width:100%;max-height:260px;border-radius:8px;">
                    </div>
                @endif

                @if (($q->type ?? 'multiple_choice') === 'essay')
                    <textarea name="answers[{{ $q->id }}]" rows="4" class="essay-input" placeholder="Tulis jawaban Anda..."></textarea>
                @else
                    @foreach ($q->options as $optIdx => $opt)
                        <label class="option-item">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" onchange="updateProgress()">
                            <strong>{{ $letters[$optIdx] ?? ($optIdx + 1) }}.</strong>
                            <span>
                                {{ $opt->option_text }}
                                @if ($opt->media_path)
                                    <br><img src="{{ str_starts_with($opt->media_path, 'http') ? $opt->media_path : asset('storage/' . ltrim($opt->media_path, '/')) }}" alt="Opsi" style="max-width:220px;max-height:160px;border-radius:6px;margin-top:6px;">
                                @endif
                            </span>
                        </label>
                    @endforeach
                @endif
            </div>
        @empty
            <div class="card"><p style="text-align:center;color:#7a7a7a;margin:0;">Belum ada soal pada ujian ini.</p></div>
        @endforelse

        <div class="portal-card submit-box" style="transform: none;">
            <a href="{{ route('student.dashboard', ['nis' => $student->nis, 'tab' => 'ulangan']) }}" class="btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>

            @if ($questions->isNotEmpty())
                <button type="submit" class="btn-submit" onclick="return confirm('Yakin ingin mengumpulkan jawaban?')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Kumpulkan Jawaban</span>
                </button>
            @endif
        </div>
    </form>
</div>

<script>
    let remainingSeconds = {{ max(1, (int) ($exam->duration_minutes ?? 45)) * 60 }};
    const timerTextEl = document.getElementById('timerText');
    const timerBoxEl = document.getElementById('timerBox');
    const examFormEl = document.getElementById('examForm');

    function updateTimer() {
        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        timerTextEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        if (remainingSeconds <= 300) timerBoxEl.classList.add('danger');
        if (remainingSeconds <= 0) {
            clearInterval(timerInterval);
            alert('Waktu ujian habis. Jawaban dikirim otomatis.');
            examFormEl.submit();
            return;
        }
        remainingSeconds--;
    }

    function updateProgress() {
        let answered = 0;
        document.querySelectorAll('.question-card').forEach(card => {
            const qId = card.getAttribute('data-question-id');
            const navBtn = document.getElementById('nav-soal-' + qId);
            const checked = card.querySelector('input[type="radio"]:checked');
            const textarea = card.querySelector('textarea');
            const done = Boolean(checked) || (textarea && textarea.value.trim() !== '');
            if (done) {
                answered++;
                if (navBtn) navBtn.classList.add('answered');
            } else if (navBtn) {
                navBtn.classList.remove('answered');
            }
        });
        document.getElementById('answeredCount').textContent = answered;
    }

    document.querySelectorAll('textarea').forEach(ta => ta.addEventListener('input', updateProgress));
    updateTimer();
    updateProgress();
    const timerInterval = setInterval(updateTimer, 1000);
</script>
</body>
</html>