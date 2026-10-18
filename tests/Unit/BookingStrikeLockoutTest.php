<?php

use App\Services\BookingStrikeService;
use Carbon\Carbon;

it('keeps the booking lock until three full days after the third strike', function () {
    $service = new BookingStrikeService();
    $thirdStrikeAt = Carbon::parse('2026-10-01 10:00:00');

    expect($service->cooldownHasElapsed($thirdStrikeAt, $thirdStrikeAt->copy()->addDays(3)->subSecond()))
        ->toBeFalse()
        ->and($service->cooldownHasElapsed($thirdStrikeAt, $thirdStrikeAt->copy()->addDays(3)))
        ->toBeTrue();
});

it('does not auto-unlock when no third strike timestamp is available', function () {
    expect((new BookingStrikeService())->cooldownHasElapsed(null, Carbon::now()))
        ->toBeFalse();
});