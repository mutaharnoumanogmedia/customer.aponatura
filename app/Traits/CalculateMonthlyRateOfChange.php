<?php
namespace App\Traits;

use Carbon\Carbon;

trait CalculateMonthlyRateOfChange
{
    /**
     * Calculate the current count for a model and the % change
     * compared to the end of last month.
     *
     * @param  string       $modelClass   FQCN of your Eloquent model
     * @param  string       $dateColumn   Column to compare dates against
     * @param  Carbon|null  $reference    Reference “now” date (defaults to now())
     * @return array{current:int, previous:int, percent_change:float}
     */
    protected function CalculateMonthlyRateOfChange(
        string $modelClass,
        string $dateColumn = 'created_at',
        Carbon $reference = null
    ): array {
        $reference      = $reference ?? Carbon::now();
        $currentCount   = $modelClass::count();

        // end of last month relative to $reference
        $endOfLastMonth = (clone $reference)
            ->subMonthNoOverflow()
            ->endOfMonth();

        $previousCount  = $modelClass
            ::where($dateColumn, '<=', $endOfLastMonth)
            ->count();

        if ($previousCount > 0) {
            $percent = round(
                (($currentCount - $previousCount) / $previousCount) * 100,
                1
            );
        } else {
            // if there were none last month and now there are some → 100%
            $percent = $currentCount > 0 ? 100.0 : 0.0;
        }

        return [
            'current'        => $currentCount,
            'previous'       => $previousCount,
            'percent_change' => $percent,
        ];
    }
}
