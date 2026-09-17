<?php

namespace App\Notifications;

use App\Models\ScoutingInterest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScoutingInterestReceived extends Notification
{
    use Queueable;

    public function __construct(
        public ScoutingInterest $scoutingInterest
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $this->scoutingInterest->loadMissing(['scout.scoutProfile']);

        $scoutName = $this->scoutingInterest->scout->name;
        $organization = $this->scoutingInterest->scout->scoutProfile?->organization;

        $subject = $organization
            ? "New Scouting Interest from {$organization}"
            : 'New Scouting Interest';

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line('A scout has expressed interest in your football profile.')
            ->action('View Scouting Interest', route('scouting.interests.show', $this->scoutingInterest))
            ->line('Log in to TalentX11 to view the scouting interest.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->scoutingInterest->loadMissing(['scout.scoutProfile']);

        $scoutName = $this->scoutingInterest->scout->name;
        $organization = $this->scoutingInterest->scout->scoutProfile?->organization;

        return [
            'scouting_interest_id' => $this->scoutingInterest->id,
            'scout_id' => $this->scoutingInterest->scout_id,
            'scout_name' => $scoutName,
            'organization' => $organization,
            'title' => $organization
                ? "New Scouting Interest from {$organization} ({$scoutName})"
                : "New Scouting Interest from {$scoutName}",
            'message' => $this->scoutingInterest->message
                ?: 'A scout has expressed official interest in your football profile.',
            'url' => route('scouting.interests.show', $this->scoutingInterest),
        ];
    }
}
