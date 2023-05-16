<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_srn_item extends Model
{
    protected $table = 'fgs_srn_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$srn_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_srn_item_rel')->insert(['master'=>$srn_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
}
