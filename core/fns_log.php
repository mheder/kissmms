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

###########################################################
# log functions to use
###########################################################

function auxi_log_error($msg) {
    error_log($msg);
}

function auxi_log_debug($msg) {
    if ($GLOBALS['debug_log']) {
        trigger_error($msg,E_USER_NOTICE);
    }
}

function auxi_log_trace($msg) {
    if ($GLOBALS['trace_log']) {
        trigger_error($msg,E_USER_NOTICE);
    }
}

?>