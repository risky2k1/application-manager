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

class ApplicationCategory extends Model
{
    protected $table = 'application_categories';

    protected $fillable = [
        'key',
        'name',
        'parent_id',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ApplicationCategory::class, 'parent_id');
    }
}
