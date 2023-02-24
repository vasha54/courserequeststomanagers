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



require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/csvlib.class.php');
require_once($CFG->dirroot.'/admin/tool/courserequeststomanagers/locallib.php');


$view = optional_param('view',0, PARAM_INT);
$action = optional_param('action',0, PARAM_INT);


//  $PAGE->set_url('/admin/tool/courserequeststomangers/index.php',null);
//  $PAGE->set_pagetype('courses');

$context = context_system::instance();
$PAGE->set_context($context);
admin_externalpage_setup('toolcourserequeststomanagers');

if($view<100){
    
    print $OUTPUT->header();
    print $OUTPUT->heading(get_string('pluginname', 'tool_courserequeststomanagers'));
    $desc = get_string('courserequeststomanagers_desc', 'tool_courserequeststomanagers');
    print $OUTPUT->box($desc);
}

$renderer = FactoryView::factoryMethodView($action,$view);
$content = $renderer->view();
print $content;

if($view<100){ 
    print $OUTPUT->footer();
} 