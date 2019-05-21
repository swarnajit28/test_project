<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<table cellpadding="0" cellspacing="0" style="width: 80%;">
    <thead style="width: 100%;border-collapse: collapse;text-align: left; background:transparent;">
        <th colspan="2" style="background: #f1f1f1; padding-bottom:20px; padding-top: 20px; text-align: center;"><img src="{{asset('public/images/logo.png')}}" alt=""/>
        </th>
    </thead>   
    <tbody>
      
      <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:100px;"></td>
        <td style="color: #576663;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;"></td>
      </tr>

      <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;">
        Hi {{ $content['user']['display_name'] }} welcome to Simplify CRM </td>
      </tr>

      <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;">Your Login Credentials as follows:</td>
         
      </tr>
      
      <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;">Name:</td>
        <td style="color: #576663;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;">{{ $content['user']['display_name'] }}</td>
      </tr>

      <tr style=" background:#fbfbfb;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;">Username:</td>
        <td style="color: #576663;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;">{{ $content['user']['username'] }}</td>
      </tr>
      <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;">Password:</td>
        <td style="color: #576663;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;">{{ $content['password'] }}</td>
      </tr>

       <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;">Please click here for verify &nbsp;&nbsp;
&nbsp;&nbsp; <a href="{{ route('verify_mail_user',['id' => Crypt::encrypt($content['eid']) ]) }}">Verify Mail</a> </td>
      </tr>

      <tr style="background:#fff;">
        <td style="color: #3bac49;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;width:60%;"></td>
        <td style="color: #576663;padding: 10px 20px;font-size:16px;font-family:Arial, Helvetica, sans-serif;"></td>
      </tr>

    </tbody>
    <thead style="width: 80%;border-collapse: collapse;text-align: left; background:transparent;">
      <tr  style="background: #fff;border:0;width: 100%;border-collapse: collapse;text-align: left;">
        <th colspan="2" style="background: #3bac49; padding-bottom:20px; padding-top: 20px; text-align: center; font-size:14px; color: #fff">© 2018 <br> SIMPLIFY. ALL RIGHTS RESERVED.</th>
      </tr>
    </thead> 
  </table>
</body>
</html>