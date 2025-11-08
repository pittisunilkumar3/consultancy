<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormStructure extends Model
{
    protected $fillable = ['name', 'slug', 'meta'];
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get all items in this structure
     */
    public function items()
    {
        return $this->hasMany(FormStructureItem::class, 'structure_id');
    }

    /**
     * Get top-level items (no parent) ordered by their position
     */
    public function topLevelItems()
    {
        return $this->items()
            ->whereNull('parent_item_id')
            ->orderBy('order');
    }

    /**
     * Load the complete nested structure including questions
     */
    public function loadNestedStructure()
    {
        return $this->topLevelItems()
            ->with(['question', 'childItems.question'])
            ->get()
            ->map(function ($item) {
                return $item->toNestedArray();
            });
    }

    /**
     * Create the Career Corner form structure if it doesn't exist
     */
    public static function createCareerCorner()
    {
        return static::firstOrCreate(
            ['slug' => 'career-corner'],
            [
                'name' => 'Career Corner',
                'meta' => ['description' => 'Student career assessment form']
            ]
        );
    }
}