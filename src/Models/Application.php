<?php

namespace Risky2k1\ApplicationManager\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'reviewer_id',
        'proponent_id',
        'name',
        'code',
        'state',
        'type',
        'reason',
        'is_paid_leave',
        'description',
        'money_amount',
        'bank_account',
        'delivery_time',
        'delivery_date',
        'attached_files',
        'company_id',
        'category_id',
    ];
    protected $casts = [
        'state' => ApplicationState::class,
        'attached_files' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ApplicationCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function proponent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proponent_id');
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
                    $start = Carbon::parse($dayOff->start_time);
                    $end = Carbon::parse($dayOff->end_time);

                    $startShift = $dayOff->start_shift;
                    $endShift = $dayOff->end_shift;

                    if ($start->isSameDay($end) && $startShift == $endShift) {
                        return $start->diffInDays($end) + 0.5;
                    } else {
                        return $start->diffInDays($end) + 1;
                    }
                });
            }
        );
    }

    public static function generateCode(int $companyId, string $type): string
    {
        if ($type == config('application-manager.application.default')) {
            $applicationType = 'ĐĐN-';
        } else {
            $applicationType = 'ĐXN-';
        }
        $prefix = $applicationType.str_pad($companyId, 2, '0', STR_PAD_LEFT);

        $countApplications = Application::withTrashed()->get()->count() + 1;

        $formattedNumber = str_pad($countApplications, 5, '0', STR_PAD_LEFT);

        $codesAvailable = Application::withTrashed()->pluck('code')->toArray();
        do {
            $code = $prefix.'-'.$formattedNumber;
            $formattedNumber++;
        } while (in_array($code, $codesAvailable));

        return $code;
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
