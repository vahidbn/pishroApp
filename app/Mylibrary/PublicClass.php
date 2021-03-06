<?php
namespace App\Mylibrary;

use App\user_activity_log;
use App\acl_userRoleAction;

class PublicClass
{


public static   function jalali_to_gregorian($jy,$jm,$jd,$mod=''){
  //https://jdf.scr.ir/source/
   list($jy,$jm,$jd)=explode('_',($jy.'_'.$jm.'_'.$jd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
   if($jy > 979){
    $gy=1600;
    $jy-=979;
   }else{
    $gy=621;
   }
   $days=(365*$jy) +(((int)($jy/33))*8) +((int)((($jy%33)+3)/4)) +78 +$jd +(($jm<7)?($jm-1)*31:(($jm-7)*30)+186);
   $gy+=400*((int)($days/146097));
   $days%=146097;
   if($days > 36524){
    $gy+=100*((int)(--$days/36524));
    $days%=36524;
    if($days >= 365)$days++;
   }
   $gy+=4*((int)(($days)/1461));
   $days%=1461;
   $gy+=(int)(($days-1)/365);
   if($days > 365)$days=($days-1)%365;
   $gd=$days+1;
   foreach(array(0,31,((($gy%4==0) and ($gy%100!=0)) or ($gy%400==0))?29:28 ,31,30,31,30,31,31,30,31,30,31) as $gm=>$v){
    if($gd <= $v)break;
    $gd-=$v;
   }
   return($mod==='')?array($gy,$gm,$gd):$gy .$mod .$gm .$mod .$gd;
  }
//---------------------------------
  public static   function convert_jalali_to_gregorian($jy,$jm,$jd)
  {
    $publicClass= new PublicClass;
    $a=  $publicClass->jalali_to_gregorian($jy,$jm,$jd,$mod='');
    $day=$a[0];
      if ($day<=9) $day='0'.$a[0];
    $month=$a[1];
      if ($month<=9) $month='0'.$a[1];
    $year=$a[2];
    if ($year<=9) $year='0'.$a[2];
    $date=$day.'/'.$month.'/'.$year;
    return $date;
  }
//---------------------------------
function gregorian_to_jalali_byString($date)
{
    $cDate=explode("-",$date);
    $days=explode(" ",$cDate[2]);
    return $this->gregorian_to_jalali($cDate[0],$cDate[1],$days[0]);
}


function gregorian_to_jalali($gy,$gm,$gd,$mod=''){
    list($gy,$gm,$gd)=explode('_',($gy.'_'.$gm.'_'.$gd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
 $g_d_m=array(0,31,59,90,120,151,181,212,243,273,304,334);
 if($gy > 1600){
  $jy=979;
  $gy-=1600;
 }else{
  $jy=0;
  $gy-=621;
 }
 $gy2=($gm > 2)?($gy+1):$gy;
 $days=(365*$gy) +((int)(($gy2+3)/4)) -((int)(($gy2+99)/100)) +((int)(($gy2+399)/400)) -80 +$gd +$g_d_m[$gm-1];
 $jy+=33*((int)($days/12053));
 $days%=12053;
 $jy+=4*((int)($days/1461));
 $days%=1461;
 $jy+=(int)(($days-1)/365);
 if($days > 365)$days=($days-1)%365;
 if($days < 186){
  $jm=1+(int)($days/31);
  $jd=1+($days%31);
 }else{
  $jm=7+(int)(($days-186)/30);
  $jd=1+(($days-186)%30);
 }
 return($mod==='')?array($jy,$jm,$jd):$jy .$mod .$jm .$mod .$jd;
}
//---------------------------------
  public static   function convert_gregorian_to_jalali($jy,$jm,$jd)
  {
    $publicClass= new PublicClass;
    $a=  $publicClass->gregorian_to_jalali($jy,$jm,$jd,$mod='');
    $day=$a[0];
      if ($day<=9) $day='0'.$a[0];
    $month=$a[1];
      if ($month<=9) $month='0'.$a[1];
    $year=$a[2];
      if ($year<=9) $year='0'.$a[2];
    $date=$day.'/'.$month.'/'.$year;
    return $date;
  }
//---------------------------------


  public static function get_Table_Name($slug)
    {
  //     if ($slug == 'xeeoirder')
  //     return "stockroom_orders";
     switch ($slug)
      {
        case "xeeoirder":
            return "stockroom_orders";
        break;

        case "xeeptningstk":
            return "stockroom_stock_putting_products";
        break;

        case "xeeptnserls3":
            return "stockroom_serialnumbers";
        break;

        case "xeeptprodusz":
            return "stockroom_products";
        break;

        case "xeepbrndssz":
            return "stockroom_products_brands";
        break;

        case "xeepTypsssz":
            return "stockroom_products_types";
        break;

        case "xeeZsstkREqssz":
            return "sell_stockrequests";
        break;
        case "xeeZCsBtmrPxZssz":
            return "custommers";
        break;
        case "xeeZTk0trdcPssz":
            return "sell_takeoutproducts";
        break;

        case "xeestkReqDlsssz":
          return "sell_stockrequests_details";

         case "xeOrGkReeqDlrsz":
             return "custommerorganizations";

        break;





      }
  }

    public  function add_user_log($string,$status,$username)
    {

        $user_activity_log = new user_activity_log;
        $user_activity_log->ual_ip = \request()->ip();
        $user_activity_log->ual_status =$status;
        $user_activity_log->ual_Activity =$string;
        $user_activity_log->ual_userId =$username;
        $user_activity_log->save();
    }


    public function checkAscii($String)
    {
        $NewString="";
        for ($i=0; $i<=strlen($String)-1 ; $i++)
        {
            $char= ord($String[$i]);
            if ($char<=126 && $char>=0 )
                $NewString=$NewString.chr($char);
            else
            {
                if (ord($String[$i]) ==226 && ord($String[$i+1])==128 && ord($String[$i+2])==144 )
                    $NewString=$NewString.'-';
            }
        }
        return $NewString;
    }


    public function checkUserValidation($DashboardController)
    {
        $r=json_encode($DashboardController);
        $set=strpos($r,"id");
        $subster=    substr($r,$set+4,3) ;
        $pos=strpos($subster,',"');
        $strArray=(str_split($subster)) ;
        $numStr="";
        foreach ($strArray AS $ss)
        {
            if ($ss !=',' && $ss !='"')
                $numStr=$numStr.$ss;
        }

        $user = acl_userRoleAction::where('ura_user_id', '=', (int)$numStr)->firstOrFail();
        if ($user->deleted_flag !=1)
            return true;
        else
            return false;
    }



    public static function Get_all_orgs()
    {
        $val= \DB::table('custommerorganizations AS org' )
            ->select( \DB::raw('
                            org.id   AS id,                                              
                            org.org_name  AS  orgName
                            '))

             ->where('org.deleted_flag', '=', 0)
//            ->where('custommer.cstmr_detials', '=', 0)
            ->get();

        $str_return='';
        foreach ( $val AS  $rr  )
        {
            $str_return=$str_return.'<option value="'.$rr->id.'">'.$rr->orgName.'</option>';
        }
        return $str_return;
//        return $val;
    }

}
