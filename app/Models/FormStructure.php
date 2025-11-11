<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormStructure extends Model
{
    protected $fillable = ['name', 'slug', 'meta', 'is_published'];
    protected $casts = [
        'meta' => 'array',
        'is_published' => 'boolean',
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
     * Returns elements in order (sections and standalone items mixed)
     */
    public function loadNestedStructure()
    {
        $result = [];
        
        // Load sections with their items, ordered by order field
        $sections = $this->sections()->orderBy('order')->get();
        
        // Load standalone items, ordered by order field
        $standaloneItems = $this->topLevelItems()
            ->with(['question'])
            ->orderBy('order')
            ->get();
        
        // Recursively load all nested children for standalone items
        $standaloneItems->each(function ($item) {
            $this->loadItemChildren($item);
        });
        
        // Create a map of sections by order
        $sectionsByOrder = [];
        foreach ($sections as $section) {
            $sectionsByOrder[$section->order] = $section;
        }
        
        // Create a map of standalone items by order
        $itemsByOrder = [];
        foreach ($standaloneItems as $item) {
            $itemsByOrder[$item->order] = $item;
        }
        
        // Get all order values and sort them
        $allOrders = array_unique(array_merge(array_keys($sectionsByOrder), array_keys($itemsByOrder)));
        sort($allOrders);
        
        // Build result in order
        foreach ($allOrders as $order) {
            // Check if there's a section at this order
            if (isset($sectionsByOrder[$order])) {
                $section = $sectionsByOrder[$order];
                
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
            
            // Check if there's a standalone item at this order
            if (isset($itemsByOrder[$order])) {
                $item = $itemsByOrder[$order];
                $result[] = [
                    'type' => 'item',
                    'order' => $item->order,
                    'item' => $item->toNestedArray()
                ];
            }
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