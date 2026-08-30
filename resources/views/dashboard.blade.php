@extends('layouts.app')

@section('content')
<div class="page-head">
    <span class="eyebrow">Painel</span>
    <h2><i class="fas fa-camera-retro"></i> Bem-vindo ao Busca Foto</h2>
    <p>Escolha abaixo uma das funcionalidades disponíveis no sistema.</p>
</div>

<div class="tile-grid">
    <div class="tile">
        <div class="tile-icon"><i class="fas fa-search"></i></div>
        <h3>Buscar por selfie</h3>
        <p>Encontre suas fotos enviando uma selfie para comparação.</p>
        <a href="{{ route('search.form') }}" class="btn btn-primary btn-block">Buscar</a>
    </div>

    @if(Auth::user()->isAdmin())
    <div class="tile">
        <div class="tile-icon"><i class="fas fa-upload"></i></div>
        <h3>Upload de fotos</h3>
        <p>Envie novas fotos do evento para o sistema.</p>
        <a href="{{ route('upload.form') }}" class="btn btn-accent btn-block">Upload</a>
    </div>
    @endif
</div>
@endsection
