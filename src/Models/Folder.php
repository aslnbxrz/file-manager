<?php

namespace Aslnbxrz\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    protected $fillable = [
        "title",
        "description",
        "slug",
        "parent_id",
        "status"
    ];

    public static function rules(): array
    {
        return [
            'id' => 'integer',
            'title' => 'string',
            'slug' => 'string|nullable',
            'description' => 'string|nullable',
            'status' => 'integer|nullable',
            'parent_id' => 'integer|nullable',
        ];
    }

    protected $with = ['children', 'parent'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, "parent_id", "id");
    }

}
