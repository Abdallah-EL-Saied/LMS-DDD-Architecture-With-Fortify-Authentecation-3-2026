<?php

namespace App\Infrastructure\Observers;

use App\Infrastructure\Persistence\Eloquent\Models\Bundle;
use App\Infrastructure\Persistence\Eloquent\Models\BundleSubscription;
use App\Infrastructure\Persistence\Eloquent\Models\Setting;
use Illuminate\Support\Facades\DB;

class BundleSubscriptionObserver
{
    /**
     * Handle the BundleSubscription "created" event.
     * Recalculate best seller if auto mode is enabled.
     */
    public function created(BundleSubscription $subscription): void
    {
        $this->recalculateBestSellerIfAuto();
    }

    /**
     * Handle the BundleSubscription "updated" event (e.g., renewal).
     */
    public function updated(BundleSubscription $subscription): void
    {
        $this->recalculateBestSellerIfAuto();
    }

    /**
     * Recalculate best seller only if auto mode is enabled.
     */
    protected function recalculateBestSellerIfAuto(): void
    {
        $mode = Setting::getValue('best_seller_mode', 'manual');

        if ($mode !== 'auto') {
            return;
        }

        // Find bundle with the highest total subscriptions count
        $topBundleId = DB::table('bundle_subscriptions')
            ->select('bundle_id', DB::raw('COUNT(*) as total'))
            ->groupBy('bundle_id')
            ->orderByDesc('total')
            ->value('bundle_id');

        if (!$topBundleId) {
            return;
        }

        // Reset all, then set the top one
        Bundle::where('is_best_seller', true)->update(['is_best_seller' => false]);
        Bundle::where('id', $topBundleId)->update(['is_best_seller' => true]);
    }
}
