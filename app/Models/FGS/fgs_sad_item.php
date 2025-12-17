<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fgs_sad_item extends Model
{
    protected $table = 'fgs_sad_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_sad_item_rel')->insert(['master'=>$sad_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
}
