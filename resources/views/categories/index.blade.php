<x-adminlte-layout>
    @section('header', 'Catégories')
    
    @section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des catégories</h3>
            <div class="card-tools">
                @if(auth()->user() && auth()->user()->isRestaurateur())
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer une catégorie
                </a>
                @endif
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Restaurant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <a href="{{ route('restaurants.show', $category->restaurant->id) }}" class="text-blue-600 hover:text-blue-900">{{ $category->restaurant->name }}</a>
                            </td>
                            <td>
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user() && auth()->user()->isRestaurateur())
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="id" value="{{ $category->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endsection
</x-adminlte-layout>
