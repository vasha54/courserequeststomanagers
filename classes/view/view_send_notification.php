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

class view_send_notification extends view {

    function __construct($_action,$_view){
        parent::__construct($_action,$_view);
    }
	
	public function view(){

        $this->m_view = 0;
        switch ($this->m_action) {
			case 0: $this->showTableStatusSendNotificacion($this->m_action,$this->m_view); break;
            default: parent::view(); break;
		}
	}

    private function showTableStatusSendNotificacion($_action,$_view){
        global $OUTPUT, $CFG, $DB;

        $pending = $DB->get_records('course_request');

        if (empty($pending)) {
            echo $OUTPUT->heading(get_string('nopendingcourses'));
            $html= \html_writer::empty_tag('hr');
            print $OUTPUT->box($html);
            print $OUTPUT->single_button(new \moodle_url('index.php', array()),
                                get_string('go_back', 'tool_courserequeststomanagers'), 
                                'post', ['class' => 'evaluation_qualitybutton']);
        }else{
            echo $OUTPUT->heading(get_string('send_notification','tool_courserequeststomanagers'));

            $table = new \html_table();
            $table->attributes['class'] = 'pendingcourserequests generaltable';
            $table->align = array('center','center','left','left');
            $table->head = array(get_string('notification_status_email','tool_courserequeststomanagers'),
                                 get_string('notification_status_system','tool_courserequeststomanagers'),
                                 get_string('notification_description_email','tool_courserequeststomanagers'),
                                 get_string('notification_description_system','tool_courserequeststomanagers'));
            $table->size = array('12%','12%','38%','38%');
            $handler_notification = new \tool_courserequeststomanagers\core\notification_handler(); 
            $table->data = $handler_notification->generateNotifications(); 
            

            for ($i=0; $i < count($table->data) ; $i++) { 
                if($table->data[$i][0]=true){
                    $table->data[$i][0]= $OUTPUT->pix_icon('i/grade_correct', get_string('notification_sent', 'tool_courserequeststomanagers'));
                }else{
                    $table->data[$i][0]= $OUTPUT->pix_icon('i/grade_incorrect', get_string('notification_not_sent', 'tool_courserequeststomanagers'));
                }

                if($table->data[$i][1]==true){
                    $table->data[$i][1]= $OUTPUT->pix_icon('i/grade_correct', get_string('notification_sent', 'tool_courserequeststomanagers'));
                }else{
                    $table->data[$i][1]= $OUTPUT->pix_icon('i/grade_incorrect', get_string('notification_not_sent', 'tool_courserequeststomanagers'));
                }

            }

            /// Display the table.
            echo \html_writer::table($table);

            $html= \html_writer::empty_tag('hr');
            $html.= \html_writer::start_tag('div', array('align' => 'center','class'=>'divButtonsExportChart'));
            
            $html.= \html_writer::link(new \moodle_url('index.php', 
                        array('view'=>0,
                              'action' => 0,
                              'cu_action' =>$_action,
                              'cu_vreport'=>$_view)),
                    get_string('go_back', 'tool_courserequeststomanagers'),
                    array('class' => 'btn btn-secondary buttonsExportChart'));

            $html.= \html_writer::end_tag('div');

            print $OUTPUT->box($html);

            /// Message about name collisions, if necessary.
            if (!empty($collision)) {
                print_string('shortnamecollisionwarning');
            }
        }
    }

}