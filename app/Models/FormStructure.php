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
        // Load top level items with their questions
        $items = $this->topLevelItems()
            ->with(['question'])
            ->get();
        
        // Recursively load all nested children
        $items->each(function ($item) {
            $this->loadItemChildren($item);
        });
        
        return $items->map(function ($item) {
            return $item->toNestedArray();
        });
    }

    /**
     * Recursively load all nested children for an item
     */
    private function loadItemChildren($item)
    {
        // Load children with their questions
        $item->load(['childItems.question']);
        
        // Recursively load children of children
        $item->childItems->each(function ($child) {
            $this->loadItemChildren($child);
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