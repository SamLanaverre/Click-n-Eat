<x-adminlte-layout>
    @section('header', 'Restaurants')
    
    @section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des restaurants</h3>
            <div class="card-tools">
                <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un restaurant
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restaurants as $restaurant)
                        <tr>
                            <td>{{ $restaurant->id }}</td>
                            <td>{{ $restaurant->name }}</td>
                            <td>
                                <a href="{{ route('restaurants.show', $restaurant->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="id" value="{{ $restaurant->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce restaurant?')">
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