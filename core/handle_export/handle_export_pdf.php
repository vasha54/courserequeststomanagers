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
 * @package    report_coursesize
 * @copyright  2014 Catalyst IT {@link http://www.catalyst.net.nz}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../../config.php');
require_once('handle_export.php');
require_once('custom_pdf.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');



class HandleExportPDF extends HandleExport  {

	function __construct ($_action,$_view){
		parent::__construct($_action,$_view);
	}

    public  function generateReport($_rows){
        $site=get_site();
        $nameSite=$site->fullname;
        $name = get_string('title_report','tool_courserequeststomanagers').get_string('pluginname','tool_courserequeststomanagers').'-'.$nameSite;
        $pdf = new CustomPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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

	
	/*public function generateEvaluationQualityCourseStageDetails($_stage){
		$course = Course::getCourse($this->m_courseid);
        $strcoursename = format_string($course->fullname);
        $stage=get_string('one_stage','report_evaluation_quality');

        if($_stage == 2)
            $stage=get_string('two_stage','report_evaluation_quality');
        else if ($_stage == 3)
            $stage=get_string('three_stage','report_evaluation_quality');

        $pathCourse=course::getPath($this->m_courseid);

        $filename = format_string(get_string('course'));

        $site=get_site();
        $nameSite=$site->fullname;
        
        $pdf = new EvaluationQualityPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->InitParamatersFormatStyle();
        $pdf->SetTitle($filename.'-'.$strcoursename.'-'.$stage);
        $pdf->SetHeaderData('icon.svg',15,$filename.'-'.$pathCourse,
                                get_string('pluginname','report_evaluation_quality').'-'.$nameSite);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);
        
        $header = array(
            get_string('status'),
            get_string('indicator', 'report_evaluation_quality'),
            get_string('description_indicator', 'report_evaluation_quality')
        );

        $percent=array(10,30,60);
 
        $rows=Course::getRowsReportThisStage($this->m_courseid,$_stage);

        $html=$this->buildTableHtml($header,$rows,$percent,array('C'),array('C','L','L'));

        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Output(clean_filename($filename.'-'.$strcoursename.'-'.$stage.'.pdf'),'D'); 
	}


	public function proccessFormExport(){
        $coursecat = core_course_category::get(is_object($this->m_categoryid) ? $this->m_categoryid->id : $this->m_categoryid);
        $coursecount = $coursecat->get_courses_count();

        $tableCategories = '';
        $tableCourses = '';
        $tableLegend = '';

        if ($coursecat->has_children()) {
            $tableCategories = $this->buildTableCategoriesPDF($this->m_categoryid,$this->m_courseid,$this->m_aspectExport);
        }

        if ($coursecount) {
            $tableCourses = $this->buildTableCoursesPDF($this->m_categoryid,$this->m_courseid,$this->m_aspectExport);
        }

        $tableLegend = $this->buildTableLegendPDF($this->m_aspectExport);

        $strcategory = $coursecat->get_formatted_name();
        $filename = format_string(get_string('category'));
        $pathCategory=Category::getPath($this->m_categoryid);


        $site=get_site();
        $nameSite=$site->fullname;
        $pdf = new EvaluationQualityPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->InitParamatersFormatStyle();
        $pdf->SetHeaderData('icon.svg',15,$filename.'-'.$pathCategory,
                                get_string('pluginname','report_evaluation_quality').'-'.$nameSite);
        $pdf->SetTitle(clean_filename($this->m_aspectExport->titleReport));
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);
        
        $pdf->writeHTML($tableCategories, true, false, false, false, '');
        $pdf->writeHTML($tableCourses, true, false, false, false, '');
        $pdf->writeHTML($tableLegend, true, false, false, false, '');
        $nameFile = clean_filename($this->m_aspectExport->titleReport).'.pdf';
        $pdf->Output($nameFile,'D');    
	}

	protected function buildTableCategoriesPDF($_categoryid,$_courseid,$_aspectExport){

        $headers = array();
        $percents=array();
        $rows = array();
        $alignHeaders = array();
        $alignRows = array('L');

        $keysTag = AspectExportForm::$keyFormsLabel;

        $countColumn=0;

        array_push($headers,get_string('category'));
        array_push($alignHeaders,'C');
        array_push($percents,20);

        foreach ($_aspectExport as $key => $value) {
            if($value=="1"){
                array_push($headers, get_string($keysTag[$key],'report_evaluation_quality'));
                array_push($alignHeaders,'C');
                $countColumn++;
            }
        }

        $percent = 4;
        if($countColumn!=0)
            $percent = 80/$countColumn;

        foreach ($_aspectExport as $key => $value) {
            if($value=="1"){
                array_push($percents, $percent);
            }
        }

       $answer = $this->generateRowsCategories($_categoryid,$_courseid,$_aspectExport);

       $rows = $answer[0];
       $alignRows = $answer[1]; 
 
       $html=$this->buildTableHtml($headers,$rows,$percents,$alignHeaders,$alignRows);

       return $html;
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
        
        	for($j=0;$j<$colums;$j++){
             	$align='left';
             	if($_alignData[$j]=='C') $align='center';
             	else if($_alignData[$j]=='R') $align='right';
             
            	$colorCell="black"; 
            	if(get_string('emptycourses', 
                                  'report_evaluation_quality')===$_headcol[$j] 
               && $row[$j]!=0)
               		$colorCell="red";
            
            	$htmlTable.='<td align="'.$align.'" color="'.$colorCell.'"  
                   width="'.$_percents[$j].
                    '%">'.$row[$j].'</td>';
            
        	}
        
        	$htmlTable.='</tr>';
    	}
    
    	$htmlTable=$htmlTable.'</table>';
    	return $htmlTable;
	}

	protected function buildTableLegendPDF($_aspectExport){
        $headers = array(get_string('acronym','report_evaluation_quality'),
                         get_string('meaning','report_evaluation_quality'));
        $percents=array(10,25);
        $rows = array();
        $alignHeaders = array('C','C');
        $alignRows = array('C','L');

        $keysTag = AspectExportForm::$keyFormsLabel;

        foreach ($_aspectExport as $key => $value) {
            if($value=="1"){
                $row=array(get_string($keysTag[$key],'report_evaluation_quality'),
                           get_string($key,'report_evaluation_quality'));
                array_push($rows,$row);
            }
        }

        $html=$this->buildTableHtml($headers,$rows,$percents,$alignHeaders,$alignRows);

        return $html;
    }

    protected function buildTableCoursesPDF($_categoryid,$_courseid,$_aspectExport){
        $headers = array();
        $percents=array();
        $rows = array();
        $alignHeaders = array();
        $alignRows = array('L');

        $keysTag = AspectExportForm::$keyFormsLabel;

        $countColumn=0;

        array_push($headers,get_string('course'));
        array_push($alignHeaders,'C');
        array_push($percents,25);

        foreach ($_aspectExport as $key => $value) {
            if($value=="1" && $key != 'courses'){
                array_push($headers, get_string($keysTag[$key],'report_evaluation_quality'));
                array_push($alignHeaders,'C');
                $countColumn++;
            }
        }

        $percent = 4;
        if($countColumn!=0)
            $percent = 75/$countColumn;

        foreach ($_aspectExport as $key => $value) {
            if($value=="1" && $key != 'courses'){
                array_push($percents, $percent);
            }
        }

       $answer = $this->generateRowsCourses($_categoryid,$_courseid,$_aspectExport);

       $rows = $answer[0];
       $alignRows = $answer[1]; 
 
       $html=$this->buildTableHtml($headers,$rows,$percents,$alignHeaders,$alignRows);

       return $html;
    }*/
}
