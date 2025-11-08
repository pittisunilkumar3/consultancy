<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormStructureItem extends Model
{
    protected $fillable = [
        'structure_id',
        'question_id',
        'parent_item_id',
        'parent_option_value',
        'order'
    ];

    /**
     * Get the structure this item belongs to
     */
    public function structure()
    {
        return $this->belongsTo(FormStructure::class);
    }

    /**
     * Get the question for this item
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the parent item if this is a child item
     */
    public function parentItem()
    {
        return $this->belongsTo(FormStructureItem::class, 'parent_item_id');
    }

    /**
     * Get child items for this item, ordered by their position
     */
    public function childItems()
    {
        return $this->hasMany(FormStructureItem::class, 'parent_item_id')
            ->orderBy('order');
    }

    /**
     * Convert item to nested array structure for frontend
     */
    public function toNestedArray()
    {
        $data = [
            'id' => $this->id,
            'question_id' => $this->question_id,
            'order' => $this->order,
            'question' => $this->question,
        ];

        // Only include children if this item has a radio type question
        if ($this->question && $this->question->type === 'radio') {
            // Get all direct children (only items where parent_item_id = this item's id)
            $directChildren = $this->childItems;
            
            // Group children by their parent_option_value
            // Filter out null values to avoid grouping issues
            $children = $directChildren
                ->filter(function ($item) {
                    return $item->parent_option_value !== null;
                })
                ->groupBy('parent_option_value')
                ->map(function ($items) {
                    return [
                        'items' => $items->map(function ($item) {
                            return $item->toNestedArray();
                        })
                    ];
                });
            
            if ($children->isNotEmpty()) {
                $data['children'] = $children;
            }
        }

        return $data;
    }
}
