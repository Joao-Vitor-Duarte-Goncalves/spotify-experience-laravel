<?php

namespace App\Actions;

use App\Models\RecentTrack;
use Illuminate\Support\Facades\Auth;

class ImportSpotifyTracksAction
{
    public function execute(array $items)
    {
        foreach ($items as $item) {
            $track = $item['track']; // Os dados da música estão aqui

            \App\Models\RecentTrack::updateOrCreate(
                [
                    'spotify_id' => $track['id'], 
                    'user_id' => auth()->id()
                ],
                [
                    'name' => $track['name'],
                    'artist' => $track['artists'][0]['name'],
                    // AQUI ESTÁ A MUDANÇA: usamos $item e não $track
                    'played_at' => \Carbon\Carbon::parse($item['played_at']),
                ]
            );
        }
    }
}