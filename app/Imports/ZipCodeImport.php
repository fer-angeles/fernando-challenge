<?php

namespace App\Imports;

use App\Models\FederalEntityModel;
use App\Models\MunicipalityModel;
use App\Models\SettlementsModel;
use App\Models\SettlementTypeModel;
use App\Models\ZipCodeModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ZipCodeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row)
        {
            if( !isset($row['c_estado']) )
                continue;

            $FederalEntity = FederalEntityModel::where('key',$row['c_estado'])
                ->where('name',$this->seo_friendly($row['d_estado']))
                ->first();

            if( !$FederalEntity )
            {
                $FederalEntity = FederalEntityModel::create([
                    'key' => $row['c_estado'],
                    'name' => $this->seo_friendly($row['d_estado']),
                    'code' => trim($row['c_cp'])
                ]);
            }
            else
            {
                $FederalEntity->name = $this->seo_friendly($row['d_estado']);
                $FederalEntity->code = $this->seo_friendly($row['c_cp']);
                $FederalEntity->save();
            }

            $Municipality = MunicipalityModel::where('federal_entity_id',$FederalEntity->key)
                ->where('name', $this->seo_friendly($row["d_mnpio"]))
                ->first();

            if( !$Municipality )
            {
                $Municipality = MunicipalityModel::create([
                    "federal_entity_id" => $FederalEntity->key,
                    "name" => $this->seo_friendly($row["d_mnpio"]),
                    "key" => $row["c_mnpio"]
                ]);
            }
            else
            {
                $Municipality->name = $this->seo_friendly($row['d_mnpio']);
                $Municipality->federal_entity_id = $FederalEntity->key;
                $Municipality->save();
            }

            $SettlementType = SettlementTypeModel::where('name',$this->seo_friendly($row["d_tipo_asenta"]))
                ->first();

            if( !$SettlementType )
            {
                $SettlementType = SettlementTypeModel::create([
                    "name" => $this->seo_friendly($row["d_tipo_asenta"]),
                    "key" => $row["c_tipo_asenta"]
                ]);
            }
            else
            {
                $SettlementType->name = $this->seo_friendly($row["d_tipo_asenta"]);
                $SettlementType->save();
            }

            $ZipCode = ZipCodeModel::where('zip_code',$row["d_codigo"])->first();

            if( !$ZipCode )
            {
                $ZipCodeId = ZipCodeModel::insertGetId([
                    "zip_code" => $row["d_codigo"],
                    "locality" => $this->seo_friendly($row["d_ciudad"]),
                    "municipality_id" => $Municipality->key,
                    "federal_entity_id" => $FederalEntity->key,
                ]);
            }
            else
            {
                $ZipCode->zip_code = $this->seo_friendly($row["d_codigo"]);
                $ZipCode->locality = $this->seo_friendly($row["d_ciudad"]);
                $ZipCode->municipality_id = $Municipality->key;
                $ZipCode->federal_entity_id = $FederalEntity->key;
                $ZipCode->save();
                $ZipCodeId = $ZipCode->key;
            }

            $Settlements = SettlementsModel::where('key',$row["id_asenta_cpcons"])
                ->where('name',$this->seo_friendly($row["d_asenta"]))
                ->first();

            if( !$Settlements )
            {
                $Settlements = SettlementsModel::create([
                    "key" => $row["id_asenta_cpcons"],
                    "name" => $this->seo_friendly($row["d_asenta"]),
                    "zone_type" => $this->seo_friendly($row["d_zona"]),
                    "settlement_type_id" => $SettlementType->key,
                    "zip_code_id" => $ZipCodeId,
                ]);
            }
            else
            {
                $Settlements->name = $this->seo_friendly($row["d_asenta"]);
                $Settlements->zone_type = $Municipality->key;
                $Settlements->settlement_type_id = $SettlementType->key;
                $Settlements->zip_code_id = $ZipCodeId;
                $Settlements->save();
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function seo_friendly($text)
    {
        $utf8 = [
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-',
            '/[’‘‹›‚]/u'    =>   ' ',
            '/[“”«»„]/u'    =>   ' ',
            '/ /'           =>   ' ',
        ];
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
}
