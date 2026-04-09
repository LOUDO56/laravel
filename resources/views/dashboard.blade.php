@extends('layout')

@section('content')
<div style="max-width:900px;margin:2rem auto;font-family:sans-serif;">
    <h1 style="font-size:1.8rem;margin-bottom:0.5rem;">Tableau de bord</h1>

    <div style="background:#f0f4ff;border-radius:8px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;gap:2rem;align-items:center;">
        <div>
            <strong>{{ auth()->user()->name }}</strong><br>
            <span style="font-size:0.85rem;color:#555;">{{ auth()->user()->email }}</span>
        </div>
        <div>
            <span style="background:#3b5bdb;color:#fff;border-radius:4px;padding:2px 10px;font-size:0.8rem;">
                {{ auth()->user()->role->value }}
            </span>
        </div>
        @if(auth()->user()->school)
        <div>
            École : <strong>{{ auth()->user()->school->name }}</strong>
        </div>
        @endif
    </div>

    {{-- Section Administrateur --}}
    @role('admin_school')
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;border-bottom:2px solid #3b5bdb;padding-bottom:0.3rem;margin-bottom:1rem;">
            Administration — {{ auth()->user()->school?->name ?? 'Mon École' }}
        </h2>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:1rem;text-align:center;">
                <div style="font-size:2rem;font-weight:bold;color:#3b5bdb;">{{ $stats['classrooms'] ?? 0 }}</div>
                <div>Classes</div>
            </div>
            <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:1rem;text-align:center;">
                <div style="font-size:2rem;font-weight:bold;color:#3b5bdb;">{{ $stats['subjects'] ?? 0 }}</div>
                <div>Matières</div>
            </div>
            <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:1rem;text-align:center;">
                <div style="font-size:2rem;font-weight:bold;color:#3b5bdb;">{{ $stats['students'] ?? 0 }}</div>
                <div>Étudiants</div>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <a href="/subjects" style="background:#3b5bdb;color:#fff;padding:0.5rem 1.2rem;border-radius:5px;text-decoration:none;">
                Gérer les matières
            </a>
        </div>
    </section>
    @endrole

    {{-- Section Formateur --}}
    @role('teacher')
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;border-bottom:2px solid #2f9e44;padding-bottom:0.3rem;margin-bottom:1rem;">
            Mes matières à enseigner
        </h2>
        @if($subjects->isEmpty())
            <p style="color:#888;">Aucune matière assignée pour le moment.</p>
        @else
        <ul style="list-style:none;padding:0;">
            @foreach($subjects as $subject)
            <li style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:0.8rem 1rem;margin-bottom:0.5rem;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <strong>{{ $subject->name }}</strong>
                    <span style="color:#888;font-size:0.85rem;margin-left:0.5rem;">{{ $subject->contents_count ?? 0 }} vidéo(s)</span>
                </div>
                <a href="/subjects/{{ $subject->id }}" style="color:#2f9e44;text-decoration:none;font-size:0.9rem;">Voir →</a>
            </li>
            @endforeach
        </ul>
        @endif
    </section>
    @endrole

    {{-- Section Étudiant --}}
    @role('student')
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;border-bottom:2px solid #e67700;padding-bottom:0.3rem;margin-bottom:1rem;">
            Mes classes
        </h2>
        @if($classrooms->isEmpty())
            <p style="color:#888;">Vous n'êtes inscrit à aucune classe pour le moment.</p>
        @else
        @foreach($classrooms as $classroom)
        <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:1rem;margin-bottom:1rem;">
            <h3 style="margin:0 0 0.5rem 0;">{{ $classroom->name }}</h3>
            @if($classroom->subjects->isEmpty())
                <p style="color:#aaa;font-size:0.9rem;">Aucune matière dans cette classe.</p>
            @else
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                @foreach($classroom->subjects as $subject)
                <a href="/subjects/{{ $subject->id }}"
                   style="background:#fff4e6;border:1px solid #e67700;color:#e67700;padding:4px 12px;border-radius:20px;font-size:0.85rem;text-decoration:none;">
                    {{ $subject->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
        @endif
    </section>
    @endrole
</div>
@endsection
