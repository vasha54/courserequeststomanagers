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

class table_view_request_course extends view {

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
            $html= \html_writer::empty_tag('hr');
            $html.= \html_writer::start_tag('div', array('align' => 'center','class'=>'divButtonsExportChart'));
            
            $html.= $OUTPUT->single_button(new \moodle_url('index.php', 
                        array('view'=>2,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('statistical_graphs', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= $OUTPUT->single_button(new \moodle_url('index.php', 
                        array('view'=>3,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('statistical_reports', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= \html_writer::end_tag('div');

            print $OUTPUT->box($html);
        } else {
            echo $OUTPUT->heading(get_string('coursespending'));
            $role = $DB->get_record('role', array('id' => $CFG->creatornewroleid), '*', MUST_EXIST);
           // echo $OUTPUT->notification(get_string('courserequestwarning', 'core', role_get_name($role)), 'notifyproblem');

            /// Build a table of all the requests.
            $table = new \html_table();
            $table->attributes['class'] = 'pendingcourserequests generaltable';
            $table->align = array('center', 'center', 'center', /*'center',*/ 'center', 'center'/*, 'center'*/);
            $table->head = array(get_string('requestedby'), get_string('shortnamecourse'), get_string('fullnamecourse'),
                                /* get_string('summary'),*/ get_string('category'), get_string('requestreason'), 
                                 get_string('responsible_manager','tool_courserequeststomanagers')/*,get_string('action')*/);

            $table->data = $this->generateRowsReport(); 

            /// Display the table.
            echo \html_writer::table($table);

            $html= \html_writer::empty_tag('hr');
            $html.= \html_writer::start_tag('div', array('align' => 'center','class'=>'divButtonsExportChart'));
            
            $html.= \html_writer::link(new \moodle_url('index.php', 
                        array('view'=>100,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                    get_string('export_data_pdf', 'tool_courserequeststomanagers'),
                    array('class' => 'btn btn-secondary buttonsExportChart','target'=>'blank'));

            $html.= $OUTPUT->single_button(new \moodle_url('index.php', 
                        array('view'=>1,
                              'action' => 0,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('send_notification', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= $OUTPUT->single_button(new \moodle_url('index.php', 
                        array('view'=>2,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('statistical_graphs', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= $OUTPUT->single_button(new \moodle_url('index.php', 
                        array('view'=>3,
                              'action' => 1,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('statistical_reports', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= \html_writer::end_tag('div');

            print $OUTPUT->box($html);

            /// Message about name collisions, if necessary.
            if (!empty($collision)) {
                print_string('shortnamecollisionwarning');
            }
        }


		
	}

    
}