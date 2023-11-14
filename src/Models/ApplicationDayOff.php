<?php

namespace Risky2k1\ApplicationManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDayOff extends Model
{
    use HasFactory;

    protected $table = 'application_dayoffs';

    protected $fillable = [
        'end_shift',
        'end_time',
        'start_shift',
        'start_time',
        'application_id',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
