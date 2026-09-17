<?php

namespace Tests\Feature;

use App\Models\PlayerProfile;
use App\Models\ScoutingInterest;
use App\Models\ScoutProfile;
use App\Models\User;
use App\Notifications\ScoutingInterestReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_notifications_page(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect('/login');
    }

    public function test_guests_cannot_mark_notifications_as_read(): void
    {
        $response = $this->patch(route('notifications.read', 'non-existent-id'));

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_notifications_page(): void
    {
        $user = User::factory()->player()->create();

        $response = $this->actingAs($user)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertSee('No Notifications');
        $response->assertSee('all caught up');
    }

    public function test_scout_expressing_interest_dispatches_notification_to_player(): void
    {
        Notification::fake();

        $scout = User::factory()->scout()->create();
        ScoutProfile::factory()->create(['user_id' => $scout->id, 'organization' => 'FUS Rabat Academy']);

        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $response = $this->actingAs($scout)->post(route('scouting.interests.store', $playerProfile), [
            'message' => 'We invite you to participate in trial sessions next Monday.',
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo(
            $player,
            ScoutingInterestReceived::class,
            function (ScoutingInterestReceived $notification) use ($playerProfile, $scout) {
                return $notification->scoutingInterest->player_profile_id === $playerProfile->id
                    && $notification->scoutingInterest->scout_id === $scout->id;
            }
        );
    }

    public function test_scout_expressing_interest_persists_database_notification_for_player(): void
    {
        $scout = User::factory()->scout()->create(['name' => 'Tarik Sektioui']);
        ScoutProfile::factory()->create(['user_id' => $scout->id, 'organization' => 'MAS Fez']);

        $player = User::factory()->player()->create(['name' => 'Bilal El Khannouss']);
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $this->actingAs($scout)->post(route('scouting.interests.store', $playerProfile), [
            'message' => 'Impressive midfield performance at the youth tournament.',
        ]);

        $this->assertDatabaseCount('notifications', 1);

        $notification = $player->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals(ScoutingInterestReceived::class, $notification->type);
        $this->assertEquals('Tarik Sektioui', $notification->data['scout_name']);
        $this->assertEquals('MAS Fez', $notification->data['organization']);
        $this->assertStringContainsString('MAS Fez (Tarik Sektioui)', $notification->data['title']);
        $this->assertEquals('Impressive midfield performance at the youth tournament.', $notification->data['message']);
        $this->assertNull($notification->read_at);
        $this->assertEquals(1, $player->unreadNotifications()->count());
    }

    public function test_player_can_see_notification_details_on_index_page(): void
    {
        $scout = User::factory()->scout()->create(['name' => 'Houcine Ammouta']);
        ScoutProfile::factory()->create(['user_id' => $scout->id, 'organization' => 'Wydad AC']);

        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $playerProfile->id,
            'message' => 'Calling you for a trial.',
        ]);

        $player->notify(new ScoutingInterestReceived($interest));

        $response = $this->actingAs($player)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('Wydad AC');
        $response->assertSee('Houcine Ammouta');
        $response->assertSee('Calling you for a trial.');
        $response->assertSee('New');
    }

    public function test_player_sees_view_link_pointing_to_interest_from_notification(): void
    {
        $scout = User::factory()->scout()->create(['name' => 'Houcine Ammouta']);
        ScoutProfile::factory()->create(['user_id' => $scout->id, 'organization' => 'Wydad AC']);

        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $playerProfile->id,
            'message' => 'Calling you for a trial.',
        ]);

        $player->notify(new ScoutingInterestReceived($interest));

        $response = $this->actingAs($player)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('View');
        $response->assertSee(route('scouting.interests.show', $interest));

        $destination = $this->actingAs($player)->get(route('scouting.interests.show', $interest));

        $destination->assertStatus(200);
        $this->assertEquals(ScoutingInterest::STATUS_VIEWED, $interest->fresh()->status);
    }

    public function test_user_can_mark_single_notification_as_read(): void
    {
        $scout = User::factory()->scout()->create();
        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $playerProfile->id,
        ]);

        $player->notify(new ScoutingInterestReceived($interest));
        $notification = $player->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($player)->patch(route('notifications.read', $notification->id));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Notification marked as read.');

        $this->assertEquals(0, $player->fresh()->unreadNotifications()->count());
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_cannot_mark_another_users_notification_as_read(): void
    {
        $playerA = User::factory()->player()->create();
        $playerB = User::factory()->player()->create();
        $scout = User::factory()->scout()->create();
        $profileB = PlayerProfile::factory()->create(['user_id' => $playerB->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $profileB->id,
        ]);

        $playerB->notify(new ScoutingInterestReceived($interest));
        $notificationB = $playerB->unreadNotifications()->first();

        // Player A tries to mark Player B's notification as read
        $response = $this->actingAs($playerA)->patch(route('notifications.read', $notificationB->id));

        $response->assertStatus(404);
        $this->assertNull($notificationB->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $scout1 = User::factory()->scout()->create();
        $scout2 = User::factory()->scout()->create();
        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $interest1 = ScoutingInterest::factory()->create([
            'scout_id' => $scout1->id,
            'player_profile_id' => $playerProfile->id,
        ]);

        $interest2 = ScoutingInterest::factory()->create([
            'scout_id' => $scout2->id,
            'player_profile_id' => $playerProfile->id,
        ]);

        $player->notify(new ScoutingInterestReceived($interest1));
        $player->notify(new ScoutingInterestReceived($interest2));

        $this->assertEquals(2, $player->unreadNotifications()->count());

        $response = $this->actingAs($player)->post(route('notifications.markAllAsRead'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'All notifications marked as read.');

        $this->assertEquals(0, $player->fresh()->unreadNotifications()->count());
    }

    public function test_user_can_delete_notification(): void
    {
        $scout = User::factory()->scout()->create();
        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $playerProfile->id,
        ]);

        $player->notify(new ScoutingInterestReceived($interest));
        $notification = $player->notifications()->first();

        $response = $this->actingAs($player)->delete(route('notifications.destroy', $notification->id));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Notification removed.');

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_notification(): void
    {
        $playerA = User::factory()->player()->create();
        $playerB = User::factory()->player()->create();
        $scout = User::factory()->scout()->create();
        $profileB = PlayerProfile::factory()->create(['user_id' => $playerB->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $profileB->id,
        ]);

        $playerB->notify(new ScoutingInterestReceived($interest));
        $notificationB = $playerB->notifications()->first();

        $response = $this->actingAs($playerA)->delete(route('notifications.destroy', $notificationB->id));

        $response->assertStatus(404);
        $this->assertDatabaseHas('notifications', [
            'id' => $notificationB->id,
        ]);
    }

    public function test_navigation_displays_unread_notification_count(): void
    {
        $scout = User::factory()->scout()->create();
        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $interest = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $playerProfile->id,
        ]);

        $player->notify(new ScoutingInterestReceived($interest));

        $response = $this->actingAs($player)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Notifications');
        $response->assertSee('1');
    }

    public function test_guests_cannot_mark_all_read_or_delete_notifications(): void
    {
        $this->post(route('notifications.markAllAsRead'))->assertRedirect('/login');
        $this->delete(route('notifications.destroy', 'dummy-id'))->assertRedirect('/login');
    }

    public function test_marking_all_as_read_does_not_affect_other_users(): void
    {
        $player1 = User::factory()->player()->create();
        $player2 = User::factory()->player()->create();
        $scout = User::factory()->scout()->create();
        $profile1 = PlayerProfile::factory()->create(['user_id' => $player1->id]);
        $profile2 = PlayerProfile::factory()->create(['user_id' => $player2->id]);

        $interest1 = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $profile1->id,
        ]);
        $interest2 = ScoutingInterest::factory()->create([
            'scout_id' => $scout->id,
            'player_profile_id' => $profile2->id,
        ]);

        $player1->notify(new ScoutingInterestReceived($interest1));
        $player2->notify(new ScoutingInterestReceived($interest2));

        $this->assertEquals(1, $player1->unreadNotifications()->count());
        $this->assertEquals(1, $player2->unreadNotifications()->count());

        // Player 1 marks all as read
        $this->actingAs($player1)->post(route('notifications.markAllAsRead'));

        // Player 1 has 0 unread, Player 2 still has 1 unread
        $this->assertEquals(0, $player1->fresh()->unreadNotifications()->count());
        $this->assertEquals(1, $player2->fresh()->unreadNotifications()->count());
    }

    public function test_scout_expressing_interest_includes_mail_channel_in_notification(): void
    {
        Notification::fake();

        $scout = User::factory()->scout()->create();
        ScoutProfile::factory()->create(['user_id' => $scout->id, 'organization' => 'FUS Rabat Academy']);

        $player = User::factory()->player()->create();
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $this->actingAs($scout)->post(route('scouting.interests.store', $playerProfile), [
            'message' => 'Trial invitation for you.',
        ]);

        Notification::assertSentTo(
            $player,
            ScoutingInterestReceived::class,
            function (ScoutingInterestReceived $notification) use ($player, $playerProfile, $scout) {
                return $notification->scoutingInterest->player_profile_id === $playerProfile->id
                    && $notification->scoutingInterest->scout_id === $scout->id
                    && in_array('database', $notification->via($player), true)
                    && in_array('mail', $notification->via($player), true);
            }
        );
    }

    public function test_scout_expressing_interest_sends_email_to_player_address(): void
    {
        Notification::fake();

        $scout = User::factory()->scout()->create();
        ScoutProfile::factory()->create(['user_id' => $scout->id, 'organization' => 'Sevilla FC Academy']);

        $player = User::factory()->player()->create(['email' => 'player@example.com']);
        $playerProfile = PlayerProfile::factory()->create(['user_id' => $player->id]);

        $this->actingAs($scout)->post(route('scouting.interests.store', $playerProfile), [
            'message' => 'We would like to invite you for a trial.',
        ]);

        Notification::assertSentTo(
            $player,
            ScoutingInterestReceived::class,
            function (ScoutingInterestReceived $notification) use ($player) {
                return $notification->via($player) === ['database', 'mail'];
            }
        );
    }
}
