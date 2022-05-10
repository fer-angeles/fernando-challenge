<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCodeModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'zip_code';
    protected $table = 'zip_codes';
    protected $timestamp = false;

    protected $fillable = [
        'zip_code',
        'locality',
        'municipality_id',
        'federal_entity_id',
    ];


    public function settlements()
    {
        return $this->hasMany(SettlementsModel::class,'zip_code_id','key');
    }

    public function federal_entity()
    {
        return $this->hasOne(FederalEntityModel::class,'key', 'federal_entity_id');
    }

    public function municipality()
    {
        return $this->hasOne(MunicipalityModel::class,'key','municipality_id');
    }
}
