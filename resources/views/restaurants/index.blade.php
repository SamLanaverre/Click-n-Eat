@extends('layout.main')

@section('main')
    <h1 class="text-2xl font-bold mb-4">Restaurants</h1>

    <div class="mb-4">
        <a href="{{ route('restaurants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Créer un restaurant
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($restaurants as $restaurant)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $restaurant->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $restaurant->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('restaurants.show', $restaurant->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('restaurants.edit', $restaurant->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST" class="inline">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="id" value="{{ $restaurant->id }}">
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        console.log("scripts !");
    </script>
@endsection