<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'question', 'draft'];

    protected function casts(): array
    {
        return [
            'draft' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('draft', false);
    }

    public function scopeSearch(Builder $query, ?string $search)
    {
        return $query->when(
            $search,
            fn (Builder $q) => $q->whereLike('question', "%$search%")
        );
    }
}
