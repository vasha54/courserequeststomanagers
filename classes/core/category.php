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

class category {

    public static function getManagers($categoryid){
        $managers = array();

        $contextcat = \context_coursecat::instance($categoryid);
        $options = array('context' => $contextcat, 'roleid' => 1);
        $currentusers = new \core_role_existing_role_holders('removeselect', $options);
        $search = optional_param($currentusers->get_name() . '_searchtext', '', PARAM_RAW);
        $searchusers = $currentusers->find_users($search);

        foreach ($searchusers as $users) {
            foreach ($users as $userid => $value) {
                    $managers[$userid] = $value;
            }
        }
        return $managers;
    }


    public static function getStrManagers($categoryid, $_format='<li>'){
        $_managers = \tool_courserequeststomanagers\core\category::getManagers($categoryid);

        $list=get_string('no_managers', 'tool_courserequeststomanagers');
        if ( count($_managers) > 0){
            switch ($_format) {
                case '<li>':
                    $list="<li style='display:block;'>";
                    foreach ($_managers as $manager) {
                        $list.="<ul>".$manager->firstname." ".$manager->lastname."<br>(".$manager->email.")</ul>";
                    }
                    $list.="</li>";
                    break;
                case '<br>':
                    $i = 0;
                    $list="";
                    foreach ($_managers as $manager) {
                        if ($i!=0) $list=$list."<br>";
                        $list=$list.$manager->firstname." ".$manager->lastname." (".$manager->email.")";
                        $i++;
                    }
                default:
                    # code...
                    break;
            }
        }
        return $list;
    }   
}