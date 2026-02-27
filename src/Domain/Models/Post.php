<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lines\Auth\Domain\Models\User;

/**
 * @mixin IdeHelperPost
 */
#[UseFactory(PostFactory::class)]
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'body',
        'published_at',
    ];

    protected $casts = [
        'author_id' => 'int',
        'published_at' => 'datetime'
    ];

    /**
     * Get the author of the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
