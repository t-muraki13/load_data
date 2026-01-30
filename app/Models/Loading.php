<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\Common;

class Loading extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_completed',
        'receiving',
        'name',
        'nameKana',
        'number',
        'content',
        'charge',
        'issue',
        'remarks',
        'place',
        'is_new',
    ];

    public function scopeSortOrder($query, $sortOrder)
    {
        if($sortOrder === null || $sortOrder === Common::SORT_ORDER['receiving']) {
            return $query->orderBy('receiving', 'asc');
        }
        if($sortOrder === Common::SORT_ORDER['name']) {
            return $query->orderBy('name', 'asc');
        }
        if($sortOrder === Common::SORT_ORDER['charge']) {
            return $query->orderBy('charge', 'asc');
        }
        if($sortOrder === Common::SORT_ORDER['issue']) {
            return $query->orderBy('issue', 'asc');
        }
        if($sortOrder === Common::SORT_ORDER['place']) {
            return $query->orderBy('place', 'asc');
        }
    }
}
