<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class BookingStrikeService
{
    private const STRIKE_LIMIT = 3;
    private const COOLDOWN_DAYS = 3;

    public function synchronize(User $user): int
    {
        $strikeCount = $this->strikeQuery($user)->count();

        if ($user->is_locked_from_booking) {
            if ($this->unlockIfExpired($user)) {
                return $this->strikeQuery($user)->count();
            }

            return $strikeCount;
        }

        if ($strikeCount >= self::STRIKE_LIMIT) {
            $user->forceFill([
                'is_locked_from_booking' => true,
                'reactivation_status' => 'none',
            ])->save();
        }

        return $strikeCount;
    }

    public function unlockIfExpired(User $user): bool
    {
        if (! $user->is_locked_from_booking) {
            return false;
        }

        $thirdStrikeAt = $this->strikeQuery($user)
            ->orderBy('updated_at')
            ->orderBy('id')
            ->offset(self::STRIKE_LIMIT - 1)
            ->value('updated_at');

        if (! $this->cooldownHasElapsed($thirdStrikeAt ? Carbon::parse($thirdStrikeAt) : null)) {
            return false;
        }

        $user->forceFill([
            'is_locked_from_booking' => false,
            'reactivation_reason' => null,
            'reactivation_status' => 'approved',
            'last_reactivated_at' => now()->subSecond(),
        ])->save();

        return true;
    }

    public function cooldownHasElapsed(?CarbonInterface $thirdStrikeAt, ?CarbonInterface $now = null): bool
    {
        if (! $thirdStrikeAt) {
            return false;
        }

        return ($now ?? Carbon::now())->greaterThanOrEqualTo(
            Carbon::parse($thirdStrikeAt)->addDays(self::COOLDOWN_DAYS)
        );
    }

    private function strikeQuery(User $user): Builder
    {
        return Appointment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['cancelled', 'no-show'])
            ->when($user->last_reactivated_at, function (Builder $query, $lastReactivatedAt) {
                $query->where('updated_at', '>', $lastReactivatedAt);
            });
    }
}