<?php

/**
* @author Luis Andrés Valido Fajardo
* @email luis.valido@umcc.cu
*/
defined('MOODLE_INTERNAL') || die();

class Category {

    public static function getManagers($categoryid){
        $managers = array();

        $contextcat = context_coursecat::instance($categoryid);
        $options = array('context' => $contextcat, 'roleid' => 1);
        $currentusers = new core_role_existing_role_holders('removeselect', $options);
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
        $_managers = Category::getManagers($categoryid);

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
