@extends('layout')

@section('page_title')
    🔍 Résultat de la recherche IA
@endsection

@section('page_subtitle')
    Recommandations intelligentes basées sur votre recherche
@endsection

@section('content')

{{-- 🔵 TEXTE IA --}}
<div style="
    background:white;
    padding:20px;
    border-radius:12px;
    line-height:1.8;
    margin-bottom:30px;
">

    <h2 style="margin-bottom:10px;">🤖 Réponse IA</h2>

    {!! nl2br(e($resultat)) !!}

</div>

{{-- 📚 LIVRES RECOMMANDÉS --}}
@if(isset($livresRecommandes) && $livresRecommandes->count() > 0)

    <h2 style="margin-bottom:15px;">📚 Livres recommandés</h2>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
        gap:20px;
    ">

        @foreach($livresRecommandes as $livre)

            <div style="
                background:white;
                padding:15px;
                border-radius:12px;
                box-shadow:0 2px 10px rgba(0,0,0,0.08);
                transition:0.3s;
            ">

                {{-- IMAGE --}}
                @if($livre->image)
                    <img src="{{ asset('storage/' . $livre->image) }}"
                         style="
                            width:100%;
                            height:240px;
                            object-fit:cover;
                            border-radius:10px;
                         ">
                @else
                    <div style="
                        width:100%;
                        height:240px;
                        background:#eee;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        color:#888;
                    ">
                        Pas d'image
                    </div>
                @endif

                {{-- TITRE --}}
                <h3 style="margin-top:12px;">
                    {{ $livre->titre }}
                </h3>

                {{-- CATEGORIE --}}
                <p style="color:#6b7280;">
                    {{ $livre->categorie->nom ?? 'Non classé' }}
                </p>

                {{-- STOCK --}}
                <p>
                    Stock :
                    <strong>{{ $livre->stock }}</strong>
                </p>

                {{-- ACTION --}}
                @auth
                    @if($livre->stock > 0)

                        <form action="{{ route('emprunter', $livre->id) }}" method="POST">
                            @csrf

                            <button type="submit" style="
                                background:#2563eb;
                                color:white;
                                border:none;
                                padding:8px 12px;
                                border-radius:8px;
                                cursor:pointer;
                                width:100%;
                            ">
                                📖 Emprunter
                            </button>
                        </form>

                    @else

                        <button disabled style="
                            background:#ccc;
                            color:#666;
                            border:none;
                            padding:8px 12px;
                            border-radius:8px;
                            width:100%;
                        ">
                            Indisponible
                        </button>

                    @endif
                @else

                    <a href="{{ route('login') }}" style="
                        display:block;
                        text-align:center;
                        background:#2563eb;
                        color:white;
                        padding:8px 12px;
                        border-radius:8px;
                        text-decoration:none;
                    ">
                        Se connecter pour emprunter
                    </a>

                @endauth

            </div>

        @endforeach

    </div>

@else

    <p style="color:#6b7280;">
        Aucun livre correspondant trouvé.
    </p>

@endif

@endsection