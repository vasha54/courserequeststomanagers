<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information
 *
 * @package    tool_courserequeststomanagers
 * @copyright  2014 Catalyst IT {@link http://www.catalyst.net.nz}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Luis Andrés Valido Fajardo <luis.valido1989@gmail.com>
 */


namespace tool_courserequeststomanagers\handle_export;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/pdflib.php');

class custom_pdf extends \pdf {


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