<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Quality extends Model
{
    use HasFactory;

    protected $table = 'add_quality';

    protected $fillable = [
        'inward_doc_date',
        'batch_no',
        'sku_name',
        'description',
        'batchcard_inward_qty',
        'material_lot_no',
        'start_date',
        'start_time',
        'end_date',
        'inspected_qty',
        'end_time',
        'inspector_name',
        'accepted_quantity',
        'rejected_qty',
        'rework_quantity',
        'accepted_quantity_with_deviation',
        'product_group',
        'pending_status',
        'remark',
        'rejected_reason',
        'status',
        'reason_for_deviation',
        'remaining_quantity',
        'remaining_reason',
        'rework_reason',
        'batch_creation_date'
    ];

    public function insertdata($data)
    {
        return self::create($data);
    }

    public function get_all_quality_list($condition)
    {
        return self::where('add_quality.status', '=', 1)
            ->select('add_quality.*')
            ->when($condition, function ($query) use ($condition) {
                foreach ($condition as $fieldCondition) {
                    $query->where($fieldCondition[0], $fieldCondition[1], $fieldCondition[2]);
                }
            })
            ->orderBy('add_quality.id', 'desc')
            ->groupBy('batch_no')
            ->paginate(15);
    }
    
}