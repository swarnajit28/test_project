<?php

namespace App\Helpers;

//require 'pdfcrowd-4.2.1.php';
use App\Models\admins\Countie;
// require 'autoload.php';
use App\Models\admins\Countrie;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Pdfcrowd;

class MyFuncs
{

    public static function pre($array, $die = false)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        if ($die) {
            die();
        }
    }

    public static function lastQuery($die = false)
    {
        DB::enableQueryLog();
        $query = DB::getQueryLog();
        $query = end($query);

        echo '<pre>';
        print_r($query);
        echo '</pre>';

        if ($die) {
            die();
        }

    }

    public static function encryptForm($field_keys)
    {
        $return_enc_fields = array();
        foreach ($field_keys as $key => $value) {

            $encrypt_val             = '';
            $encrypt_val             = self::encrypt($value);
            $return_enc_fields[$key] = $encrypt_val;
        }
        //self::pre($return_enc_fields);
        return $return_enc_fields;
    }

    public static function decryptForm($post_data = array(), $white_list = array(), $ignore_keys = array())
    {
        $return_post_data = array();

        foreach ($post_data as $key => $value) {
            if (!in_array($key, $ignore_keys)) {
                $dycrypt_val = '';
                $dycrypt_val = self::decrypt($key);

                if (in_array($dycrypt_val, $white_list)) {
                    $return_post_data[$dycrypt_val] = $value;
                } else {
                    return false;
                }
            }
        }
        //  self::pre($return_post_data,0);
        return $return_post_data;
    }

    public static function encrypt($name)
    {
        $name     = trim($name);
        $charArr  = str_split(trim($name));
        $ascii    = '';
        $numRange = range(0, count($charArr) - 1);
        shuffle($numRange);
        foreach ($numRange as $k => $v) {
            $ascii .= dechex(ord($charArr[$v])) . self::genChar('G', 'Z', 1);
        }

        $firstBit = 'ft';
        foreach ($numRange as $kk => $vv) {
            $firstBit .= $vv . self::genChar('g', 'z', 1);
        }
        $finalEnc = $firstBit . self::genChar('G', 'Z', rand(6, 12)) . $ascii;
        return $finalEnc;
    }

    private static function genChar($rangefrom, $rangeto, $length = 1)
    {
        $alphabets   = range($rangefrom, $rangeto);
        $final_array = array_merge($alphabets);
        $char        = '';
        while ($length--) {
            $key = array_rand($final_array);
            $char .= $final_array[$key];
        }
        return $char;
    }

    public static function decrypt($finalEnc)
    {
        $temp = str_replace('ft', '', $finalEnc);
        if ($temp == $finalEnc) {
            return false;
        }
        $finalEnc   = $temp;
        $alphabets  = range('G', 'Z');
        $alphabets1 = range('g', 'z');
        $temp       = str_replace($alphabets, '|', $finalEnc);
        if ($temp == $finalEnc) {
            return false;
        }
        $finalEnc    = $temp;
        $finalEncArr = explode('|', $finalEnc);
        $shuffled    = $finalEncArr[0];
        $temp        = str_replace($alphabets1, '|', $shuffled);
        if ($temp == $shuffled) {
            return false;
        }
        $shuffled    = $temp;
        $shuffledArr = explode('|', $shuffled);
        unset($finalEncArr[0]);
        $finalNameDecr = '';
        foreach ($finalEncArr as $k1 => $v1) {
            if ($v1 != '') {
                $finalNameDecr .= chr(hexdec($v1));
            }
        }

        $charArr1 = str_split(trim($finalNameDecr));
        //self::pre($charArr1);
        //self::pre($shuffledArr);
        $finalNameDecr = '';
        $i             = 0;
        $shuffledArr1  = array();
        foreach ($shuffledArr as $v2) {
            if ($v2 != '') {
                //echo '<br />'.$shuffledArr1[$v2]." = ".$charArr1[$i];
                $shuffledArr1[$v2] = $charArr1[$i];
                $i++;
            }
        }
        ksort($shuffledArr1);
        foreach ($shuffledArr1 as $k3 => $v3) {
            $finalNameDecr .= $v3;
        }

        //die('DECRYPT->SP');
        return $finalNameDecr;
    }

    public static function getFormFields($form_name)
    {
        $fieldArray = Config::get('formArray.' . $form_name);
        return $fieldArray;
    }

    public static function current_timestamp($type = 'date_time')
    {
        $cur_time = ($type == 'date') ? date('Y-m-d') : date('Y-m-d H:i:s');
        return $cur_time;
    }

    public static function AlphaNumeric($length)
    {
        $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $clen  = strlen($chars) - 1;
        $id    = '';

        for ($i = 0; $i < $length; $i++) {
            $id .= $chars[mt_rand(0, $clen)];
        }
        return ($id);
    }

    public static function getAllModelName()
    {
        return Config::get('formArray.model_name');
    }

    public static function generateUsername($f_name, $l_name)
    {
        $f_name = self::clean($f_name);
        $l_name = self::clean($l_name);

        $user_name = strtolower(substr($f_name, 0, 1) . $l_name);
        //$existing_count = User::where('username', $user_name)->count();
        $total_count = User::where('concated_username', $user_name)->count();
        $_this = new self;
        if ($total_count != 0) {
            $chkusername = $user_name . $total_count;
            return $_this->recursiveCheckUsername($chkusername);
        } else {
            return $_this->recursiveCheckUsername($user_name);
        }
    }

    public static function recursiveCheckUsername($username)
    {
        $total_count1 = User::where('username', $username)->count();
        //echo "count = ".$total_count1;exit;
        if($total_count1==0)
        {
            return $username;
        }
        else
        {
            $_this = new self;
            $username = $username.$total_count1;
            return $_this->recursiveCheckUsername($username);
        }
    }

    public static function getConcatenatedUsername($f_name, $l_name)
    {
        $f_name = self::clean($f_name);
        $l_name = self::clean($l_name);

        $user_name = strtolower(substr($f_name, 0, 1) . $l_name);
        return $user_name;
    }

    public static function generateUsernameNewformat($name)
    {
        $name = self::clean($name);

        $exp_name  = explode(' ', trim($name));
        $fusername = $exp_name[0];
        //$existing_count = User::where('username', $fusername)->count();
        $total_count = User::where('concated_username', $fusername)->count();
        $_this = new self;
        if ($total_count != 0) {
            $chkusername = $fusername . $total_count;
            return $_this->recursiveCheckUsername($chkusername);
        } else {
            return $_this->recursiveCheckUsername($fusername);
        }
    }

    public static function generateUsernameIfExist($u_name, $f_name, $l_name)
    {
        $u_name = self::clean($u_name);
        $existing_count = User::where('username', $u_name)->count();
        return ($existing_count != 0) ? self::generateUsername($f_name, $l_name) : $u_name;
    }

    public static function generatePassword($l_name)
    {
        /*$microsec = explode(".", microtime(true));
        $randNo   = date('ms') . rand(0, 9) . $microsec[1];
        $sixDigit = substr($randNo, -6);
        return $sixDigit;*/
        $l_name = self::clean($l_name);
        return strtolower($l_name);
    }

    public static function generatePasswordNewformat()
    {
        $random = substr(rand() * 999999 + 100000, 0, 6);
        return $random;
    }

    public static function getCountryList()
    {
        $countryList = Countrie::listCountry();
        return $countryList;
    }

    public static function getCountyList()
    {
        $countyList = Countie::listCounty();
        return $countyList;
    }

    public static function getPackingTimeList()
    {
        $packingTimeList = array(
            '08:00' => '08:00',
            '08:15' => '08:15',
            '08:30' => '08:30',
            '08:45' => '08:45',
            '09:00' => '09:00',
            '09:15' => '09:15',
            '09:30' => '09:30',
            '09:45' => '09:45',
            '10:00' => '10:00',
            '10:15' => '10:15',
            '10:30' => '10:30',
            '10:45' => '10:45',
            '11:00' => '11:00',
            '11:15' => '11:15',
            '11:30' => '11:30',
            '11:45' => '11:45',
            '12:00' => '12:00',
            '12:15' => '12:15',
            '12:30' => '12:30',
            '12:45' => '12:45',
            '13:00' => '13:00',
            '13:15' => '13:15',
            '13:30' => '13:30',
            '13:45' => '13:45',
            '14:00' => '14:00',
            '14:15' => '14:15',
            '14:30' => '14:30',
            '14:45' => '14:45',
            '15:00' => '15:00',
            '15:15' => '15:15',
            '15:30' => '15:30',
            '15:45' => '15:45',
            '16:00' => '16:00',
        );
        return $packingTimeList;
    }

    public static function json_response($data = array(), $http_response, $error_message, $success_message, $error_msg_details = '')
    {

        //die('http_response->'.$http_response);
        $developer = 'www.massoftind.com';
        $version   = str_replace('_', '.', Config::get('serverconfig.test_api_ver'));

        $raws = array();
        if ($error_message != '') {
            $raws['error_message'] = $error_message;
            if ($error_msg_details != '') {
                $raws['error_msg_details'] = $error_msg_details;
            }
        } else {
            $raws['success_message'] = $success_message;
        }

        $raws['data']    = $data;
        $raws['publish'] = array(
            'version'   => $version,
            'developer' => $developer,
        );
        //$raws['status'] = Config::get('formArray.status_code.' . $http_response);

        /*$response = array(array(
        'raws' => $raws
        ), array('api_response'=>Config::get('serverconfig.'.$http_response)));*/

        $response = array(
            'raws' => $raws,
        );
        //response in json format
        //return response($response);

        return response(array(
            'response' => $response,
        ), Config::get('formArray.status_code.' . $http_response));

    }

    public static function valid_email($mail)
    {
        $email = $mail;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public static function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data   = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }

            }
            fclose($handle);
        }

        return $data;
    }

    public static function createPdf($html, $file_name, $header = '', $header_height = '', $footer = '', $footer_height = '', $isSave = '', $path)
    {
        try
        {
            $file_name = $file_name . ".pdf";

            // $userName                 = 'bandhu';

            // echo Config::get('formArray.pdf_crowd.user_name');die;
            $userName = Config::get('formArray.pdf_crowd.user_name');
            $apiKey   = Config::get('formArray.pdf_crowd.api_key');

            //  $userName                 = env('PDF_CROWD_USER_NAME');
            //  $apiKey                   = 'b163ce66997d47077d2893c784020ffb';
            //  $apiKey                   = env('PDF_CROWD_API_KEY');
            $client = new \Pdfcrowd\HtmlToPdfClient($userName, $apiKey);
            $client->setPageMargins("0.3in", "0.3in", "0.3in", "0.3in");
            $client->setPageSize("A4");

            if ($header != '') {
                $client->setHeaderHtml($header);
                if ($header_height != '') {
                    $client->setHeaderHeight($header_height);
                }
            }

            /*if ($footer == '') {
                $footer = "Page: <span class='pdfcrowd-page-number'></span> of <span class='pdfcrowd-page-count'></span>";
            } else {
                if ($footer_height != '') {
                    $client->setFooterHeight($footer_height);
                }
            }*/
            //$client->setFooterHtml($footer);
            if ($isSave == 1) {
                $pdf = $client->convertStringToFile($html, $path . $file_name);
                echo $pdf;
            } else {
                $pdf = $client->convertString($html);
                // set HTTP response headers
                header("Content-Type: application/pdf");
                header("Cache-Control: max-age=0");
                header("Accept-Ranges: none");
                header("Content-Disposition: attachment; filename=\"" . $file_name);
                flush();
                echo $pdf;
            }
        } catch (\Pdfcrowd\Error $why) {
            // report the error to the standard error stream
            // fwrite(STDERR, "Pdfcrowd Error: {$why}\n");
            echo $why;die;
        }
    }


    public static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

}
