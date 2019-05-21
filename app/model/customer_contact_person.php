<?php
namespace App;
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\model\Lead;
use DB;
use helper;
class customer_contact_person extends Model
{  
    protected $guarded = ['id'];   
    protected $table = 'customer_contact_persons';
    public $timestamps = false;
    public static function insertcontacts($data)
    {       
      customer_contact_person::where('custom_id', '=', $data['custom_id'])->delete();
	    for($i=0;$i<count($data)-1;$i++) 
  		{
  			$contacts  = new customer_contact_person;
		    $contacts->custom_id  = $data['custom_id'];
        $contacts->contact_person_name = $data[$i]['contact_person_name'];
        $contacts->contact_person_phone1   = $data[$i]['contact_person_phone1'];
      	$contacts->contact_person_phone2   = $data[$i]['contact_person_phone2'];
      	$contacts->contact_person_email1 = $data[$i]['contact_person_email1'];
      	$contacts->contact_person_email2 = $data[$i]['contact_person_email2'];
      	$contacts->contact_person_note = $data[$i]['contact_person_note'];
      	$contacts->contact_person_job_title = $data[$i]['contact_person_job_title'];
      	$contacts->contact_person_job_role = $data[$i]['contact_person_job_role'];
        $contacts->timestamps = false;
        $contacts->save();
        $insertedId = $contacts->id;
        if($i==0)
        {
          Lead::where('custom_id', $data['custom_id'])
            ->update(['customer_contact_person_id' =>$insertedId]);
        }
		  }
	 } 

  public static function deletecontact($id)
  {  
      Lead::where('custom_id', $id)
          ->update(['customer_contact_person_id' =>'']);
      customer_contact_person::where('custom_id', '=', $id)->delete();
  }

   public static function checkcustomer($email_address)
   {
        $arr  = customer_contact_person::select('id')
                ->where('contact_person_email1', $email_address)
                ->get()->toArray();
        $arr2  = customer_contact_person::select('id')
              ->where('contact_person_email2', $email_address)
              ->get()->toArray();
        if (is_array($arr) && count($arr) > 0 || is_array($arr2) && count($arr2) > 0) {
            return "Y";
        } else {
            return "N";
        }
   }

   public static function contact_details($id)
   {
      $contact_details = customer_contact_person::select('*')
                        ->where('custom_id', $id)
                        ->get()->toArray();
      
      //helper::pre($contact_details);
      return $contact_details;
   }

  public static function allcontact_person($id) {
      $result = customer_contact_person::select('id','contact_person_name')->where('custom_id', $id)->orderBy('contact_person_name', 'ASC')->get()->toArray();
      return $result;
  }

   public static function persondetails($id)
   {
      $details = customer_contact_person::select('custom_id','contact_person_name','contact_person_phone1','contact_person_email1')
                        ->where('id', $id)
                        ->get();
      
      if ($details->count()) {
          $details = $details->first()->toArray();
          //helper::pre($details);exit;
      }
      else
      {
        $details['custom_id'] = '';
        $details['contact_person_name'] = '';
        $details['contact_person_phone1'] = '';
        $details['contact_person_email1'] = '';
      }
      //helper::pre($details);exit;
      return $details;
   }

}
