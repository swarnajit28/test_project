<?php

namespace App;
namespace App\model;
use Illuminate\Database\Eloquent\Model;
class Stored_document extends Model {

    public static function fetch_all_store_document($items_per_page, $user_type) {
        $result = Stored_document::select('stored_documents.*', 'ut.user_type')
                ->leftjoin('user_types as ut', 'ut.id', '=', 'stored_documents.user_type_id');
        if ($user_type != 'IT') {
            $result = $result-> where('ut.type_code', '=', $user_type);
        }
        $result = $result->orderBy('stored_documents.id', 'DESC')->limit($items_per_page)->get()->toArray();
        return $result;
    }

    public static function get_load_document_store($qty, $id,$user_type) {
         $result = Stored_document::select('stored_documents.*','ut.user_type')
                ->leftjoin('user_types as ut', 'ut.id', '=', 'stored_documents.user_type_id')
                -> where('stored_documents.id', '<', $id);
         if ($user_type != 'IT') {
            $result = $result-> where('ut.type_code', '=', $user_type);
        }
               $result = $result->orderBy('stored_documents.id', 'DESC')->limit($qty)->get()->toArray();
        return $result;
    }
    
  public static function searchQuerie($query,$user_type) {
        $all_data = Stored_document::select('stored_documents.*','ut.user_type')->leftjoin('user_types as ut', 'ut.id', '=', 'stored_documents.user_type_id')->orderBy('stored_documents.id', 'DESC');
        if (isset($query['fromdate']) && $query['fromdate'] != '') {
            $fromdate = str_replace('/', '-', $query['fromdate']);
            $fromdate = date('Y-m-d H:i:s', strtotime($fromdate));
            $all_data = $all_data->where('stored_documents.created_on', '>=', $fromdate);
        }
        if (isset($query['todate']) && $query['todate'] != '') {
            $todate = str_replace('/', '-', $query['todate']);
            $todate = date('Y-m-d H:i:s', strtotime($todate. "+1 days"));
            $all_data = $all_data->where('stored_documents.created_on', '<=', $todate);
        }
        if ($user_type != 'IT') {
            $all_data = $all_data-> where('ut.type_code', '=', $user_type);
        }
        $result = $all_data->get();
        return $result;
    }  
//    protected $fillable = [
//        'type_code', 'user_type', 'is_active'
//    ];
    public $timestamps = false;
    protected $guarded = ['id'];

}
