<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Models\RecentTrack;
use Illuminate\Http\Request;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    $search = $request->query('search');
    $user = auth()->user();

    // Query base
    $query = RecentTrack::where('user_id', $user->id);

    // Dados para os Cards
    $totalTracks = (clone $query)->count();
    
    $topArtist = (clone $query)
        ->select('artist', \DB::raw('count(*) as total'))
        ->groupBy('artist')
        ->orderByDesc('total')
        ->first();

    $lastSync = (clone $query)->latest('created_at')->first();

    // Paginação com filtro
    $tracks = $query->when($search, function ($q, $search) {
            return $q->where(function ($sub) use ($search) {
                $sub->where('name', 'ilike', "%{$search}%")
                    ->orWhere('artist', 'ilike', "%{$search}%");
            });
        })
        ->orderBy('played_at', 'desc')
        ->paginate(10)
        ->withQueryString();

    return view('dashboard', compact('tracks', 'totalTracks', 'topArtist', 'lastSync'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Rota para iniciar o login no Spotify
Route::get('/auth/spotify', [SpotifyController::class, 'redirect'])->name('spotify.redirect');

// Rota de retorno (aquela que você tentou cadastrar no site do Spotify)
Route::get('/callback', [SpotifyController::class, 'callback'])->middleware('auth');