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

class handle_export_pdf extends handle {

	function __construct ($_action,$_view){
		parent::__construct($_action,$_view);
	}

    public  function generateReport($_rows){
        $site=get_site();
        $nameSite=$site->fullname;
        $name = get_string('title_report','tool_courserequeststomanagers').get_string('pluginname','tool_courserequeststomanagers').'-'.$nameSite;
        $pdf = new custom_pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->InitParamatersFormatStyle();
        $pdf->SetHeaderData('moodle.svg',15,get_string('title_report','tool_courserequeststomanagers'),
                                get_string('pluginname','tool_courserequeststomanagers').'-'.$nameSite);
        $pdf->SetTitle(clean_filename(get_string('title_report','tool_courserequeststomanagers')));
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        $header = array(get_string('requestedby'), get_string('fullnamecourse'),
                                /* get_string('summary'),*/ get_string('category'), get_string('requestreason'), 
                                 get_string('responsible_manager','tool_courserequeststomanagers')/*,get_string('action')*/);

        $percent=array(20,20,20,20,20);
 
        $html=$this->buildTableHtml($header,$_rows,$percent,array('C'),array('L'));

        $pdf->writeHTML($html, true, false, false, false, '');
        $nameFile = clean_filename($name).'.pdf';
        $pdf->Output($nameFile,'D'); 
    }

    protected function buildTableHtml($_headcol,$_rows,$_percents,$_alignHead,$_alignData) {
		$colums=count($_headcol);
		$htmlTable= '<table width="100%" cellspacing="0" cellpadding="4" border="1px" >  <thead><tr>';
        	
    	for($i=0;$i<count($_headcol);$i++){
        	$align='center';
        	if($_alignHead[$i%count($_alignHead)]=='L') $align='left';
        	else if($_alignHead[$i%count($_alignHead)]=='R') $align='right';

        	$htmlTable=$htmlTable.'<td  align="'.$align.'" width="'.$_percents[$i]. '%"><strong>'.$_headcol[$i].'</strong></td>';
    	}
    	$htmlTable.='</tr></thead>';
    
    	$nrows=count($_rows);
        $colums=count($_alignData);
    
    	for($i=0;$i<$nrows;$i++){
       		$htmlTable.='<tr>';
        	$row=$_rows[$i];
        
        	for($j=0;$j<count($row);$j++){
             	$align='left';
             	if($_alignData[$j%count($_alignData)]=='C') $align='center';
             	else if($_alignData[$j%count($_alignData)]=='R') $align='right';
             
            	$colorCell="black"; 
            	$htmlTable.='<td align="'.$align.'" color="'.$colorCell.'"  
                   width="'.$_percents[$j].
                    '%">'.$row[$j].'</td>';
            
        	}
        
        	$htmlTable.='</tr>';
    	}
    
    	$htmlTable=$htmlTable.'</table>';
    	return $htmlTable;
	}
}
