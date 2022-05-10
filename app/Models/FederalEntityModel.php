<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FederalEntityModel extends Model
{
    use HasFactory;

    protected $table = 'federal_entity';
    protected $primaryKey = 'key';

    protected $fillable = [
        'key',
        'name',
        'code'
    ];
}
