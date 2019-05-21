<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use helper;
class customer_attachment extends Model
{
    protected $guarded = ['id'];
    protected $table = 'customer_attachments';
    public static function insertattachments($data)
    {  
	    for($i=0;$i<count($data)-1;$i++) 
		{
			$attachment  = new customer_attachment;
		    $attachment->custom_id  = $data['custom_id'];
		    $attachment->customer_attachment_name = $data[$i]['customer_attachment_name'];
		    $attachment->customer_attachment_file_name = $data[$i]['customer_attachment_file_name'];
		    $attachment->timestamps = false;
		    $attachment->save();
		}
	}

    public static function deleteattachments($id)
    {        
        customer_attachment::where('custom_id', '=', $id)->delete();
    }

    public static function attach_details($id)
    {        
        $attach_details = customer_attachment::select('*')
                          ->where('custom_id', $id)
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
