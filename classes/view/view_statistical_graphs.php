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


namespace tool_courserequeststomanagers\view;
defined('MOODLE_INTERNAL') || die();

class view_statistical_graphs extends view {

    function __construct($_action,$_view){
        parent::__construct($_action,$_view);
    }
	
	public function view(){

        $this->m_view = 2;
        switch ($this->m_action) {

            case 0: $this->showStatisticalGraphs($this->m_action,$this->m_view);break;
			default: parent::view(); break;
		}
	}

	private function showStatisticalGraphs($_action,$_view){
        global $OUTPUT;

        $tab = optional_param('tab', 1, PARAM_INT);
        $tabs = [];

        $urlByMonths = new \moodle_url('index.php', 
                                    array('view'=>2,
                                        'action' => 0,
                                        'cu_action' =>$_action,
                                        'cu_vreport'=>$_view,
                                        'tab'=>2));

        $urlByYear = new \moodle_url('index.php', 
                                        array('view'=>2,
                                            'action' => 0,
                                            'cu_action' =>$_action,
                                            'cu_vreport'=>$_view,
                                            'tab'=>1));

        $tabs[] = new \tabobject(1,$urlByYear , 
                                get_string('course_accept_by_years', 'tool_courserequeststomanagers'));
        $tabs[] = new \tabobject(2, $urlByMonths, 
                                get_string('course_accept_by_months', 'tool_courserequeststomanagers'));
        
        echo $OUTPUT->tabtree($tabs, $tab);
        if ($tab == 1) {
            $this->showStatisticalByYears($_action, $_view);
        } else {
            $this->showStatisticalByMonths($_action, $_view);
        }

        $html= \html_writer::link(new \moodle_url('index.php', 
                        array('view'=>0,
                        'action' => 0,
                        'cu_action' =>0,
                        'cu_vreport'=>0)),
                    get_string('go_back', 'tool_courserequeststomanagers'),
                    array('class' => 'btn btn-secondary buttonsExportChart'));
        print $OUTPUT->box($html);

	}

    private function showStatisticalByYears($_action, $_view){
        global $OUTPUT, $CFG, $DB;

        $categoryid = optional_param('categoryid', 0, PARAM_INT);
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $cu_vreport = optional_param('cu_vreport',0, PARAM_INT);
        $cu_action  = optional_param('cu_action',0, PARAM_INT);
        $categoryidBack = optional_param('cu_categoryid', 0, PARAM_INT);
        $courseidBack   = optional_param('cu_courseid', 0, PARAM_INT);

        $coursecat = \core_course_category::get(is_object($categoryid) ? $categoryid->id : $categoryid);
        $coursecount = $coursecat->get_courses_count();

        $output = "";
        $output .= \html_writer::start_tag('div', array('align' => 'center'));
        if (empty($_categoryid)) {
            $select = new \single_select(new \moodle_url('index.php',
                                         array('view'=>2,
                                         'action' => 0,
                                         'cu_action' =>$_action,
                                         'cu_vreport'=>$_view,
                                         'tab'=>1)),
                                         'categoryid', 
                                         \core_course_category::make_categories_list('tool/courserequeststomanagers:view'), $coursecat->id, get_string('selectacategory'), null);
        } else {
            $select = new \single_select(new \moodle_url('index.php',
                                        array('view'=>2,
                                        'action' => 0,
                                        'cu_action' =>$_action,
                                        'cu_vreport'=>$_view,
                                        'tab'=>1)), 'categoryid', \core_course_category::make_categories_list('tool/courserequeststomanagers:view'), $coursecat->id, null, null);
        }
        
        $select->set_label(get_string('categories') . ':');
        $output .= $OUTPUT->render($select);
        $output .= \html_writer::end_tag('div');
        echo $output;

        $years = \tool_courserequeststomanagers\core\category::getCountCourseByYear($categoryid);

        if( count($years) > 0 ){
            $countsCourses = array();
            $yearsLabel = array();

            
            foreach ($years as $y => $value) {
                $countsCourses[] = $value;
                $yearsLabel[] = $y;
                
            }

            
            $courses_accept = new \core\chart_series(get_string('course_accept', 'tool_courserequeststomanagers'), $countsCourses);
            $chart = new \core\chart_bar();
            $chart->set_horizontal(true); 
            $chart->add_series($courses_accept);
            $chart->set_labels($yearsLabel);
           

            echo $OUTPUT->render($chart);
        }else{
            echo get_string('not_data_visualization', 'tool_courserequeststomanagers');
        }

        
    }

    private function showStatisticalByMonths($_action, $_view){
        global $OUTPUT, $CFG, $DB;

        $monthsStr = array('01' => get_string('juanary', 'tool_courserequeststomanagers'),
                           '02' => get_string('febrary', 'tool_courserequeststomanagers'),
                           '03' => get_string('march', 'tool_courserequeststomanagers'),
                           '04' => get_string('april', 'tool_courserequeststomanagers'),
                           '05' => get_string('may', 'tool_courserequeststomanagers'),
                           '06' => get_string('june', 'tool_courserequeststomanagers'),
                           '07' => get_string('july', 'tool_courserequeststomanagers'),
                           '08' => get_string('august', 'tool_courserequeststomanagers'),
                           '09' => get_string('september', 'tool_courserequeststomanagers'),
                           '10' => get_string('octuber', 'tool_courserequeststomanagers'),
                           '11' => get_string('november', 'tool_courserequeststomanagers'),
                           '12' => get_string('december', 'tool_courserequeststomanagers'));

        $categoryid = optional_param('categoryid', 0, PARAM_INT);
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $cu_vreport = optional_param('cu_vreport',0, PARAM_INT);
        $cu_action  = optional_param('cu_action',0, PARAM_INT);
        $categoryidBack = optional_param('cu_categoryid', 0, PARAM_INT);
        $courseidBack   = optional_param('cu_courseid', 0, PARAM_INT);

        $coursecat = \core_course_category::get(is_object($categoryid) ? $categoryid->id : $categoryid);
        $coursecount = $coursecat->get_courses_count();

        $output = "";
        $output .= \html_writer::start_tag('div', array('align' => 'center'));
        if (empty($_categoryid)) {
            $select = new \single_select(new \moodle_url('index.php',
                                         array('view'=>2,
                                         'action' => 0,
                                         'cu_action' =>$_action,
                                         'cu_vreport'=>$_view,
                                         'tab'=>2)),
                                         'categoryid', 
                                         \core_course_category::make_categories_list('tool/courserequeststomanagers:view'), $coursecat->id, get_string('selectacategory'), null);
        } else {
            $select = new \single_select(new \moodle_url('index.php',
                                        array('view'=>2,
                                        'action' => 0,
                                        'cu_action' =>$_action,
                                        'cu_vreport'=>$_view,
                                        'tab'=>2)), 'categoryid', \core_course_category::make_categories_list('tool/courserequeststomanagers:view'), $coursecat->id, null, null);
        }
        
        $select->set_label(get_string('categories') . ':');
        $output .= $OUTPUT->render($select);
        $output .= \html_writer::end_tag('div');
        echo $output;

        $months = \tool_courserequeststomanagers\core\category::getCountCourseByMonth($categoryid);


        if (count($months) > 0){
            $countsCourses = array();
            $monthsLabel = array();

            foreach ($months as $y => $value) {
                $countsCourses[] = $value;
                $monthsLabel[] = $monthsStr[$y];
            }

            $courses_accept = new \core\chart_series(get_string('course_accept', 'tool_courserequeststomanagers'), $countsCourses);
            $chart = new \core\chart_bar();
            $chart->set_horizontal(true); 
            $chart->add_series($courses_accept);
            $chart->set_labels($monthsLabel);
            echo $OUTPUT->render($chart);

        }else{
            echo get_string('not_data_visualization', 'tool_courserequeststomanagers');
        }

        
        
    }
    
}