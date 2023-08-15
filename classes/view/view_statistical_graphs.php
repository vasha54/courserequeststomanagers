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
			default: parent::view(); break;
		}
	}

	private function showTableMenu($_action,$_view){
        global $OUTPUT, $CFG, $DB;
		
	}

    
}