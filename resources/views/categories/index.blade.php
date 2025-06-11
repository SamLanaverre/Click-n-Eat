@extends('layout.adminlte')

@section('header', 'Catégories')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Catégories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Catégories</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des catégories</h3>
                <div class="card-tools">
                    @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isRestaurateur()))
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nouvelle catégorie
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($categories as $category)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                    <p class="card-text">
                                        <span class="badge badge-info">{{ $category->items_count ?? 0 }} items</span>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="btn-group">
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Éditer
                                            </a>
                                        @else
                                            <a href="{{ route('categories.show', $category) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-eye"></i> Détails
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Aucune catégorie disponible pour le moment.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer clearfix">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
