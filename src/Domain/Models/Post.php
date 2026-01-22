<?php

declare(strict_types=1);

namespace Blog\Domain\Models;

use Auth\Domain\Models\User;
use Blog\Infrastructure\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'body',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'author_id' => 'int',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return PostFactory::new();
    }

    /**
     * Get the author of the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
