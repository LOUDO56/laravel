@extends('layout')

@section('content')
<div style="max-width:900px;margin:2rem auto;font-family:sans-serif;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h1 style="font-size:1.8rem;margin:0;">Matières</h1>
        <a href="/dashboard" style="color:#3b5bdb;text-decoration:none;">← Tableau de bord</a>
    </div>

    @if($subjects->isEmpty())
        <div style="text-align:center;padding:3rem;color:#888;background:#f8f8f8;border-radius:8px;">
            <p style="font-size:1.1rem;">Aucune matière disponible.</p>
        </div>
    @else
    <div style="display:grid;gap:1rem;">
        @foreach($subjects as $subject)
        <div style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:1.2rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="margin:0 0 0.3rem 0;font-size:1.1rem;">{{ $subject->name }}</h2>
                <div style="color:#666;font-size:0.85rem;">
                    {{ $subject->school->name }} &bull;
                    {{ $subject->contents_count }} vidéo(s) &bull;
                    @if($subject->expected_hours)
                        {{ $subject->expected_hours }}h attendues &bull;
                    @endif
                    @if($subject->referential_path)
                        <span style="color:#2f9e44;">PDF disponible</span>
                    @else
                        <span style="color:#aaa;">Pas de référentiel</span>
                    @endif
                </div>
                @if($subject->description)
                <p style="margin:0.5rem 0 0 0;color:#555;font-size:0.9rem;">{{ Str::limit($subject->description, 120) }}</p>
                @endif
            </div>
            <div style="flex-shrink:0;margin-left:1rem;">
                <a href="/subjects/{{ $subject->id }}"
                   style="background:#3b5bdb;color:#fff;padding:0.5rem 1rem;border-radius:5px;text-decoration:none;font-size:0.9rem;white-space:nowrap;">
                    Voir les cours
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
