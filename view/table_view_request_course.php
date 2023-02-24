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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot.'/admin/tool/courserequeststomanagers/view/view.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');


class TableViewRequestCourse extends View {

    function __construct($_action,$_view){
        parent::__construct($_action,$_view);
    }
	
	public function view(){

        $this->m_view = 0;
        switch ($this->m_action) {
			case 0: $this->showTableRequestCourse($this->m_action,$this->m_view);break;
            default: parent::view(); break;
		}
	}

	private function showTableRequestCourse($_action,$_view){
        global $OUTPUT, $CFG, $DB;

        $baseurl = $CFG->wwwroot . '/course/pending.php';

        $pending = $DB->get_records('course_request');
        if (empty($pending)) {
            echo $OUTPUT->heading(get_string('nopendingcourses'));
        } else {
            echo $OUTPUT->heading(get_string('coursespending'));
            $role = $DB->get_record('role', array('id' => $CFG->creatornewroleid), '*', MUST_EXIST);
           // echo $OUTPUT->notification(get_string('courserequestwarning', 'core', role_get_name($role)), 'notifyproblem');

            /// Build a table of all the requests.
            $table = new html_table();
            $table->attributes['class'] = 'pendingcourserequests generaltable';
            $table->align = array('center', 'center', 'center', /*'center',*/ 'center', 'center'/*, 'center'*/);
            $table->head = array(get_string('requestedby'), get_string('shortnamecourse'), get_string('fullnamecourse'),
                                /* get_string('summary'),*/ get_string('category'), get_string('requestreason'), 
                                 get_string('responsible_manager','tool_courserequeststomanagers')/*,get_string('action')*/);

            $table->data = $this->generateRowsReport(); 

            /// Display the table.
            echo html_writer::table($table);

            $html= html_writer::empty_tag('hr');
            $html.= html_writer::start_tag('div', array('align' => 'center','class'=>'divButtonsExportChart'));
            
            // $html.= html_writer::link(new moodle_url('index.php', 
            //             array('view'=>100,
            //                   'action' => 0,
            //                   'cu_action' =>$_action,
            //                   'cu_vreport'=>$_view)),
            //         get_string('export_data_excel', 'tool_courserequeststomanagers'),
            //         array('class' => 'buttonsExportChart','target'=>'blank'));

            $html.= html_writer::link(new moodle_url('index.php', 
                        array('view'=>100,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                    get_string('export_data_pdf', 'tool_courserequeststomanagers'),
                    array('class' => 'btn btn-secondary buttonsExportChart','target'=>'blank'));

            $html.= $OUTPUT->single_button(new moodle_url('index.php', 
                        array('view'=>2,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('send_notification', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= html_writer::end_tag('div');

            print $OUTPUT->box($html);

            /// Message about name collisions, if necessary.
            if (!empty($collision)) {
                print_string('shortnamecollisionwarning');
            }
        }


		// $cu_vreport = optional_param('cu_vreport',0, PARAM_INT);
        // $cu_action  = optional_param('cu_action',0, PARAM_INT);
        // $categoryidBack = optional_param('cu_categoryid', 0, PARAM_INT);
        // $courseidBack   = optional_param('cu_courseid', 0, PARAM_INT);
        // $pageBack       = optional_param('cu_page', 0, PARAM_INT);

        // $coursecat = core_course_category::get(is_object($_categoryid) ? $categoryid->id : $_categoryid);
        // $this->coursecat = $coursecat;
        // $coursecount = $coursecat->get_courses_count();

        // global $OUTPUT;

        // $desc = get_string('tag_button_cc', 'report_evaluation_quality').':';
        // print $OUTPUT->heading($desc).'<br>';

        // $output = "";
        // $output .= html_writer::start_tag('div', array('align' => 'center'));
        // if (empty($_categoryid)) {
        //     $select = new single_select(new moodle_url('/report/evaluation_quality/index.php',array('view_report'=>8)),'categoryid', core_course_category::make_categories_list('report/evaluation_quality:view'), $coursecat->id, get_string('selectacategory'), null);
        // } else {
        //     $select = new single_select(new moodle_url('/report/evaluation_quality/index.php',array('view_report'=>8)), 'categoryid', core_course_category::make_categories_list('report/evaluation_quality:view'), $coursecat->id, null, null);
        // }
        
        // $select->set_label(get_string('categories') . ':');
        // $output .= $OUTPUT->render($select);
        // $output .= html_writer::end_tag('div');
        // // echo html_writer::tag('h4', get_string('pluginname', 'report_evaluation_quality'));
        // echo $output;


        // //Construye el formulario principal
        // echo html_writer::start_tag('form', array('action' => '', 'method' => 'POST'));
        // echo html_writer::start_div('');

        // if ($coursecat->has_children()) {
        //     echo $this->showCourseOnlineCategories($_categoryid,$_courseid,$_action,$_view);
        // }

        // if ($coursecount) {

        //     //if($coursecat->has_children()) 
        //         echo html_writer::tag('br',null);

        //     echo $this->showCourseOnlineCourses($_categoryid,$_courseid,$_action,$_view);
        // }
        
        // if (!$coursecat->has_children() && !$coursecat->has_courses() ) {
        //     $error = html_writer::empty_tag('br');
        //     $error.= html_writer::tag('span', 
        //             get_string('emptycat', 'report_evaluation_quality') . '. ' . get_string('selectacategory'), 
        //             array('class' => 'text-error'));
        //     $error.= html_writer::empty_tag('hr');
        //     echo $error;
        // }

        // if ( ($coursecat->has_children() || $coursecat->has_courses())){
              
        //     $html = "";
              
        //     $html.= html_writer::empty_tag('hr');
        //     $html.= html_writer::start_tag('div', 
        //         array('align' => 'center','class'=>'divButtonsExportChart'));
            
            

        //     $html.= $OUTPUT->single_button(new moodle_url('index.php', 
        //                 array('view_report'=>100,
        //                       'categoryid' => $_categoryid,
        //                       'courseid'=> $_courseid,
        //                       'action' => 17,
        //                       'cu_action' =>$_action,
        //                       'cu_vreport'=>$_view)),
        //                 get_string('export_data_excel', 'report_evaluation_quality'), 
        //                 'post', ['class' => 'buttonsExportChart']);

        //     $html.= $OUTPUT->single_button(new moodle_url('index.php', 
        //                 array('view_report'=>100,
        //                       'categoryid' => $_categoryid,
        //                       'courseid'=> $_courseid,
        //                       'action' => 16,
        //                       'cu_action' =>$_action,
        //                       'cu_vreport'=>$_view)),
        //                 get_string('export_data_pdf', 'report_evaluation_quality'), 
        //                 'post', ['class' => 'buttonsExportChart']);

        //     $html.= html_writer::end_tag('div');

        //     print $OUTPUT->box($html);
        // }

        // echo html_writer::end_div('');
        // echo html_writer::end_tag('form');

        // echo html_writer::empty_tag('br');

        // if( intval($coursecat->depth)==0){
        //     $categoryidBack =0;
        //     $cu_vreport = 0;
        // }
        // else{
        //     $categoryidBack =intval($coursecat->parent) ;
        //     $cu_vreport = 8;
        //     $cu_action = 0;     
        // }

        // print $OUTPUT->single_button(new moodle_url('index.php', 
        //                 array('view_report'=>$cu_vreport,
        //                       'action'=>$cu_action,
        //                       'categoryid'=>$categoryidBack)),
        //                 get_string('go_back', 'report_evaluation_quality'), 
        //                 'post', ['class' => 'evaluation_qualitybutton']); 
	}

    
}