<x-adminlte-layout>
    @section('header', 'Items')
    
    @section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des items</h3>
            <div class="card-tools">
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un item
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Coût</th>
                        <th>Prix</th>
                        <th>Catégorie</th>
                        <th>Disponible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->cost }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->is_active ? 'Oui' : 'Non' }}</td>
                            <td>
                                <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endsection
</x-adminlte-layout>
