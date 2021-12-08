<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Benefit extends Model
{
    use HasFactory;
    protected $casts = [
        'actions' => 'collection',
        'images' => 'collection',
    ];
    public function __construct(array $attributes = [])
    {
        $this->fillable = Schema::getColumnListing($this->getTable());
        parent::__construct($attributes);
    }
}
