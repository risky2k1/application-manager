<?php

namespace Risky2k1\ApplicationManager\Models;

use App\Models\User;
use Risky2k1\ApplicationManager\Models\States\Application\ApplicationState;
use Risky2k1\ApplicationManager\Models\States\Application\Pending;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewer_id',
        'name',
        'code',
        'state',
        'type',
        'reason',
        'start_time',
        'start_shift',
        'end_time',
        'end_shift',
        'is_paid_leave',
        'description',
        'money_amount',
        'bank_account',
        'delivery_time',
        'delivery_date',
        'attached_files',
        'company_id'
    ];
    protected $casts = [
        'state' => ApplicationState::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function considers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'application_consider', 'application_id', 'user_id');
    }

    public function dayOffs(): HasMany
    {
        return $this->hasMany(ApplicationDayOff::class, 'application_id');
    }

    protected function numberOfDayOff(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->dayOffs->sum(function ($dayOff) {
                    $start = new \DateTime($dayOff->start_time);
                    $end = new \DateTime($dayOff->end_time);

                    return $start->diff($end)->days + 1;
                });
            }
        );
    }

    public static function generateCode($companyId): string
    {
        //        $words = explode(" ", $company->name);
        //        $acronym = "";
        //        foreach ($words as $word) {
        //            $acronym .= mb_substr($word, 0, 1);
        //        }
        //        $codesAvailable = Application::pluck('code')->toArray();
        //        do {
        //            $order_code = $acronym.'-'.$vietnameseCompanyOrders ++ .'-'.Carbon::now()->year;
        //        } while (in_array($order_code, $codesAvailable));
        //
        //        return Str::upper($order_code);
        $all = Application::all()->count();
        return $all + 1;
    }

    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->state == Pending::$name;
            }
        );
    }
}
