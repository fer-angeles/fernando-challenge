<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementTypeModel extends Model
{
    use HasFactory;

    protected $table = 'settlement_type';
    protected $primaryKey = 'key';

    protected $fillable = [
        'name'
    ];
}
