<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExcessiveLikesNotification;

class CheckExcessiveLikes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'likes:check-excessive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users who have liked more than 5 people and send email to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for users with excessive likes...');

        // Get users who have liked more than 5 people and haven't been notified yet
        $users = User::where('notified', false)
            ->where('like_count', '>', 5)
            ->get();

        if ($users->isEmpty()) {
            $this->info('No users with excessive likes found.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            // Send email to admin
            $adminEmail = env('MAIL_ADMIN_EMAIL', 'admin@example.com');
            Mail::to($adminEmail)->send(new ExcessiveLikesNotification($user));

            // Mark user as notified
            $user->update(['notified' => true]);

            $this->info("Notification sent for user {$user->name} (ID: {$user->id}) with {$user->user_likes_count} likes.");
        }

        $this->info('Excessive likes check completed.');
        return Command::SUCCESS;
    }
}
