<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total de Músicas</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTracks ?? 0 }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Artista Favorito</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $topArtist->artist ?? '---' }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Última Sincronização</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ isset($lastSync) ? $lastSync->created_at->timezone('America/Sao_Paulo')->format('d/m H:i') : 'Nunca' }}
                    </p>
                </div>
            </div>

            <div class="mb-8">
                <a href="{{ route('spotify.redirect') }}" 
                onclick="showLoading(this)"
                id="syncButton"
                class="inline-flex items-center px-6 py-3 bg-[#1DB954] border border-transparent rounded-md font-bold text-sm text-white uppercase tracking-widest hover:bg-[#1ed760] transition ease-in-out duration-150 shadow-lg">
                    
                    <span id="buttonTextDefault">Conectar com Spotify</span>

                    <span id="loadingSpinner" class="hidden flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sincronizando...
                    </span>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4 dark:text-white">Minhas Músicas Recentes</h3>

                <div class="mb-6 flex justify-between items-center">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex w-full max-w-sm">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar música ou artista..." 
                            class="w-full rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900">
                            Buscar
                        </button>
                    </form>

                    @if(request('search'))
                        <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">
                            Limpar Filtro
                        </a>
                    @endif
                </div>
                
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Música</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Artista</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ouvida em</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($tracks as $track)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">{{ $track->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">{{ $track->artist }}</td>
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">
                                    {{ $track->played_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                        <p>Nenhuma música encontrada. Clique em "Conectar com Spotify" para sincronizar seu histórico.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="mt-4">
                    {{ $tracks->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoading(button) {
            // 1. Esconde o texto padrão "Conectar com Spotify"
            const defaultText = document.getElementById('buttonTextDefault');
            if (defaultText) defaultText.classList.add('hidden');

            // 2. Mostra o container com o Spinner + "Sincronizando..."
            const loadingContent = document.getElementById('loadingSpinner');
            if (loadingContent) loadingContent.classList.remove('hidden');

            // 3. Desativa cliques repetidos e muda o visual
            button.classList.add('opacity-75', 'pointer-events-none');
        }
    </script>
</x-app-layout>