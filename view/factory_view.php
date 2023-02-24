<?php

defined('MOODLE_INTERNAL') || die();




class FactoryView  {

	public static function factoryMethodView($_action,$_view){
		$view = new View($_action,$_view);
        switch ($_view) {
            case 0: $view = new TableViewRequestCourse($_action,$_view); break;
            case 100: $view = new ViewExport($_action,$_view); break;
        }

        return $view;
	}
}
