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

class background_notificaction_email extends \core\task\scheduled_task {
    /**
     * get_name
     *
     * @return string
     */
    public function get_name() {
        return get_string('background_notificaction_email', 'tool_courserequeststomanagers');
    }

    /**
     * Executes the prediction task.
     *
     * @return void
     */
    public function execute() {
        global $OUTPUT, $PAGE;
        
        mtrace(get_string('begin_background_notificaction_email', 'tool_courserequeststomanagers'));
        mtrace(get_string('end_background_notificaction_email', 'tool_courserequeststomanagers'));

        // if (!\core_analytics\manager::is_analytics_enabled()) {
        //     mtrace(get_string('analyticsdisabled', 'analytics'));
        //     return;
        // }

        // $models = \core_analytics\manager::get_all_models(true);
        // if (!$models) {
        //     mtrace(get_string('errornoenabledmodels', 'tool_analytics'));
        //     return;
        // }

        // foreach ($models as $model) {

        //     if ($model->is_static()) {
        //         // Skip models based on assumptions.
        //         continue;
        //     }

        //     if (!$model->get_time_splitting()) {
        //         // Can not train if there is no time splitting method selected.
        //         continue;
        //     }

        //     $renderer = $PAGE->get_renderer('tool_analytics');

        //     $result = $model->train();

        //     // Reset the page as some indicators may call external functions that overwrite the page context.
        //     \tool_analytics\output\helper::reset_page();

        //     if ($result) {
        //         echo $OUTPUT->heading(get_string('modelresults', 'tool_analytics', $model->get_name()));
        //         echo $renderer->render_get_predictions_results($result, $model->get_analyser()->get_logs());
        //     }
        // }
    }
}