<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Util extends Model
{
    /**
     * Check array
     *
     * @author DungNT
     * @since 09/11/2015
     * @param array $the_a_Array
     * @return boolean
     */
    public static function b_fCheckArray($the_a_Array)
    {
        return isset($the_a_Array) && is_array($the_a_Array) && $the_a_Array;
    }

    /**
     * Check object
     *
     * @author DungNT
     * @since 09/11/2015
     * @param stdClass $the_o_Object
     * @return boolean
     */
    public static function b_fCheckObject($the_o_Object)
    {
        return isset($the_o_Object) && is_object($the_o_Object) && $the_o_Object;
    }

    /**
     * Get Current datetime
     * @author DungNT
     * @since 24/12/2015
     * @param string $the_sz_Format
     * @return string
     */
    public static function sz_fCurrentDateTime($the_sz_Format = 'Y-m-d H:i:s')
    {
        return date($the_sz_Format, time());
    }

    /**
     * Merge multi array to single array
     * @author DungNT
     * @since 25/12/2015
     * @param array $the_a_MultiArray
     * @param string $the_sz_Key
     * @param string $the_sz_Value
     * @param boolen $the_b_SortByValue
     * @param boolen $the_b_SortDesc
     * @return array $a_SingleArray
     */
    public static function a_fMultiToSingleArray($the_a_MultiArray, $the_sz_Key = 'id', $the_sz_Value = 'name', $the_b_SortByValue = false, $the_b_SortDesc = false) {
        $a_SingleArray = array();
        foreach ($the_a_MultiArray as $a_Single) {
            $a_SingleArray[$a_Single[$the_sz_Key]] = $a_Single[$the_sz_Value];
        }
        return $a_SingleArray;
    }
    //$sz_Start,$sz_End,$position_day
    public static function i_fNumberOfDays($i_FromTime,$i_ToTime,$sz_day)
    {
        $i_FromTime = strtotime(date('Y-m-d 00:00:00',$i_FromTime)) ;
        $i_ToTime = strtotime(date('Y-m-d 00:00:00',$i_ToTime)) ;
        $dt = Array ();
        for($i=$i_FromTime; $i<=$i_ToTime;$i=$i+86400) {
                if(date("l",$i) == $sz_day) {
                        $dt[] = date("l Y-m-d ", $i);
                }
        }
        return count($dt);
        //echo "Found ".count($dt). " Saturdays...<br>";
//        for($i=0;$i<count($dt);$i++) {
//                echo $dt[$i]."<br>";
//        }
    }
    
    /**

     * @Auth: Dienct
     * @Des: Format date time
     * @Since: 8/1/2016     
     */
    public static function sz_DateTimeFormat($date_time){
        $date = date_create($date_time);
        return date_format($date,"d/m/Y H:i:s");
    }
    
    /**
     * @Auth: HuyNN
     * @Des: Create date Range from time to time
     * @Since: 13/01/2016     
     */
    public static function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
    
    /**
     * @Auth: HuyNN
     * @Des: Create array date from current month to last month
     * @Since: 13/01/2016     
     */
    public static function GetRangeDate($i_Month, $i_Year)
    {
        if($i_Month == 0)
        {
            $i_CurrentMonth =  date("m");
            $i_CurrentMonth = (int) $i_CurrentMonth;
            $i_Year =  date("Y");
        }
        else
        {
            $i_CurrentMonth = $i_Month;
        }
        if($i_CurrentMonth == 1)
    	{
            $i_LastMonth = 12;
            $i_LastYear = $i_Year - 1;
    	} 
    	else
    	{
            $i_LastMonth = $i_CurrentMonth - 1;
            $i_LastYear = $i_Year;
    	} 
        $sz_TimeEnd = $i_Year.'-'.($i_CurrentMonth < 10?'0'.$i_CurrentMonth:$i_CurrentMonth).'-25';
    	$sz_TimeStart = $i_LastYear.'-'.($i_LastMonth < 10?'0'.$i_LastMonth:$i_LastMonth).'-26';
        ///////Mảng lưu các ngày thỏa mãn theo tháng và năm////////
    	$a_RangeDate = Util::createDateRangeArray($sz_TimeStart,$sz_TimeEnd);
        return $a_RangeDate;
    }
    
        /**
     * @Auth: HuyNN
     * @Des: Create array day from array date
     * @Since: 25/02/2016     
     */
    public static function GetRangeDay($a_RangeDate)
    {
        if(is_array($a_RangeDate))
        {
            foreach ($a_RangeDate as $key => &$sz_Date) 
            {
                $i_Date = strtotime($sz_Date);
                $i_DateNumber = date('d',$i_Date);
                $i_DateNumber = $i_DateNumber;
                $a_RangeDate[$key] = $i_DateNumber;
                $sz_GetdayFromDate = date( "l", $i_Date);
                $a_RangeDay[$i_DateNumber] = config('cmconst.day.'.$sz_GetdayFromDate);
            }
        }
        return $a_RangeDay;
    }
    
    public static function GetRealDate($i_year, $i_month, $i_date)
    {
        // Huy cc nhờ sửa hộ.
        $i_year = (int) $i_year;
        $i_month = (int) $i_month;
        $i_date = (int) $i_date;
        if($i_date >= 26 )
        {
            if($i_month == 1)
            {
                $i_month = 12;
                $i_year = $i_year - 1;
            }else{
                $i_month = $i_month - 1;
            }
        }    
        $i_month = $i_month < 10 ?'0'.$i_month : $i_month;
        return strtotime($i_year.'-'.$i_month.'-'.$i_date) ;
    } //test
}
