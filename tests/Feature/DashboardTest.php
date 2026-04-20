<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RecentTrack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_is_displayed_for_authenticated_users()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Minhas Músicas Recentes');
    }

    public function test_unauthenticated_users_are_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_search_filter_works_correctly_on_dashboard()
    {
        $user = User::factory()->create();
        
        RecentTrack::factory()->create([
            'user_id' => $user->id,
            'name' => 'Música do Heitor',
            'artist' => 'Heitor Santos',
        ]);

        $response = $this->actingAs($user)->get('/dashboard?search=Heitor');

        $response->assertSee('Música do Heitor');
        $response->assertDontSee('Zé Vaqueiro');
    }
}