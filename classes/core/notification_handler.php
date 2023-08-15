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


namespace tool_courserequeststomanagers\core;
defined('MOODLE_INTERNAL') || die();

class notification_handler {
    
    function __construct(){
    
    }

    public function generateNotificationEmailManager($_manager,$_course,$_subject,
                                                     $_nameSite){
        global $CFG;

        $result = array(false,'');

        $usser = $_course->get_requester()->firstname.' '.
                 $_course->get_requester()->lastname.' ('.
                 $_course->get_requester()->email.')';

        $textMessaje = get_string('message_email_1','tool_courserequeststomanagers').
                       $usser;
        $textMessaje = $textMessaje.
                       get_string('message_email_2','tool_courserequeststomanagers').
                       $_course->fullname;
        $textMessaje = $textMessaje.
                       get_string('message_email_3','tool_courserequeststomanagers').
                       format_string($_course->reason);
        $textMessaje = $textMessaje.
                       get_string('message_email_4','tool_courserequeststomanagers').
                       $CFG->wwwroot.'/course/pending.php';

        $toUser = $this->generate_email_user($_manager);
        $fromUser = $this->generate_email_user(null,$CFG->noreplyaddress,$_nameSite);

        $managerStr = $_manager->firstname.' '.$_manager->lastname.' ('.$_manager->email.')';

        $send = \email_to_user($toUser, $fromUser, $_subject, $textMessaje, 
                               $textMessaje, null, null, true);
                
        $description = get_string('description_email_1','tool_courserequeststomanagers').
                       $managerStr;
        $description = $description.
                       get_string('description_email_2','tool_courserequeststomanagers').
                       $usser;
        $description = $description.get_string('description_email_3','tool_courserequeststomanagers').    
                       $course->fullname;
        $description = $description.get_string('description_email_4','tool_courserequeststomanagers');
                    
        $result[0] = $send;
        $result[1] = $description;

        return $result ;
    }

    public function generateNotificationEmailAdmin($_admin,$_course,$_subject,
                                                     $_nameSite){
        global $CFG;

        $result = array(false,'');

        $usser = $_course->get_requester()->firstname.' '.
                 $_course->get_requester()->lastname.' ('.
                 $_course->get_requester()->email.')';

        $toUser = $this->generate_email_user($_admin);
        $fromUser = $this->generate_email_user(null,$CFG->noreplyaddress,
                                               $_nameSite);

        $textMessaje = get_string('message_email_admin_1', 'tool_courserequeststomanagers').
                       $usser;
        $textMessaje = $textMessaje.
                       get_string('message_email_admin_2','tool_courserequeststomanagers');
        $textMessaje = $textMessaje.$_course->fullname.
                       get_string('message_email_admin_3','tool_courserequeststomanagers');
        $textMessaje = $textMessaje.$CFG->wwwroot.'/course/pending.php';

        $send = \email_to_user($toUser, $fromUser, $_subject, $textMessaje, 
                                 $textMessaje, null, null, true);
                
        $description = get_string('description_admin_1','tool_courserequeststomanagers').
                       $usser;
        $description = $description.
                       get_string('description_admin_2','tool_courserequeststomanagers').
                       $_course->fullname;
        $description = $description.
                       get_string('description_admin_3','tool_courserequeststomanagers'); 

        $result[0] = $send;
        $result[1] = $description;                                                

        return $result ;
    }


    public function generateNotificationSystemManager($_manager,$_course,
                                                      $_subject){
        $result = array(false,'');
        global $CFG;

        $usser = $_course->get_requester()->firstname.' '.
                 $_course->get_requester()->lastname.' ('.
                 $_course->get_requester()->email.')';

        $textMessaje = get_string('message_email_1','tool_courserequeststomanagers').
                       $usser;
        $textMessaje = $textMessaje.
                       get_string('message_email_2','tool_courserequeststomanagers').
                       $_course->fullname;
        $textMessaje = $textMessaje.
                       get_string('message_email_3','tool_courserequeststomanagers').
                       format_string($_course->reason);

        $message = new \core\message\message();
        $message->component = 'tool_courserequeststomanagers'; // Your plugin's name
        $message->name = 'notification_request_course'; // Your notification name from message.php
        $message->userfrom = \core_user::get_noreply_user();
        $message->userto = $_manager->id;
        $message->subject = $_subject;
        $message->fullmessage = $textMessaje;
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = '<p>'.$textMessaje.'.</p>';
        $message->smallmessage = '';
        $message->notification = 1; 
        $message->contexturl = $CFG->wwwroot.'/course/pending.php'; 
        $message->contexturlname = get_string('link','tool_courserequeststomanagers'); 
        $send = message_send($message);

        $managerStr = $_manager->firstname.' '.$_manager->lastname.' ('.$_manager->email.')';

        $description = get_string('description_system_1','tool_courserequeststomanagers').
                       $managerStr;
        $description = $description.
                       get_string('description_system_2','tool_courserequeststomanagers').
                       $usser;
        $description = $description.get_string('description_system_3','tool_courserequeststomanagers').    
                       $_course->fullname;
        $description = $description.get_string('description_system_4','tool_courserequeststomanagers');

        $result[0] = $send;
        $result[1] = $description;
        return $result ;
    }

    public function generateNotificationSystemAdmin($_admin,$_course,$_subject){
        $result = array(false,'');
        global $CFG;

        $usser = $_course->get_requester()->firstname.' '.
                 $_course->get_requester()->lastname.' ('.
                 $_course->get_requester()->email.')';

        $textMessaje = get_string('message_email_admin_1', 'tool_courserequeststomanagers').
                       $usser;
        $textMessaje = $textMessaje.
                       get_string('message_email_admin_2','tool_courserequeststomanagers');
        $textMessaje = $textMessaje.$_course->fullname.
                       get_string('message_email_admin_3','tool_courserequeststomanagers');

        $message = new \core\message\message();
        $message->component = 'tool_courserequeststomanagers'; // Your plugin's name
        $message->name = 'notification_request_course'; // Your notification name from message.php
        $message->userfrom = \core_user::get_noreply_user();
        $message->userto = $_admin->id;
        $message->subject = $_subject;
        $message->fullmessage = $textMessaje;
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = '<p>'.$textMessaje.'.</p>';
        $message->smallmessage = '';
        $message->notification = 1; 
        $message->contexturl = $CFG->wwwroot.'/course/pending.php'; 
        $message->contexturlname = get_string('link','tool_courserequeststomanagers'); 
        $send = message_send($message);

        $description = get_string('description_admin_1','tool_courserequeststomanagers').
                       $usser;
        $description = $description.
                       get_string('description_admin_2','tool_courserequeststomanagers').
                       $_course->fullname;
        $description = $description.
                       get_string('description_admin_3','tool_courserequeststomanagers');

        $result[0] = $send;
        $result[1] = $description;
        return $result ;
    }


    public function generateNotifications(){
        global $CFG, $DB;
        $rows = array();

        $pending=$DB->get_records('course_request'); 

        $site = \get_site();
        $nameSite = $site->fullname;
        $subject = get_string('course_request_notification','tool_courserequeststomanagers');
        

        foreach ($pending as $course){
           $course = new \course_request($course); 
           if(!$course->can_approve()){ continue;}

           $category = $course->get_category();
           $managers = \tool_courserequeststomanagers\core\category::getManagers($category->id);

           if( count($managers) >0){
                foreach($managers as $manager){
                    $row = array (false,false,'','');
                    $resultEmail = $this->generateNotificationEmailManager($manager,
                                    $course,$subject,$nameSite);
                    $resultSystem = $this->generateNotificationSystemManager($manager,
                                    $course,$subject);
                    $row[0]=$resultEmail[0];
                    $row[2]=$resultEmail[1];
                    $row[1]=$resultSystem[0];
                    $row[3]=$resultSystem[1];
                    $rows[]= $row;
                }
           }else{
                $admins = \get_admins();
                foreach($admins as $admin){
                    $row = array (false,false,'','');
                    $resultEmail = $this->generateNotificationEmailAdmin($admin,
                                   $course,$subject,$nameSite);
                    $resultSystem = $this->generateNotificationSystemAdmin($admin,
                                    $course,$subject);
                    $row[0]=$resultEmail[0];
                    $row[2]=$resultEmail[1];
                    $row[1]=$resultSystem[0];
                    $row[3]=$resultSystem[1];
                    $rows[]= $row;
                }
           }
        }
        
        return $rows;
    }

    public function generateNotificationsSystem(){
        global $CFG, $DB;
        $rows = array();
        
        $pending=$DB->get_records('course_request'); 

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
                    $message = new \core\message\message();
                    $message->component = 'tool_courserequeststomanagers'; // Your plugin's name
                    $message->name = 'request_course'; // Your notification name from message.php
                    $message->userfrom = core_user::get_noreply_user();
                    $message->userto = $manager;
                    $message->subject = $subject;
                    $message->fullmessage = 'message body';
                    $message->fullmessageformat = FORMAT_HTML;
                    $message->fullmessagehtml = '<p>message body</p>';
                    $message->smallmessage = 'small message';
                    $message->notification = 1; 
                    $message->contexturl = $CFG->wwwroot.'/course/pending.php'; 
                    $message->contexturlname = 'Course list'; 
                    $result = message_send($message);

                    // $managerStr = $manager->firstname.' '.$manager->lastname.' ('.$manager->email.')';
                    // $toUser = $this->generate_email_user($manager);
                    // $fromUser = $this->generate_email_user(null,$CFG->noreplyaddress,$nameSite); 
                    
                    // $result = \email_to_user($toUser, $fromUser, $subject, $textMessaje, $textMessaje, null, null, true);
                
                    
                    
                    $row[] = $result;
                    $row[] = '';
                     
                    $rows[]= $row;
                }
            }else{

                $admins = \get_admins();
                foreach($admins as $admin){
                
                    $row = array();

                    $message = new \core\message\message();
                    $message->component = 'tool_courserequeststomanagers'; // Your plugin's name
                    $message->name = 'request_course'; // Your notification name from message.php
                    $message->userfrom = core_user::get_noreply_user();
                    $message->userto = $admin;
                    $message->subject = $subject;
                    $message->fullmessage = 'message body';
                    $message->fullmessageformat = FORMAT_HTML;
                    $message->fullmessagehtml = '<p>message body</p>';
                    $message->smallmessage = 'small message';
                    $message->notification = 1; 
                    $message->contexturl = $CFG->wwwroot.'/course/pending.php'; 
                    $message->contexturlname = 'Course list';
                    $result = message_send($message);

                    // $toUser = $this->generate_email_user($admin);
                    // $fromUser = $this->generate_email_user(null,$CFG->noreplyaddress,$nameSite);

                    // $textMessaje = get_string('message_email_admin_1', 'tool_courserequeststomanagers').$usser;
                    // $textMessaje = $textMessaje.get_string('message_email_admin_2','tool_courserequeststomanagers');
                    // $textMessaje = $textMessaje.$course->fullname.get_string('message_email_admin_3','tool_courserequeststomanagers');
                    // $textMessaje = $textMessaje.$CFG->wwwroot.'/course/pending.php';

                    // $result = \email_to_user($toUser, $fromUser, $subject, $textMessaje, $textMessaje, null, null, true);
                
                    // $description = get_string('description_admin_1','tool_courserequeststomanagers').$usser;
                    // $description = $description.get_string('description_admin_2','tool_courserequeststomanagers').$course->fullname;
                    // $description = $description.get_string('description_admin_3','tool_courserequeststomanagers'); 

                    $row[] = $result;
                    $row[] ='';
                    
                }
                $rows[]= $row;
            }
                
        }
        return $rows;
    }

   
    public static function  generate_email_user($user=null,$email='',$name='',$id=-99) {
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