@extends('layouts.app')

@section('content')
<div class="dashboard-welcome mb-5 text-center">
    <h2 class="fw-semibold mb-3"><i class="fas fa-camera-retro me-2 text-primary"></i>Bem-vindo ao Sistema de Busca Foto</h2>
    <p class="text-muted">Escolha abaixo uma das funcionalidades disponíveis no sistema.</p>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-md-4">
        <div class="card h-100 text-center p-4">
            <div class="card-body">
                <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Buscar por Selfie</h5>
                <p class="card-text">Encontre fotos suas usando Busca Foto.</p>
                <a href="{{ route('search.form') }}" class="btn btn-primary w-100">Buscar</a>
            </div>
        </div>
    </div>

   
    @if(Auth::user()->isAdmin())
    <div class="col-md-4">
        <div class="card h-100 text-center p-4">
            <div class="card-body">
                <i class="fas fa-upload fa-3x mb-3 text-secondary"></i>
                <h5 class="card-title">Upload de Fotos</h5>
                <p class="card-text">Faça upload de novas fotos para o sistema.</p>
                <a href="{{ route('upload.form') }}" class="btn btn-secondary w-100">Upload</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
