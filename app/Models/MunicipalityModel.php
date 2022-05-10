<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalityModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'key';
    protected $table = 'municipality';

    protected $fillable = [
        'name',
        'code',
        'federal_entity_id'
    ];
}
