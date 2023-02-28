<?php



defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/pdflib.php');


class CustomPDF extends \pdf {


    public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8') {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding);
    }

    public function InitParamatersFormatStyle(){
        $this->SetCreator(PDF_CREATOR);
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->SetFont('helvetica', 'B', 20);
    }

    public function Output($name='doc.pdf', $dest='I'){
        ob_end_clean();
        parent::Output($name,$dest);
    }

    

    public function SetHeaderData($ln='', $lw=0, $ht='', $hs='', $tc=array(0,0,0), $lc=array(0,0,0)){
        $ln = '/admin/tool/courserequeststomanagers/pix/'.$ln;
        parent::SetHeaderData($ln,$lw,$ht,$hs,$tc,$lc);
    }
}
