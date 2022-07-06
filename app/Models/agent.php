<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class agent extends Model
{
    
    protected $table = 'agent';

    protected $primary_key = 'subr_id';

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('agent.status', function (Builder $builder) {
            $builder->where('agent.status', '!=', 2);
        });
    }

    public function add_subscriber($data)
    {
        return $this->insertGetId($data);
    }
    public function get_subscriber($condition)
    {
        return $this->select(['agent.subscriber_id','agent.f_name','agent.l_name','agent.email','agent.phone'
                            ,'agent.pincode','agent.status','agent.subr_id','org_type_rel.type_id'])
        ->join('agent_org_rel','agent_org_rel.subscriber_id','=','agent.subr_id')
        ->leftjoin('organizational_structure','organizational_structure.org_id','=','agent_org_rel.org_id')
        ->leftjoin('org_type_rel','org_type_rel.org_id','=','agent_org_rel.org_id')
       // ->leftjoin('agent_subscription_book_rel','agent_subscription_book_rel.subscription_id','=','agent.subr_id')
        ->where($condition) 
        ->where('agent.subr_id','!=','') 
        ->groupBy('agent.subr_id')
        ->orderBy('agent.subr_id', 'DESC')
        ->paginate(11);
    }
    public function get_subscriber_excel($condition)
    {
        return $this->select(['agent.subscriber_id as Agent ID','agent.f_name as First name','agent.l_name as Last name','agent.email  as Organization Email','agent.phone as Phone','agent.house_name as Organization name (school / shop etc.)','agent.place as Place','agent.post_office as Post Office','agent.pincode as Pincode','agent.billing_address as Billing address','agent.shipping_address as Shipping address'])
        ->join('agent_org_rel','agent_org_rel.subscriber_id','=','agent.subr_id')
        ->leftjoin('organizational_structure','organizational_structure.org_id','=','agent_org_rel.org_id')
        ->leftjoin('org_type_rel','org_type_rel.org_id','=','agent_org_rel.org_id')
       // ->leftjoin('agent_subscription_book_rel','agent_subscription_book_rel.subscription_id','=','agent.subr_id')
        ->where($condition) 
        ->where('agent.subr_id','!=','') 
        ->groupBy('agent.subr_id')
        ->orderBy('agent.subr_id', 'DESC')
        ->get();
    }




    function delete_subscriber($condition,$update){
        return $this->join('subscriber_org_rel','subscriber_org_rel.subscriber_id','=','subscriber.subr_id')
        ->where($condition)
        ->update($update);
    }
    public function update_subscriber($condition,$data)
    {
          return $this->where($condition)->update($data);
    }
    public function get_single_subscriber($condition)
    {
        
        return $this->select(['*','agent.subscriber_id as subr_id'])
        ->join('agent_org_rel','agent_org_rel.subscriber_id','=','agent.subr_id')
        ->join('organizational_structure','organizational_structure.org_id','=','agent_org_rel.org_id')
        ->where($condition)->first();
    }
    public function get_subscriber_data($condition)
    {
        return $this->select(['*'])->where($condition)->first();
    }

}
