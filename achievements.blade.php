@extends('layouts.app')
@section('title', 'Pencapaian')

@section('content')
<div class="animate-fade-in">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span class="tag-decoration">🏆 Ruang Trofi</span>
            <h1 class="page-title">Pencapaian & Lencana</h1>
            <p class="page-subtitle">Selesaikan tugas untuk membuka lencana prestisius!</p>
        </div>
        <div class="card" style="padding: 1rem 1.5rem; background: var(--pink); border-color: var(--black); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2.5rem;">🏆</div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; line-height: 1.1;">{{ $unlockedCount }} / {{ $totalBadges }}</div>
                <div style="font-size: 0.85rem; font-weight: 600;">Lencana Terbuka</div>
            </div>
        </div>
    </div>

    <!-- Badge Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        @foreach($badges as $badge)
        <div class="card animate-bounce-in" style="
            background: {{ $badge['unlocked'] ? $badge['color'] : 'var(--white)' }}; 
            border-color: var(--black); 
            opacity: {{ $badge['unlocked'] ? '1' : '0.85' }}; 
            filter: {{ $badge['unlocked'] ? 'none' : 'grayscale(20%)' }};
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        ">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="
                        font-size: 2.5rem; 
                        background: var(--white); 
                        border: 2px solid var(--black); 
                        border-radius: 50%; 
                        width: 60px; 
                        height: 60px; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center;
                        box-shadow: 2px 2px 0px var(--black);
                    ">
                        {{ $badge['unlocked'] ? $badge['icon'] : '🔒' }}
                    </div>
                    @if($badge['unlocked'])
                    <span style="background: var(--black); color: var(--white); border-radius: 50px; padding: 0.2rem 0.6rem; font-size: 0.75rem; font-weight: 700; border: 2px solid var(--black);">Terbuka</span>
                    @else
                    <span style="background: #e0e0e0; border: 2px solid var(--black); border-radius: 50px; padding: 0.2rem 0.6rem; font-size: 0.75rem; font-weight: 700; color: #666;">Terkunci</span>
                    @endif
                </div>
                
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--black);">{{ $badge['title'] }}</h3>
                <p style="font-size: 0.9rem; font-weight: 600; color: rgba(26, 26, 26, 0.75); line-height: 1.4; margin-bottom: 1.5rem;">{{ $badge['description'] }}</p>
            </div>

            <div style="margin-top: auto;">
                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.3rem; color: var(--black);">
                    <span>Progres</span>
                    <span>{{ min($badge['current'], $badge['target']) }} / {{ $badge['target'] }}</span>
                </div>
                <div class="progress-bar-track" style="height: 10px; border-radius: 5px; background: rgba(0,0,0,0.1); border: 2px solid var(--black); overflow: hidden;">
                    <div class="progress-bar-fill" style="
                        width: {{ min(100, round(($badge['current'] / $badge['target']) * 100)) }}%; 
                        border-radius: 0; 
                        background: var(--black);
                        height: 100%;
                    "></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
