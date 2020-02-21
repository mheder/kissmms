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

require_once "kiss/kiss_fns_attributes.php";
require_once "kiss/kiss_fns_crypto.php";
require_once "kiss/kiss_fns_email.php";
require_once "kiss/kiss_fns_audit.php";

include "core/init_core.php";

session_start();

#pick up all variables
$GLOBALS['kiss'] = $kiss;

###########################################################
# customizations
###########################################################

if (file_exists("customizations/fns_email.php")) {
    require_once 'customizations/fns_email.php';
} else {
    require_once "core/fns_email.php";
}

?>