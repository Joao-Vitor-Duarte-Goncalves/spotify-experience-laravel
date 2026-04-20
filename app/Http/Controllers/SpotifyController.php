<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Actions\ImportSpotifyTracksAction;
use App\Actions\GetSpotifyUserTracksAction; // Importe a nova Action

class SpotifyController extends Controller
{
    // Redireciona o usuário para o Spotify
    public function redirect()
    {
        return Socialite::driver('spotify')
            ->scopes(['user-read-recently-played', 'user-read-email'])
            ->redirect();
    }

    // O Spotify devolve o usuário para cá
    public function callback(
        ImportSpotifyTracksAction $importAction, 
        GetSpotifyUserTracksAction $getTracksAction // Injetando a nova Action aqui
    ) {
        $spotifyUser = Socialite::driver('spotify')-> stateless()->user();

        // 1ª Action: Busca os dados (Responsabilidade externa)
        $tracks = $getTracksAction->execute($spotifyUser->token);

        // Verifica se a busca trouxe resultados
        if (!empty($tracks)) {
            
            // 2ª Action: Salva no banco (Responsabilidade interna)
            $importAction->execute($tracks);

            return redirect()->route('dashboard')->with('status', 'Músicas importadas com sucesso!');
        }

        return redirect()->route('dashboard')->with('error', 'Falha ao buscar músicas ou histórico vazio.');
    }
}