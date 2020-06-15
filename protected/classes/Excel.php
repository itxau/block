<?php

include "phpexcel/PHPExcel.php";

class Excel {

    private $expCellName = null;
    private $expTableData = null;
    private $fileName = null;
    private $cellNum = null;
    private $dataNum = null;
    private $cellName = null;
    private $xlsTitle = null;
    private $objPHPExcel = null;

    public function __construct($expTitle, $expCellName, $expTableData, $fileName) {

        $this->xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
        $this->fileName = $fileName;
        $this->expCellName = $expCellName;
        $this->expTableData = $expTableData;
        $this->cellNum = count($expCellName);
        $this->dataNum = count($expTableData);
        $this->cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        $this->objPHPExcel = new PHPExcel();
    }

    public function excel_export() {
       
     //   设置默认的字体和文字大小 锚：aaa
        $this->objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
       // $this->objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $this->cellName[$this->cellNum - 1] . '1'); //合并单元格
       // $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $this->xlsTitle . '  导出时间:' . date('Y-m-d H:i:s'));
      
        for ($i = 0; $i < $this->cellNum; $i++) {
            $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->cellName[$i] . '1', $this->expCellName[$i][1]);
            $this->objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($this->cellName[$i])->setWidth(10);//宽度
      
        }
        // Miscellaneous glyphs, UTF-8
        
        for ($i = 0; $i < $this->dataNum; $i++) {
            for ($j = 0; $j < $this->cellNum; $j++) {
                $this->objPHPExcel->getActiveSheet(0)->setCellValue($this->cellName[$j] . ($i + 2),  $this->expTableData[$i][$this->expCellName[$j][0]]);
            }
            
        }
      
         //     背景填充颜色     锚：bbb
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:' . $this->cellName[$this->cellNum - 1] . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:' . $this->cellName[$this->cellNum - 1] . '1')->getFill()->getStartColor()->setARGB('FF808080');
        
        ob_end_clean();
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $this->xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$this->fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }

}
