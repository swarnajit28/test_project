<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use helper;
class Website_setting extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    //protected $table = 'customer_attachments';
    public static function weekly_project_target() {
        $setting = Website_setting::select('weekly_project_target')
                ->get();
        if ($setting->isEmpty()) {
            return 5000;
        } else {
            $data = $setting->toArray();
            return $data[0]['weekly_project_target'];
        }
    }
    
    public static function exclusive_lock_days() {
        $setting = Website_setting::select('customer_exclusive_lock_days')
                ->get();
        if ($setting->isEmpty()) {
            return 56;
        } else {
            $data = $setting->toArray();
            return $data[0]['customer_exclusive_lock_days'];
        }
    }

}
