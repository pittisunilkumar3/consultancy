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
     * Get all sections in this structure
     */
    public function sections()
    {
        return $this->hasMany(FormStructureSection::class, 'structure_id')
            ->orderBy('order');
    }

    /**
     * Get top-level items (no parent, no section) ordered by their position
     */
    public function topLevelItems()
    {
        return $this->items()
            ->whereNull('parent_item_id')
            ->whereNull('section_id')
            ->orderBy('order');
    }

    /**
     * Get top-level items in a specific section
     */
    public function topLevelItemsInSection($sectionId)
    {
        return $this->items()
            ->whereNull('parent_item_id')
            ->where('section_id', $sectionId)
            ->orderBy('order');
    }

    /**
     * Load the complete nested structure including questions and sections
     */
    public function loadNestedStructure()
    {
        $result = [];
        
        // Load sections with their items
        $sections = $this->sections()->get();
        
        foreach ($sections as $section) {
            // Load top-level items in this section
            $items = $this->topLevelItemsInSection($section->id)
                ->with(['question'])
                ->get();
            
            // Recursively load all nested children
            $items->each(function ($item) {
                $this->loadItemChildren($item);
            });
            
            $result[] = [
                'type' => 'section',
                'id' => $section->id,
                'name' => $section->name,
                'description' => $section->description,
                'order' => $section->order,
                'is_collapsible' => $section->is_collapsible,
                'is_expanded_by_default' => $section->is_expanded_by_default,
                'items' => $items->map(function ($item) {
                    return $item->toNestedArray();
                })
            ];
        }
        
        // Load standalone items (not in any section)
        $standaloneItems = $this->topLevelItems()
            ->with(['question'])
            ->get();
        
        // Recursively load all nested children
        $standaloneItems->each(function ($item) {
            $this->loadItemChildren($item);
        });
        
        if ($standaloneItems->isNotEmpty()) {
            $result[] = [
                'type' => 'items',
                'items' => $standaloneItems->map(function ($item) {
                    return $item->toNestedArray();
                })
            ];
        }
        
        return $result;
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