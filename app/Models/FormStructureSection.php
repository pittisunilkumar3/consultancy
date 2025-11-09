<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormStructureSection extends Model
{
    protected $fillable = [
        'structure_id',
        'name',
        'description',
        'order',
        'is_collapsible',
        'is_expanded_by_default',
    ];

    protected $casts = [
        'is_collapsible' => 'boolean',
        'is_expanded_by_default' => 'boolean',
    ];

    /**
     * Get the structure this section belongs to
     */
    public function structure()
    {
        return $this->belongsTo(FormStructure::class);
    }

    /**
     * Get all items in this section (top-level items only)
     */
    public function items()
    {
        return $this->hasMany(FormStructureItem::class, 'section_id')
            ->whereNull('parent_item_id')
            ->orderBy('order');
    }

    /**
     * Get all items in this section (including nested)
     */
    public function allItems()
    {
        return $this->hasMany(FormStructureItem::class, 'section_id')
            ->orderBy('order');
    }
}
