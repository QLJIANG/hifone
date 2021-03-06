<?php

/*
 * This file is part of Hifone.
 *
 * (c) Hifone.com <hifone@hifone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hifone\Models;

use AltThree\Validator\ValidatingTrait;
use Hifone\Presenters\CreditPresenter;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class Credit extends Model implements HasPresenter
{
    use ValidatingTrait;

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = ['user_id', 'rule_id', 'balance', 'body', 'frequency_tag'];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'user_id'    => 'required|int',
        'rule_id'    => 'required|int',
    ];

    /**
     * Overrides the models boot method.
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($credit) {
            if (!$credit->frequency_tag) {
                $credit->frequency_tag = self::generateFrequencyTag();
            }
        });
    }

    public function rule()
    {
        return $this->belongsTo(CreditRule::class, 'rule_id');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Returns a frequency tag.
     *
     * @return string
     */
    public static function generateFrequencyTag()
    {
        return date('Ymd');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return CreditPresenter::class;
    }
}
