<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\model\Lead;
use App\model\Lead_activity;
use DB;
use helper;
class Lead_supporting_document extends Model
{
    protected $guarded = ['id'];

    public static function upload_documents($data) {
        //print_r($data);exit;
        if (count($data) > 1) {
            for ($i = 0; $i < count($data) - 1; $i++) {
                $attachment = new Lead_supporting_document;
                $attachment->lead_id = $data['lead_id'];
                $attachment->supporting_doc_scan_file_path = $data[$i]['supportdoc'];
                $attachment->uploaded_by_user_id = Auth::id();
                $attachment->timestamps = false;
                $attachment->save();
            }
        }
    }

    public static function completeLead($data) {
        $lead_detail = Lead::find($data['lead_id']);
        $lead_detail->is_completed = '1';
        $lead_detail->lead_completed_date = date('Y-m-d H:i:s');
        $lead_detail->updated_at = date('Y-m-d H:i:s');
        $lead_detail->updated_by = Auth::id();
        $lead_detail->update();

        Lead_activity::insertactivity($data['lead_id'], 'completed', '1', '0');
    }

    public static function supportdocs($id)
    {        
        $attach_details = Lead_supporting_document::select('*')
                          ->where('lead_id', $id)
                          ->get();
        
        if ($attach_details->count()) {
            $attach_details = $attach_details->toArray();
        }
        else
        {
            $attach_details = array();
        }
        return $attach_details;
    }
}
