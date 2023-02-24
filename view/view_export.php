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
 * @package    report_coursesize
 * @copyright  2014 Catalyst IT {@link http://www.catalyst.net.nz}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot.'/admin/tool/courserequeststomanagers/view/view.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');


require_once($CFG->dirroot . '/admin/tool/courserequeststomanagers/core/handle_export/handle_export_pdf.php');




class ViewExport extends View {

    function __construct($_action,$_view){
        parent::__construct($_action,$_view);
    }
	public function view(){

        global $OUTPUT;

        $this->m_view = 100;
        $handleExport = null;

        $rows = $this->generateRowsReportFile();

        switch ($this->m_action) {
            case 1:
                $handleExport = new HandleExportPDF($this->m_action,$this->m_view);
            break;
            case 2:
                $handleExport = new HandleExportEXCEL($this->m_action,$this->m_view);
                break;
            default:
                 admin_externalpage_setup('toolcourserequeststomanagers');
                 print $OUTPUT->header();
                 print $OUTPUT->heading(get_string('pluginname', 'tool_courserequeststomanagers'));
                 $desc = get_string('courserequeststomanagers_desc', 'tool_courserequeststomanagers');
                 print $OUTPUT->box($desc);
                 parent::view(); 
            	 print $OUTPUT->footer();
            	 break;
		}

        if(is_null($handleExport) == false){
            $handleExport->generateReport($rows);
        }

	}

	
}
