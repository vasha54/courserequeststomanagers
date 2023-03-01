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

require_once('handle_export.php');

require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');

require_once($CFG->dirroot . '/admin/tool/courserequeststomanagers/classes/export/php-excel.class.php');



class HandleExportEXCEL extends HandleExport  {

	 

	function __construct ($_action,$_view){
		parent::__construct($_action,$_view);
	}

    public  function generateReport($_rows){

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
        $downloadfilename = clean_filename($filename.'-'.$strcoursename.'-'.$stage).'.xls';

        $data = array();

        $rows=Course::getRowsReportThisStage($this->m_courseid,$_stage);

        $pivot = 0;

        $headcols = array(
            get_string('status'),
            get_string('indicator', 'report_evaluation_quality'),
            get_string('description_indicator', 'report_evaluation_quality')
        );

        $data[$pivot] = $headcols;
        $pivot++;

        for($i =0 ;$i<count($rows);$i++){
            $data[$pivot]=$rows[$i];
            $pivot++;
        }

        
        $xls = new Excel_XML;
        $xls->addWorksheet($strcoursename, $data);
        return $xls->sendWorkbook($downloadfilename);

	}

	public function proccessFormExport(){
        $coursecat = core_course_category::get(is_object($this->m_categoryid) ? $this->m_categoryid->id : $this->m_categoryid);
        $coursecount = $coursecat->get_courses_count();

        $data=array();
        $pivot =0;

        if ($coursecat->has_children()) {
            $headers = $this->generateHeadersCategoriesExcel($this->m_aspectExport);
            $data[$pivot] =$headers;
            $pivot++;
            $rows = $this->generateRowsCategories($this->m_categoryid,$this->m_courseid,
            										$this->m_aspectExport);

            $count = count($rows[0]);

            $cColumn = count($rows[0][$count-1]);

            for($i=0;$i<$count;$i++){
                for($j=0;$j<$cColumn;$j++){
                    $rows[0][$i][$j] = strip_tags($rows[0][$i][$j]);
                }
            }

            for ($i=0; $i < $count ; $i++) { 
                $data[$pivot] = $rows[0][$i];
                $pivot++;
            }
        }

        if ($coursecount) {

             $data[$pivot]=array();$pivot++;
             $data[$pivot]=array();$pivot++;

            $headers = $this->generateHeadersCourseExcel($this->m_aspectExport);
            $data[$pivot] =$headers;
            $pivot++;
            $rows = $this->generateRowsCourses($this->m_categoryid,$this->m_courseid,
            									$this->m_aspectExport);

            $count = count($rows[0]);

            $cColumn = count($rows[0][$count-1]);
            for($i=0;$i<$count;$i++){
                for($j=0;$j<$cColumn;$j++){
                    $rows[0][$i][$j] = strip_tags($rows[0][$i][$j]);
                }
            }

            for ($i=0; $i < $count ; $i++) { 
                $data[$pivot] = $rows[0][$i];
                $pivot++;
            }
        }

        $data[$pivot]=array();$pivot++;
        $data[$pivot]=array();$pivot++;

        $rows = $this->buildTableLegendExcel($this->m_aspectExport);

        for ($i=0; $i < count($rows) ; $i++) { 
            $data[$pivot] = $rows[$i];
            $pivot++;
        }

        $downloadfilename = clean_filename($this->m_aspectExport->titleReport.'.xls');
        $xls = new Excel_XML;
        $xls->addWorksheet($this->m_aspectExport->titleReport, $data);
        return $xls->sendWorkbook($downloadfilename); 
	}

	private function generateHeadersCategoriesExcel($_aspectExport){
        $headers = array();

        $keysTag = AspectExportForm::$keyFormsLabel;

        array_push($headers,get_string('category'));

        foreach ($_aspectExport as $key => $value) {
            if($value=="1"){
                array_push($headers, get_string($keysTag[$key],'report_evaluation_quality'));
            }
        }

        return $headers;
    }

    private function generateHeadersCourseExcel($_aspectExport){
        $headers = array();

        $keysTag = AspectExportForm::$keyFormsLabel;
        
        array_push($headers,get_string('category'));

        foreach ($_aspectExport as $key => $value) {
            if($value=="1" && $key != 'courses'){
                array_push($headers, get_string($keysTag[$key],'report_evaluation_quality'));
            }
        }

        return $headers;
    }

    private function buildTableLegendExcel($_aspectExport){
        $headers = array(get_string('acronym','report_evaluation_quality'),
                         get_string('meaning','report_evaluation_quality'));
        $rows = array();

        array_push($rows,$headers);

        $keysTag = AspectExportForm::$keyFormsLabel;

        foreach ($_aspectExport as $key => $value) {
            if($value=="1"){
                $row=array(get_string($keysTag[$key],'report_evaluation_quality'),
                           get_string($key,'report_evaluation_quality'));
                array_push($rows,$row);
            }
        }
        return $rows;
    }*/
}