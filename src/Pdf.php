<?php

namespace oyswonder\Html2pdf;

/**
 * Laravel htm2pdf: convert html into pdf
 *
 * @package laravel-html2pdf
 * @author Nahidul Hasan <nahidul.cse@gmail.com>
 * @author Wonder <oyswonder@gmail.com>
 */
class Pdf
{

    protected $system ;

    protected $isWindows;

    protected $params = ' ';

    public function __construct()
    {
        $this->system = php_uname('s');
        $this->isWindows = $this->system === 'Windows NT';
    }

    /**
     * Convert html into pdf
     *
     * @param $input
     * @return bool|string
     */
    public function generatePdf($input)
    {
        $key = time() . '-' . rand(10000, 99999);

        $pdfFile = storage_path() . DIRECTORY_SEPARATOR . 'page-' . $key . '.pdf';

        $generatedFile = $this->executeCommand($input, $pdfFile);

        $this->removeAndReturnFile($pdfFile);

        return $generatedFile;

    }


    /**
     * Make the PDF downloadable by the user
     *
     * @param $input
     * @return bool|string
     */

    public function download($input)
    {
        $file = $this->generatePdf($input);

        return $file;


    }


    /**
     * Open PDF in the browser
     * @param $input
     */
    public function stream($input)
    {
        $file = $this->generatePdf($input);

        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . 'filename.pdf' . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        echo $file;

    }

    /**
     * Removed generated file and return
     *
     * @param $pdfFile
     * @return mixed
     */
    protected function removeAndReturnFile($pdfFile)
    {
        $rmCmd = $this->returnRemoveCommand();
        shell_exec($rmCmd . $pdfFile);
    }

    /**
     * @param $htmlFile
     * @param $pdfFile
     * @return false|string
     */
    public function executeCommand($htmlFile, $pdfFile)
    {
        $params = $this->params;
        $htmlInput = '"'. $htmlFile .'"';
        if($this->isWindows){
            exec("wkhtmltopdf {$params} {$htmlInput} {$pdfFile}", $output, $code);
        }else{
            exec("xvfb-run wkhtmltopdf {$params} {$htmlInput} {$pdfFile}", $output, $code);
            if($code !==0){
                exec("wkhtmltopdf {$params} {$htmlInput} {$pdfFile}", $output, $code);
            }
        }

        if($code === 0){
            $generatedFile = file_get_contents($pdfFile);
        }else{
            $generatedFile = 'E';
        }

        return $generatedFile;
    }

    /**
     * Set Params
     *
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        if(is_string($params)){
            $this->setOneParam($params);
        }elseif(is_array($params)){
            foreach ($params as $param){
                $this->setOneParam($param);
            }
        }
        return $this;
    }

    public function setOneParam(String $param)
    {
        $param!=='' && $this->params .= '--'.$this->cmdFilter($param).' ';
        return $this;
    }

    /**
     * Return Remove Command
     *
     * @return string
     */
    protected function returnRemoveCommand()
    {
        return $this->isWindows ? 'del ' : 'rm ';
    }

    /**
     * @param String $string
     * @return mixed
     */
    protected function cmdFilter(String $string)
    {
        $substitutions = ['&' => '', '|' => '', ';' => ''];
        return str_replace(array_keys($substitutions), $substitutions, $string);
    }
}
