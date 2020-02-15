<?php

############################################################################
#
# Copyright [2020] [Mihály Héder]
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
############################################################################

require_once "core/fns_db.php";
require_once "core/fns_lang.php";
require_once "core/fns_env.php";
require_once "core/fns_log.php";
require_once "core/fns_misc.php";

require_once "kiss/kiss_fns_attributes.php";
require_once "kiss/kiss_fns_crypto.php";
require_once "kiss/kiss_fns_email.php";
require_once "kiss/kiss_fns_audit.php";

session_start();

include "conf.php";

###########################################################
# database info
###########################################################

$GLOBALS['db_host'] = $db_host;
$GLOBALS['db_user'] = $db_user;
$GLOBALS['db_pass'] = $db_pass;
$GLOBALS['db_name'] = $db_name;

###########################################################
# baseurl, logos
###########################################################

$GLOBALS['baseurl'] = $baseurl;

$GLOBALS['left-logo'] = $left_logo;
$GLOBALS['head-logo'] = $head_logo;

if (!empty($customcss)) {
    if (!file_exists($customcss)) {
        log_error("Configured custom CSS:".$customcss." does not exist.");
    } else {
        $GLOBALS['customcss'] = $customcss;
    }
}

###########################################################
# get page name and parameters to globals
###########################################################

load_pageinfo_to_globals();

###########################################################
# decide actual lang
###########################################################
if (count($ls_languages) == 1) {
    $GLOBALS['lang'] = $ls_languages[0];
}
// TODO multi-lang

###########################################################
# email settings
###########################################################
$GLOBALS['email_from'] = $email_from;

###########################################################
# log settings
###########################################################
$GLOBALS['debug_log'] = $debug_log;
$GLOBALS['trace_log'] = $trace_log;

###########################################################
# html elements, default or custom
###########################################################

function make_header($menuitems) {
    if (file_exists("customizations/header.php")) {
        include 'customizations/header.php';
    } else {
        include "core/style/header.php";
    }
}

function make_footer() {
    if (file_exists("customizations/footer.php")) {
        include 'customizations/footer.php';
    } else {
        include "core/style/footer.php";
    }
}

if (file_exists("customizations/elements.php")) {
    require_once 'customizations/elements.php';
} else {
    require_once "core/style/elements.php";
}

###########################################################
# customizations
###########################################################

if (file_exists("customizations/fns_email.php")) {
    require_once 'customizations/fns_email.php';
} else {
    require_once "core/fns_email.php";
}

?>