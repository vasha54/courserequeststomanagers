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
 * tool_courserequeststomanagers tasks
 *
 * @package   tool_courserequeststomanagers
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



namespace tool_courserequeststomanagers\task;



/**
 * report_async tasks
 *
 * @package   tool_courserequeststomanagers
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class report_async extends \core\task\scheduled_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('pluginname', 'tool_courserequeststomanagers');
    }

    

    /**
     * Execute task
     */
    public function execute() {
        global $DB, $CFG;
        
        require_once($CFG->dirroot.'/admin/tool/courserequeststomanagers/locallib.php');

        mtrace("tool_courserequeststomanagers .... ".get_string('begin_process_send_email','tool_courserequeststomanagers'));
        
        //  $pending = $DB->get_records('course_request');

        //  foreach ($pending as $course) {
            
        //      $course = new course_request($course);

            //  $course->check_shortname_collision();
            //  if (!$course->can_approve()) {
            //      continue;
            //  }
        //     $category = $course->get_category();
        //     // $usser = $course->get_requester()->firstname.' '.$course->get_requester()->lastname.' ('.$course->get_requester()->email.')';
        //     $managers = $this->getManagers($category->id);
        //     // format_string($course->fullname);
        //     // $category->get_formatted_name();
        //     // format_string($course->reason);
            

        //     foreach($manager as $managers){
        //         mtrace("tool_courserequeststomanagers .... ".get_string('send_email_to','tool_courserequeststomanagers')." ".$manager->firstname." ".$manager->lastname);
        //     }
            
        // }
        
        mtrace("tool_courserequeststomanagers ....".get_string('end_process_send_email','tool_courserequeststomanagers'));
        
    }
}