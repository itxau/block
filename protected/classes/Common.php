<?php
/**
 * 框架所能提供的通用方法
 *
 * @author:sun xian gen(baxgsun@163.com)
 * 
 */
class Common
{
    public static function getAvatar($avater) {
        if ($avater)
            return $avater;
        else
            return str_replace("/index.php","",$_SERVER['PHP_SELF'])."/static/images/avatar.png";
    }
    //活动文档扩展名图表
    public static function getExtensionIcon($ext) {
        $icon="";
        switch (strtolower($ext)) {
            case 'xls':
            case 'xlsx':
                $icon="fa-file-excel-o";
                break;
            case 'doc':
            case 'docx':
                $icon="fa-file-word-o";
                break;
            case 'gif':
            case 'jpg':
            case 'png':
            case 'bmp':
                $icon="fa-file-image-o";
                break;
            case 'pdf':
                $icon="fa-file-pdf-o";
                break;
            case 'rar':
            case 'zip':
            case '7z':
                $icon="fa-file-archive-o";
                break;
            default:
                $icon="fa-file";
                break;
        }
        return $icon;


    }

    //生成密码
    public static function generatePWord($password,$validcode="") {
        if (!$validcode) $validcode=CHash::random(8);
        return CHash::md5($password,$validcode);
    }

    //状态值映射
    public static function statusMap($statusArray,$status,$default="未知"){
        if (array_key_exists($status,$statusArray)) 
            return $statusArray[$status];
        else
            return $default;
    }

    //导出 excel
    public static function exportExcel($cells, $data) {
        $sheet    = date('YmdHis');
        $filename = date('YmdHis')."_".rand(100,999);
        $expExcel = new Excel($sheet, $cells, $data, $filename);
        $expExcel->excel_export();
    }

    //获得datagrid 的序列号
    public static function gridIndex($pageData,$index) {
        $page=$pageData["page"]; 
        $pagesize=$pageData["pageSize"];
        $idx=($page-1)*$pagesize + $index + 1 ;
        return $idx;
    }

    public static function datetime2String($preStr="")  {
        return $preStr.date('YmdHis').rand(1000,9999);
    }
   
    /**
     * 10进制转36进制
     *
     * @access public
     * @param String $dec 十进制数据
     * @param String $len 转后的长度
     * @return String
     */
    public static function  dec2Hex($dec,$len) {
        $dec=intval($dec);

        $map = array(
            0=>'0',1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',
            10=>'A',11=>'B',12=>'C',13=>'D',14=>'E',15=>'F',16=>'G',17=>'H',18=>'I',19=>'J',
            20=>'K',21=>'L',22=>'M',23=>'N',24=>'O',25=>'P',26=>'Q',27=>'R',28=>'S',29=>'T',
            30=>'U',31=>'V',32=>'W',33=>'X',34=>'Y',35=>'Z');

        $dex=count($map);

        $suffix = '';
        do {
            $suffix = $map[($dec % $dex)] . $suffix;
            $dec /= $dex;
        } while ($dec >= 1);

        if (strlen($suffix)<$len)
            return str_pad($suffix,$len,0,STR_PAD_LEFT);
        else
            return $suffix;

    }
   
    public static function generateUniqueNo($preStr="") {
        $yesr_str = 'ABCDEFGHJKLMNPQRSTUVWXYZABCDEFGHJKLMNPQRSTUVWXYZ';
        $strY=substr($yesr_str,date("Y")-2019,1);
        $strD=self::dec2Hex(date('z'),2);
        $strS=self::dec2Hex(date('His'),4);
        $strR=self::dec2Hex(rand(1,999999),4);
        return $preStr.$strY.$strD.'-'.$strS.'-'.$strR;
    }

    public static function generateAccount() {
        $yesr_str = 'ABCDEFGHJKLMNPQRSTUVWXYZABCDEFGHJKLMNPQRSTUVWXYZ';
        $strY=substr($yesr_str,date("Y")-2019,1);
        $strD=self::dec2Hex(date('z'),2);
        $strS=self::dec2Hex(date('His'),4);
        return $strY.$strD.$strS.rand(100,999);
    }
   
    
    
}
