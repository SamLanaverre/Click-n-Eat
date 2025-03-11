<x-app-layout>
    <h1 class="text-2xl font-bold mb-4">Catégories</h1>

    <div class="mb-4">
        <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Créer une catégorie
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restaurant</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('restaurants.show', $category->restaurant->id) }}" class="text-blue-600 hover:text-blue-900">{{ $category->restaurant->name }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('categories.show', $category->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="id" value="{{ $category->id }}">
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
