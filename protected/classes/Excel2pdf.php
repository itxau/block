<?php

include_once 'phpexcel/PHPExcel.php';
require_once ('phpexcel/PHPExcel/IOFactory.php');
include_once ('phpexcel/PHPExcel/Writer/Excel2007.php');
include_once ('phpexcel/PHPExcel/Writer/PDF.php');
include_once ('phpexcel/PHPExcel/Writer/PDF/DomPDF.php');

class Excel2pdf {

    public static function do($filename) {
        $filename='a.pdf';
        $encoded_filename = rawurlencode($filename);
        $rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF; //指定通过mpdf类库导出pdf文件
        $rendererLibraryPath = 'PHPExcel/MPDF57'; //指定你下载的mpdf类库路径
        if (!\PHPExcel_Settings::setPdfRenderer(
          $rendererName,
          $rendererLibraryPath
        )) {
          die(
            'Please set the $rendererName and $rendererLibraryPath values' .
            PHP_EOL .
            ' as appropriate for your directory structure'
          );
        }
        header('Content-type: application/pdf');
        if (preg_match("/MSIE/", $ua) || preg_match("/Trident\/7.0/", $ua) || preg_match("/Edge/", $ua)) {
          header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
          header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"');
        } else {
          header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }
        header("Pragma:no-cache");
        header("Expires:0");
        $objWriter = new \PHPExcel_Writer_PDF($objPHPExcel);
        $objWriter->setPreCalculateFormulas(false);
        $objWriter->save('php://output');
    }

}
