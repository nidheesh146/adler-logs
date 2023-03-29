<?php

namespace App\Models\FGS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class fgs_grs_item extends Model
{
    protected $table = 'fgs_grs_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data,$grs_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('fgs_grs_item_rel')->insert(['master'=>$grs_id,'item'=>$item_id]);
        }
        return true;
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItems($condition)
    {
        return $this->select('fgs_grs_item.*')
                        ->leftjoin('fgs_grs_item_rel','fgs_grs_item_rel.item','=', 'fgs_grs_item.id')
                        ->leftjoin('fgs_grs','fgs_grs.id','=','fgs_grs_item_rel.master')
                        ->where($condition)
                        ->where('fgs_grs.status','=',1)
                        ->orderBy('fgs_grs_item.id','DESC')
                        ->distinct('fgs_grs_item.id')
                        ->paginate(15);
    }
}
