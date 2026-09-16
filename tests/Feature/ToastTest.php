<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToastTest extends TestCase
{
    use RefreshDatabase;

    public function test_toast_component_renders_all_supported_flash_types(): void
    {
        session([
            'success' => 'Player saved to shortlist.',
            'error' => 'You have already expressed interest.',
            'warning' => 'Your profile is incomplete.',
            'info' => 'A new feature is available.',
        ]);

        $html = (string) view('components.toast');

        $this->assertStringContainsString('atlas-toast-stack', $html);
        $this->assertStringContainsString('aria-label="Notifications"', $html);
        $this->assertStringContainsString('x-cloak', $html);
        $this->assertStringContainsString('Player saved to shortlist.', $html);
        $this->assertStringContainsString('You have already expressed interest.', $html);
        $this->assertStringContainsString('Your profile is incomplete.', $html);
        $this->assertStringContainsString('A new feature is available.', $html);
        $this->assertStringContainsString('\u0022success\u0022', $html);
        $this->assertStringContainsString('\u0022error\u0022', $html);
        $this->assertStringContainsString('\u0022warning\u0022', $html);
        $this->assertStringContainsString('\u0022info\u0022', $html);
        $this->assertStringContainsString('aria-label="Close notification"', $html);
    }

    public function test_toast_component_maps_legacy_status_key_to_success(): void
    {
        session(['status' => 'Football profile created successfully!']);

        $html = (string) view('components.toast');

        $this->assertStringContainsString('Football profile created successfully!', $html);
        $this->assertStringContainsString('\u0022success\u0022', $html);
    }

    public function test_toast_component_translates_known_status_keys_to_messages(): void
    {
        session(['status' => 'profile-updated']);

        $html = (string) view('components.toast');

        $this->assertStringContainsString('Profile updated successfully.', $html);
    }

    public function test_toast_component_renders_empty_stack_without_flashes(): void
    {
        $html = (string) view('components.toast');

        $this->assertStringContainsString('toastStack([], 4000)', $html);
        $this->assertStringNotContainsString('JSON.parse', $html);
    }

    public function test_dashboard_renders_session_flash_within_toast(): void
    {
        $user = User::factory()->player()->create();

        $this->actingAs($user)
            ->withSession(['status' => 'Welcome back to TalentX11.'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('atlas-toast-stack')
            ->assertSee('Welcome back to TalentX11.');
    }
}
