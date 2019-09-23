<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('lib.php');
require_once('locallib.php');

$id = required_param('id', PARAM_INT);
list ($course, $cm) = get_course_and_cm_from_cmid($id, 'observationtest');
$observationtest = $DB->get_record('observationtest', array('id'=> $cm->instance), '*', MUST_EXIST);

require_login($course, true, $cm);
$modulecontext = context_module::instance($cm->id);

$useredit = get_user_can_edit(get_user_roles($modulecontext, $USER->id));

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($useredit) {
        if(isset($_POST['action'])) {
            if($_POST['action'] == 'addcategory') {
                echo set_category($_POST['category']);
            } else if($_POST['action'] == 'addquestion') {
                echo set_question($_POST['caseid'],$_POST['question']);
            }  else if($_POST['action'] == 'addanswer') {
                echo set_answer($_POST['questionid'],$_POST['correct'],$_POST['intro']);
            } else if($_POST['action'] == 'addcase') {
                $PAGE->set_url('/mod/observationtest/view.php', array('id' => $cm->id));
                $PAGE->set_title(format_string($observationtest->name));
                $PAGE->requires->css(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'));
                $PAGE->set_heading(format_string($course->fullname));
                $PAGE->set_context($modulecontext);
                echo $OUTPUT->header();
                echo $OUTPUT->heading('Add new case');
                if($_FILES['customFile']){
                    $extensions = array("jpeg","jpg","png","gif");
                    $extension_file = pathinfo($_FILES['customFile']['name'],PATHINFO_EXTENSION);
                    if(!in_array(strtolower($extension_file),$extensions))
                    {
                        show_alert('danger','File extension is not supported. Select an image with extension jpeg, jpg, png or gif');
                        show_addcase_form($cm->id);
                    } else {
                        $upload_dir = $CFG->dirroot . '/mod/observationtest/cases/';
                        $currentDate = new DateTime();
                        $filename = $currentDate->getTimestamp() . '.' . $extension_file;
                        $upload_file = $upload_dir . basename($filename);
                        if (move_uploaded_file($_FILES['customFile']['tmp_name'], $upload_file)) {
                            $caseid = set_case($_POST['category'],$_POST['complexity'],$filename);
                            if($caseid > 0) {
                                show_alert('success','The file was saved successfully. Now you can create the questions');
                                $loadfile = $CFG->wwwroot . '/mod/observationtest/cases/' . basename($filename);
                                show_addquestion_form($cm->id,$caseid,$_POST['categoryname'],$_POST['complexityname'],$loadfile);
                                
                            } else {
                                show_alert('danger','An error occurred while trying to save the image. Try again');
                                show_addcase_form($cm->id);
                            }
                        } else {
                            show_alert('danger','An error occurred while trying to save the image. Try again');
                            show_addcase_form($cm->id);
                        }
                    }
                }
                $PAGE->requires->js(new moodle_url('https://code.jquery.com/jquery-3.4.1.min.js'));
                $PAGE->requires->js(new moodle_url('https://kit.fontawesome.com/8368a92b51.js'));
                $PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js'));
                $PAGE->requires->js(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'));
                $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/mod/observationtest/assets/js/observationtest.js'));
                echo $OUTPUT->footer();
            } else if($_POST['action'] == 'addresult') {
                echo set_result($USER->id,$_POST['testid'],$_POST['caseid'],$_POST['result']);
            }
        }
    }
} else {
    $PAGE->set_url('/mod/observationtest/view.php', array('id' => $cm->id));
    $PAGE->set_title(format_string($observationtest->name));
    $PAGE->requires->css(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'));
    $PAGE->set_heading(format_string($course->fullname));
    $PAGE->set_context($modulecontext);
    echo $OUTPUT->header();
    if(!$useredit) {
        if(isset($_GET['action'])) {
            if($_GET['action'] == 'addcase') {
                echo $OUTPUT->heading('Add new case');
                show_addcase_form($cm->id);
            }
        }  else {
            echo $OUTPUT->heading($observationtest->name);
            show_addcasesbutton();
            echo $observationtest->intro;
        }
    } else {
        echo $OUTPUT->heading($observationtest->name);
        echo $observationtest->intro;
        show_case($observationtest->id,$observationtest->complexity,$CFG->wwwroot . '/mod/observationtest/cases/');
    }
    $PAGE->requires->js(new moodle_url('https://code.jquery.com/jquery-3.4.1.min.js'));
    $PAGE->requires->js(new moodle_url('https://kit.fontawesome.com/8368a92b51.js'));
    $PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js'));
    $PAGE->requires->js(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'));
    $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/mod/observationtest/assets/js/observationtest.js'));
    echo $OUTPUT->footer();
}