<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\SalesCommon;

class Sales extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receiving',
        'name',
        'nameKana',
        'number',
        'content',
        'charge',
        'is_new',
    ];

    public function scopeSortOrder($query, $sortOrder)
    {
        if($sortOrder === null || $sortOrder === SalesCommon::SORT_ORDER['receiving']) {
            return $query->orderBy('receiving', 'asc');
        }
        if($sortOrder === SalesCommon::SORT_ORDER['name']) {
            return $query->orderBy('name', 'asc');
        }
        if($sortOrder === SalesCommon::SORT_ORDER['charge']) {
            return $query->orderBy('charge', 'asc');
        }
    }
    
}
