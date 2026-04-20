<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetSpotifyUserTracksAction
{
    public function execute($token)
    {
        $response = Http::withToken($token)
            ->get('https://api.spotify.com/v1/me/player/recently-played', [
                'limit' => 50
            ]);

        return $response->successful() ? $response->json()['items'] : [];
    }
}