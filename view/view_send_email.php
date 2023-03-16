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


class ViewSendEmail extends View {

    function __construct($_action,$_view){
        parent::__construct($_action,$_view);
    }
	
	public function view(){

        $this->m_view = 2;
        switch ($this->m_action) {
			case 1: $this->sendEmailCategoryManagers($this->m_action,$this->m_view);break;
            default: parent::view(); break;
		}
	}

    public function sendEmailCategoryManagers($_action,$_view){
        global $OUTPUT, $CFG, $DB;
        
        $baseurl = $CFG->wwwroot . '/course/pending.php';

        $pending = $DB->get_records('course_request');
        if (empty($pending)) {
            echo $OUTPUT->heading(get_string('nopendingcourses'));
        } else {
            echo $OUTPUT->heading(get_string('notification_result','tool_courserequeststomanagers'));
            
            /// Build a table of all the requests.
            $table = new html_table();
            $table->attributes['class'] = 'pendingcourserequests generaltable';
            $table->align = array('center', 'left');
            $table->size = array('10%', '90%');
            $table->head = array(get_string('status','tool_courserequeststomanagers'), get_string('description','tool_courserequeststomanagers'));

            $table->data = $this->generateRowsReportSendEmail(); 

            /// Display the table.
            echo html_writer::table($table);
            
            $html= html_writer::empty_tag('hr');
            $html.= html_writer::start_tag('div', array('align' => 'left','class'=>'divButtonsExportChart'));
            
            $html.= $OUTPUT->single_button(new moodle_url('index.php', 
                        array('view'=>0,
                              'action' => 0,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                        get_string('go_back', 'tool_courserequeststomanagers'), 
                        'post', ['class' => 'buttonsExportChart']);

            $html.= html_writer::end_tag('div');

            print $OUTPUT->box($html);
            if (!empty($collision)) {
                print_string('shortnamecollisionwarning');
            }
        }
    }
}