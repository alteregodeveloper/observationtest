<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function get_complexity_ranges(){
    return array(
    1 => get_string('easy','observationtest'),
    2 => get_string('medium','observationtest'),
    3 => get_string('advanced','observationtest'));
}

function get_observationtest_categories() {
    global $USER, $DB;

    $query = 'SELECT id, category FROM mdl_observation_categories';
    return $DB->get_records_sql_menu($query);
}

function show_addcasesbutton() {
    $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    echo '<div class="newcasses" style="text-align: right"><a class="printicon" title="Add new case" href="' . $current_url . '&action=addcase"><i class="far fa-plus-square"></i> Add new case</a></div>';
}

function show_case() {
    echo '<div class="yui3-g">
    <div class="yui3-u" id="nav">
        <p>Test A</p>
    </div>
    <div class="yui3-u" id="main">
        <p>Test b</p>
    </div>
</div>';
}

function show_addcase_form($activity) {
    $complexityranges = get_complexity_ranges();
    $categories = get_observationtest_categories();
    require_once('localview/addcase_form.php');
}

function set_case($category,$complexity,$customFile) {
    global $DB;
    $record = new stdClass();
    $record->categoryid = $category;
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
        echo json_encode(array('status' => 'success', 'message' => 'Category successfully added', 'idcategory' => $idcategory, 'category' => $category));
    } else {
        echo json_encode(array('status' => 'danger', 'message' => 'It was not possible to create a new category'));
    }
}