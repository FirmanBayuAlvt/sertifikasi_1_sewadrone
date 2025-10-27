<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;

class RentalService
{
    const MAX_RENTAL_DAYS = 5;
    const MAX_UNITS_PER_USER = 2;
    const FINE_PER_DAY = 1000; // Example fine amount per day

    public function calculateFine($rental)
    {
        $dueDate = Carbon::parse($rental->due_date);
        $returnDate = Carbon::now();

        if ($returnDate->greaterThan($dueDate)) {
            $daysOverdue = $returnDate->diffInDays($dueDate);
            return $daysOverdue * self::FINE_PER_DAY;
        }

        return 0;
    }

    public function canUserRent($user)
    {
        $currentRentals = Rental::where('user_id', $user->id)
            ->where('returned', false)
            ->count();

        return $currentRentals < self::MAX_UNITS_PER_USER;
    }

    public function isRentalPeriodValid($rentalDays)
    {
        return $rentalDays <= self::MAX_RENTAL_DAYS;
    }
}