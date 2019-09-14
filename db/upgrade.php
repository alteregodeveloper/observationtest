<?php

/**
 * @package   mod_observationtest
 * @copyright 2019, alterego developer {@link https://alteregodeveloper.github.io}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute vat upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */

function xmldb_observationtest_upgrade($oldversion, $mod) {
    global $CFG;

    return true;
}
