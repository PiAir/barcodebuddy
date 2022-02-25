<?php

/**
 * Barcode Buddy for Grocy
 *
 * PHP version 7
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU General
 * Public License v3.0 that is attached to this project.
 *
 *
 * Locale file. Setting correct locale to the user
 *
 * @author     Ole-Kenneth Bratholt
 * @copyright  2019 Marc Ole Bulling
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html  GNU GPL v3.0
 * @since      File available since Release 1.0
 */

$available_languages = [];
foreach(array_diff(scandir(__DIR__ . "/../locales", 1), ['..', '.']) as $lang) {
    $available_languages[substr(strtolower($lang), 0, 2)] = substr(strtolower($lang), 0, 2).'_'.substr(strtoupper($lang), 0, 2);
}

$lang = strtolower(substr($CONFIG->LOCALE ?? 'en-US', 0, 2));
$locale = $available_languages[$lang] ?? 'en';
// DEBUG
$locale = "nl_NL.UTF-8";

putenv("LC_MESSAGES=$locale");
setlocale(LC_MESSAGES, $locale);
bindtextdomain("messages", __DIR__ . "/../locales");
textdomain("messages");