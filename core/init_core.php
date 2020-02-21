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

include "conf.php";

#pick up all variables
$GLOBALS['core'] = $core;

if (!empty($core['customcss'])) {
    if (!file_exists($core['customcss'])) {
        log_error("Configured custom CSS:".$core['customcss']." does not exist.");
    } else {
        $GLOBALS['customcss'] = $core['customcss'];
    }
}

###########################################################
# get page name and parameters to globals
###########################################################

load_pageinfo_to_globals();

###########################################################
# decide actual lang
###########################################################
if (count($core['ls_languages']) == 1) {
    $GLOBALS['lang'] = $core['ls_languages'][0];
}
// TODO multi-lang

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

?>