<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BookingStrikeService;
use Illuminate\Console\Command;

class UnlockExpiredBookingAccounts extends Command
{
    protected $signature = 'bookings:unlock-expired-accounts';

    protected $description = 'Automatically unlock patient booking access three days after the third strike';

    public function handle(BookingStrikeService $strikeService): int
    {
        $unlockedCount = 0;

        User::where('is_locked_from_booking', true)
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($strikeService, &$unlockedCount) {
                foreach ($users as $user) {
                    if ($strikeService->unlockIfExpired($user)) {
                        $unlockedCount++;
                    }
                }
            });

        $this->info("Automatically unlocked {$unlockedCount} expired booking lock(s).");

        return self::SUCCESS;
    }
}