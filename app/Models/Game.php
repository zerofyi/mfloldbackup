<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'slot',
        'first',
        'second_winners',
        'third_winners',
        'fourth_winners',
        'fifth_winners',
        'status',
    ];
}
