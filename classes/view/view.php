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


class view {
	

	protected $m_action;
    protected $m_view;

    function __construct($_action,$_view){
        $this->m_action = $_action;
        $this->m_view = $_view;
    }

	public function view(){
		global $OUTPUT, $CFG;

        $nextVersion = get_string('courserequeststomanagers_next_version', 'tool_courserequeststomanagers');
        print $OUTPUT->box($nextVersion);

        $img = \html_writer::start_tag('img', 
            array('src' => $CFG->wwwroot.'/admin/tool/courserequeststomanagers/pix/next_version.png',
              'style' => 'width:95%;'));

        print $OUTPUT->box($img);
        print $OUTPUT->single_button(new \moodle_url('index.php', array()),
                                get_string('go_back', 'tool_courserequeststomanagers'), 
                                'post', ['class' => 'evaluation_qualitybutton']);
	}

    public function generateRowsReport(){

        global $OUTPUT, $CFG, $DB;

        $rows = array();
        $pending = $DB->get_records('course_request');

        foreach ($pending as $course) {

            
            
            $course = new \course_request($course);

            // Check here for shortname collisions and warn about them.
            $course->check_shortname_collision();

            if (!$course->can_approve()) {
                continue;
            }
            $category = $course->get_category();
            // Fullname of the user who requested the course (with link to profile if current user can view it).
            $requesterfullname = $OUTPUT->user_picture($course->get_requester(), [
                'includefullname' => true,
                'link' => user_can_view_profile($course->get_requester()),
            ]);
            $managers = \tool_courserequeststomanagers\core\category::getStrManagers($category->id,'<br>');
            $row = array();
            $row[] = $requesterfullname;
            $row[] = format_string($course->shortname);
            $row[] = format_string($course->fullname);
           // $row[] = format_text($course->summary, $course->summaryformat);
            $row[] = $category->get_formatted_name();
            $row[] = format_string($course->reason);
            $row[] = $managers;
    //     $row[] = $OUTPUT->single_button(new moodle_url($baseurl, array('approve' => $course->id, 'sesskey' => sesskey())), get_string('approve'), 'get') .
    //              $OUTPUT->single_button(new moodle_url($baseurl, array('reject' => $course->id)), get_string('rejectdots'), 'get');

            /// Add the row to the table.
             $rows[]= $row;
        }

        return $rows;
    }

    public function generateRowsReportFile(){

        global $OUTPUT, $CFG, $DB;

        $rows = array();
        $pending = $DB->get_records('course_request');

        foreach ($pending as $course) {
            $course = new \course_request($course);

            $course->check_shortname_collision();

            if (!$course->can_approve()) {
                continue;
            }
            $category = $course->get_category();

            $usser = $course->get_requester()->firstname.' '.$course->get_requester()->lastname.' ('.$course->get_requester()->email.')';
            
            $managers = \tool_courserequeststomanagers\core\category::getStrManagers($category->id,'<br>');
            $row = array();
            $row[] = $usser;
            $row[] = format_string($course->fullname);
            $row[] = $category->get_formatted_name();
            $row[] = format_string($course->reason);
            $row[] = $managers;
    
            $rows[] = $row;
        }

        return $rows;
    }

    public function generateRowsNotifications(){
        global $OUTPUT, $CFG, $DB;
        $rows = array();
        $pending = $DB->get_records('course_request');

        $site = \get_site();
        $nameSite = $site->fullname;
        $subject = get_string('course_request_notification','tool_courserequeststomanagers');

        foreach ($pending as $course){
            $course = new \course_request($course);
            if(!$course->can_approve()){ continue;}

            $usser = $course->get_requester()->firstname.' '.$course->get_requester()->lastname.' ('.$course->get_requester()->email.')';

            $textMessaje = get_string('message_email_1','tool_courserequeststomanagers').$usser;
            $textMessaje = $textMessaje.get_string('message_email_2','tool_courserequeststomanagers').$course->fullname;
            $textMessaje = $textMessaje.get_string('message_email_3','tool_courserequeststomanagers').format_string($course->reason);
            $textMessaje = $textMessaje.get_string('message_email_4','tool_courserequeststomanagers').$CFG->wwwroot.'/course/pending.php';

            $category = $course->get_category();
            $managers = \tool_courserequeststomanagers\core\category::getManagers($category->id);
            
            if( count($managers) >0){
                foreach($managers as $manager){
                    $row = array();
                    $managerStr = $manager->firstname.' '.$manager->lastname.' ('.$manager->email.')';
                    $toUser = $this->generate_email_user($manager);
                    $fromUser = $this->generate_email_user(null,$CFG->noreplyaddress,$nameSite); 
                    
                    $result = \email_to_user($toUser, $fromUser, $subject, $textMessaje, $textMessaje, null, null, true);
                
                    $uicon = $OUTPUT->pix_icon('i/grade_incorrect', get_string('notification_not_sent','tool_courserequeststomanagers'));
                    $description = get_string('description_1','tool_courserequeststomanagers').$managerStr;
                    $description = $description.get_string('description_2','tool_courserequeststomanagers').$usser;
                    $description = $description.get_string('description_3','tool_courserequeststomanagers').$course->fullname;
                    $description = $description.get_string('description_4','tool_courserequeststomanagers');
                    

                    if ($result==true) {
                        $uicon = $OUTPUT->pix_icon('i/grade_correct', get_string('notification_sent','tool_courserequeststomanagers'));
                    }

                    $row[] = $uicon;
                    $row[] = $description;
                     
                    $rows[]= $row;
                }
            }else{

                $admins = \get_admins();
                foreach($admins as $admin){
                
                    $row = array();
                    $toUser = $this->generate_email_user($admin);
                    $fromUser = $this->generate_email_user(null,$CFG->noreplyaddress,$nameSite);

                    $textMessaje = get_string('message_email_admin_1', 'tool_courserequeststomanagers').$usser;
                    $textMessaje = $textMessaje.get_string('message_email_admin_2','tool_courserequeststomanagers');
                    $textMessaje = $textMessaje.$course->fullname.get_string('message_email_admin_3','tool_courserequeststomanagers');
                    $textMessaje = $textMessaje.$CFG->wwwroot.'/course/pending.php';

                    $result = \email_to_user($toUser, $fromUser, $subject, $textMessaje, $textMessaje, null, null, true);
                
                    $uicon = $OUTPUT->pix_icon('i/grade_incorrect', get_string('notification_not_sent', 'tool_courserequeststomanagers'));
                    $description = get_string('description_admin_1','tool_courserequeststomanagers').$usser;
                    $description = $description.get_string('description_admin_2','tool_courserequeststomanagers').$course->fullname;
                    $description = $description.get_string('description_admin_3','tool_courserequeststomanagers'); 

                    if ($result==true) {
                        $uicon = $OUTPUT->pix_icon('i/grade_correct', get_string('notification_sent', 'tool_courserequeststomanagers'));
                    }

                    $row[] = $uicon;
                    $row[] = $description;
                }
                $rows[]= $row;
            }
                
        }
        return $rows;        
    }

    function generate_email_user($user=null,$email='',$name='',$id=-99) {
        $emailuser = new \stdClass();
        if($user !=null){
            $emailuser->email = $user->email;
            $emailuser->firstname = $user->firstname;
            $emailuser->lastname = $user->lastname;
            $emailuser->username = \explode("@",$user->email);
            $emailuser->maildisplay = true;
            $emailuser->mailformat = 1;
            $emailuser->id = $user->id;
            $emailuser->firstnamephonetic = $user->firstnamephonetic;
            $emailuser->lastnamephonetic = $user->lastnamephonetic;
            $emailuser->middlename = $user->middlename;
            $emailuser->alternatename = $user->alternatename;
        }else{
            $emailuser->email = $email;
            $emailuser->firstname = $name;
            $emailuser->lastname = '';
            $emailuser->username = '';
            $emailuser->maildisplay = true;
            $emailuser->mailformat = 1;
            $emailuser->id = $id;
            $emailuser->firstnamephonetic = '';
            $emailuser->lastnamephonetic = '';
            $emailuser->middlename = '';
            $emailuser->alternatename = '';
        }
        
        return $emailuser;
    }

}


