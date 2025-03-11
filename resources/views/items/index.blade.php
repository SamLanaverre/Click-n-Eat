<x-app-layout>
    <h1 class="text-2xl font-bold mb-4">Items</h1>

    <div class="mb-4">
        <a href="{{ route('items.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Créer un item
        </a>
    </div>

    @if($items->isEmpty())
        <p class="text-gray-500">Aucun item disponible.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponible</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->cost }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->is_active ? 'Oui' : 'Non' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                <a href="{{ route('items.show', $item->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                <a href="{{ route('items.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-app-layout>
