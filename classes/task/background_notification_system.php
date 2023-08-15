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

namespace tool_courserequeststomanagers\task;

defined('MOODLE_INTERNAL') || die();

class background_notification_system extends \core\task\scheduled_task {
    /**
     * get_name
     *
     * @return string
     */
    public function get_name() {
        return get_string('background_notification_system', 'tool_courserequeststomanagers');
    }

    /**
     * Executes the task.
     *
     * @return void
     */
    public function execute() {
        global $CFG,$DB; 
        $pending = $DB->get_records('course_request');
        
        mtrace(get_string('begin_background_notification_system', 'tool_courserequeststomanagers'));

        $rows = array();
        
        foreach ($pending as $course){
           mtrace(var_dump($course));
        }
        
        mtrace(count($pending).'bien todo');
        mtrace(get_string('end_background_notification_system', 'tool_courserequeststomanagers'));

       
    }

}