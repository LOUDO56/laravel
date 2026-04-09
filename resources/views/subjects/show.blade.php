@extends('layout')

@section('content')
<div style="max-width:900px;margin:2rem auto;font-family:sans-serif;">
    {{-- En-tête --}}
    <div style="margin-bottom:1.5rem;">
        <a href="/subjects" style="color:#3b5bdb;text-decoration:none;font-size:0.9rem;">← Toutes les matières</a>
        <h1 style="font-size:1.8rem;margin:0.5rem 0 0.3rem 0;">{{ $subject->name }}</h1>
        <div style="color:#666;font-size:0.9rem;">
            {{ $subject->school->name }}
            @if($subject->expected_hours)
                &bull; {{ $subject->expected_hours }}h prévues
            @endif
        </div>
        @if($subject->description)
        <p style="margin-top:0.7rem;color:#444;">{{ $subject->description }}</p>
        @endif

        @if($subject->referential_path)
        <div style="margin-top:0.7rem;">
            <span style="background:#ebfbee;color:#2f9e44;border:1px solid #2f9e44;border-radius:4px;padding:3px 10px;font-size:0.85rem;">
                Référentiel PDF disponible — {{ $subject->referential_name }}
                ({{ number_format($subject->referential_size / 1024, 1) }} Ko)
            </span>
        </div>
        @endif
    </div>

    {{-- Liste des contenus --}}
    <h2 style="font-size:1.2rem;border-bottom:2px solid #eee;padding-bottom:0.4rem;margin-bottom:1rem;">
        Contenus pédagogiques ({{ $subject->contents->count() }} vidéo(s))
    </h2>

    @if($subject->contents->isEmpty())
        <div style="text-align:center;padding:2rem;color:#888;background:#f8f8f8;border-radius:8px;">
            Aucun contenu dans cette matière pour l'instant.
        </div>
    @else
    <div style="display:grid;gap:0.8rem;">
        @foreach($subject->contents as $index => $content)
        @php
            $progress = $progressMap[$content->id] ?? null;
            $pct = 0;
            if ($progress && $content->duration_seconds) {
                $pct = min(100, round($progress->watched_seconds / $content->duration_seconds * 100));
            }
        @endphp
        <div style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:1rem 1.2rem;">
            <div style="display:flex;align-items:center;gap:0.8rem;margin-bottom:0.5rem;">
                <span style="background:#3b5bdb;color:#fff;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;">
                    {{ $index + 1 }}
                </span>
                <div style="flex:1;">
                    <h3 style="margin:0;font-size:1rem;">{{ $content->title }}</h3>
                    <div style="color:#888;font-size:0.8rem;margin-top:2px;">
                        @if($content->duration_seconds)
                            {{ gmdate('i\min s\s', $content->duration_seconds) }}
                        @endif
                        &bull; Par {{ $content->teacher->name }}
                    </div>
                </div>
                @if($progress && $progress->completed)
                <span style="background:#ebfbee;color:#2f9e44;border-radius:4px;padding:2px 8px;font-size:0.8rem;flex-shrink:0;">✓ Terminé</span>
                @elseif($progress && $progress->watched_seconds > 0)
                <span style="background:#fff4e6;color:#e67700;border-radius:4px;padding:2px 8px;font-size:0.8rem;flex-shrink:0;">En cours</span>
                @endif
            </div>

            @if($content->description)
            <p style="margin:0 0 0.7rem 0;color:#555;font-size:0.88rem;padding-left:2.4rem;">{{ $content->description }}</p>
            @endif

            {{-- Barre de progression --}}
            @auth
            <div style="padding-left:2.4rem;">
                <div style="background:#f0f0f0;border-radius:4px;height:6px;overflow:hidden;">
                    <div style="background:{{ $pct >= 90 ? '#2f9e44' : '#3b5bdb' }};height:100%;width:{{ $pct }}%;transition:width 0.3s;"></div>
                </div>
                <div style="font-size:0.78rem;color:#888;margin-top:3px;">{{ $pct }}% visionné</div>
            </div>
            @endauth

            <div style="padding-left:2.4rem;margin-top:0.7rem;">
                <a href="/contents/{{ $content->id }}"
                   style="color:#3b5bdb;font-size:0.9rem;text-decoration:none;">
                    ▶ Regarder la vidéo →
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
