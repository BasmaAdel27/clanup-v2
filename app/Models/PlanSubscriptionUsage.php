<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanSubscriptionUsage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plan_subscription_usage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id',
        'feature_id',
        'used',
        'valid_until',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'subscription_id' => 'integer',
        'feature_id' => 'integer',
        'used' => 'integer',
        'valid_until' => 'datetime',
    ];

    /**
     * Subscription usage always belongs to a plan feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(PlanFeature::class, 'feature_id', 'id', 'feature');
    }

    /**
     * Subscription usage always belongs to a plan subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(PlanSubscription::class, 'subscription_id', 'id', 'subscription');
    }

    /**
     * Scope subscription usage by feature name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $featureSlug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFeatureSlug(Builder $builder, string $featureSlug, int $plan_id): Builder
    {
        $feature = PlanFeature::where('plan_id', $plan_id)->where('slug', $featureSlug)->first();

        return $builder->where('feature_id', $feature->getKey() ?? null);
    }

    /**
     * Check whether usage has been expired or not.
     *
     * @return bool
     */
    public function expired(): bool
    {
        if (is_null($this->valid_until)) {
            return false;
        }

        return Carbon::now()->gte($this->valid_until);
    }
}