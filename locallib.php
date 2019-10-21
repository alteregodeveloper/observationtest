<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function get_complexity_ranges(){
    return array(
    6 => get_string('easy','observationtest'),
    4 => get_string('medium','observationtest'),
    2 => get_string('advanced','observationtest'));
}

function get_observationtest_categories() {
    global $DB;

    $query = 'SELECT id, category FROM mdl_observation_categories';
    return $DB->get_records_sql_menu($query);
}

function get_user_can_edit($roles) {
    if($roles) {
        $role = key($roles);
        $shortname = $roles[$role]->shortname;
        if($shortname == 'manager' || $shortname == 'coursecreator' || $shortname == 'editingteacher') {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        if(is_siteadmin()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

function show_addcasesbutton() {
    $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    echo '<div class="newcasses" style="text-align: right"><a class="printicon" title="Add new case" href="' . $current_url . '&action=addcase"><i class="far fa-plus-square"></i> ' . get_string('start_test', 'observationtest') . '</a></div>';
}

function show_case($observationtestid,$complexity,$observationroute) {
    global $DB;

    $query = 'SELECT mdl_observation_cases.id, mdl_observation_cases.intro FROM mdl_observation_cases INNER JOIN mdl_observationtest ON mdl_observation_cases.category = mdl_observationtest.category AND mdl_observation_cases.complexity = mdl_observationtest.complexity WHERE mdl_observationtest.id = ' . $observationtestid . ' ORDER BY RAND() LIMIT 1';
    $case = $DB->get_record_sql($query);

    $image_route = $observationroute . $case->intro;
    $qnas = '';

    $query = 'SELECT id, caseid, intro FROM mdl_observation_questions WHERE caseid = ' . $case->id . ' ORDER BY RAND()';
    $questions = $DB->get_records_sql($query);
    foreach($questions as $question) {
        $query = 'SELECT id, questionid, correct, intro FROM mdl_observation_answers WHERE questionid = ' . $question->id . ' ORDER BY RAND()';
        $answers = $DB->get_records_sql($query);
        $qnas .= question_show($question,$answers);
    }
    require_once('localview/case_exercise.php');
}

function show_addcase_form($activity) {
    $complexityranges = get_complexity_ranges();
    $categories = get_observationtest_categories();
    require_once('localview/addcase_form.php');
}

function set_case($category,$complexity,$customFile) {
    global $DB;
    $record = new stdClass();
    $record->category = $category;
    $record->complexity = $complexity;
    $record->intro = $customFile;
    $currentDate = new DateTime();
    $record->timecreated = $currentDate->getTimestamp();
    $record->timemodified = $currentDate->getTimestamp();
    return $DB->insert_record('observation_cases', $record, true);
}

function show_alert($status, $message) {
    echo '<div class="alert alert-' . $status . ' alert-dismissible fade show" role="alert"><i class="fas fa-bell"></i> ' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}

function set_category($category) {
    global $DB;
    $record = new stdClass();
    $record->category = $category;
    $currentDate = new DateTime();
    $record->timecreated = $currentDate->getTimestamp();
    $record->timemodified = $currentDate->getTimestamp();
    $idcategory = $DB->insert_record('observation_categories', $record, true);
    if($idcategory > 0) {
        echo json_encode(array('status' => 'success', 'message' => get_string('category_added', 'observationtest'), 'idcategory' => $idcategory, 'category' => $category));
    } else {
        echo json_encode(array('status' => 'danger', 'message' => get_string('category_dont_added', 'observationtest')));
    }
}

function show_addquestion_form($observationtestid,$caseid,$category,$complexity,$upload_file) {
    require_once('localview/addquestion_form.php');
}

function set_question($caseid,$question) {
    global $DB;
    $record = new stdClass();
    $record->caseid = $caseid;
    $record->intro = $question;
    $currentDate = new DateTime();
    $record->timecreated = $currentDate->getTimestamp();
    $record->timemodified = $currentDate->getTimestamp();
    $questionid = $DB->insert_record('observation_questions', $record, true);
    if($questionid > 0) {
        echo json_encode(array('status' => 'success', 'message' => get_string('question_added', 'observationtest'), 'questionid' => $questionid, 'question' => $question));
    } else {
        echo json_encode(array('status' => 'danger', 'message' => get_string('question_dont_added', 'observationtest')));
    }
}

function set_answer($questionid,$correct,$intro) {
    global $DB;
    $record = new stdClass();
    $record->questionid = $questionid;
    $record->correct = $correct;
    $record->intro = $intro;
    $currentDate = new DateTime();
    $record->timecreated = $currentDate->getTimestamp();
    $record->timemodified = $currentDate->getTimestamp();
    $answerid = $DB->insert_record('observation_answers', $record, true);
    if($answerid > 0) {
        echo json_encode(array('status' => 'success', 'correct' => $correct, 'answer' => $intro));
    } else {
        echo json_encode(array('status' => 'warning', 'message' => get_string('error_answer', 'observationtest')));
    }
}

function question_show($question,$answers) {
    $html = '<div class="form-group question border-bottom mb-5"><input type="hidden" class="quiz-value" name="quiz_' . $question->id . '" value="0"><p>' . $question->intro . '</p>';  
    foreach($answers as $answer) {
        $html .= '<p class="answer" data-correct="' . $answer->correct . '"><i class="fas fa-square"></i> ' .$answer->intro . '</p class="answer">';
    }
    $html .= '</div>';
    return $html;
}

function set_result($userid,$testid,$caseid,$exercise,$result) {
    global $DB;
    $record = new stdClass();
    $record->userid = $userid;
    $record->testid = $testid;
    $record->caseid = $caseid;
    $record->exercise = $exercise;
    $record->result = $result;
    $currentDate = new DateTime();
    $record->timecreated = $currentDate->getTimestamp();
    $record->timemodified = $currentDate->getTimestamp();
    $answerid = $DB->insert_record('observation_result', $record, true);
    if($answerid > 0) {
        $message = get_string('result_is', 'observationtest') . ' ' . round($result,2);
        echo json_encode(array('status' => 'success', 'message' => $message, 'result' => round($result,2), 'exercise' => $exercise));
    } else {
        echo json_encode(array('status' => 'warning', 'message' => get_string('result_dont_save', 'observationtest')));
    }
}