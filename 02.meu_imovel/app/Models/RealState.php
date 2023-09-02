<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'content',
        'price',
        'bedrooms',
        'bathrooms',
        'property_area',
        'total_property_area',
        'slug'
    ];

    protected $appends = ['_links'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'real_state_categories');
    }

    public function photos()
    {
        return $this->hasMany(RealStatePhoto::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLinksAttribute()
    {
        return [
            'href' => route('real_states.real-states.show', ['real_state' => $this->id]),
            'rel' => 'Imóveis'
        ];
    }
}
