<?php

namespace App;
namespace App\model;
use Illuminate\Database\Eloquent\Model;
class User_type extends Model {

    public static function all_user_type() {
        $result = User_type::where('is_active', '=', 1)->get()->toArray();
        return $result;
    }

    protected $fillable = [
        'type_code', 'user_type', 'is_active'
    ];
    public $timestamps = false;
    protected $guarded = ['id'];

}
