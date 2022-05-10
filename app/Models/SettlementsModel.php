<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementsModel extends Model
{
    use HasFactory;

    protected $table = 'settlements';
    protected $primaryKey = 'key';
    protected $fillable = [
        "name",
        "code",
        "zone_type",
        "settlement_type_id",
        "zip_code_id",
    ];

    public function settlement_type()
    {
        return $this->hasOne(SettlementTypeModel::class,'key','settlement_type_id');
    }

}
