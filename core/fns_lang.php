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

function auxi_get_tx_generic($table, $txkey, $lang ,$param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null) {
    $tx = query_scalar("SELECT content FROM " . $table . " WHERE txkey = ? AND lang = ?", $txkey, $lang);
    if (empty($tx)) {
        return null;
    }
    else {
        if (isset($param1) and !isset($param2)) {
            $tx = sprintf($tx, $param1);
        }
        if (isset($param1) and isset($param2) and !isset($param3)) {
            $tx = sprintf($tx, $param1, $param2);
        }
        if (isset($param1) and isset($param2) and isset($param3) and !isset($param4)) {
            $tx = sprintf($tx, $param1, $param2, $param3);
        }
        if (isset($param1) and isset($param2) and isset($param3) and isset($param4) and !isset($param5)) {
            $tx = sprintf($tx, $param1, $param2, $param3, $param4);
        }
        if (isset($param1) and isset($param2) and isset($param3) and isset($param4) and isset($param5)) {
            $tx = sprintf($tx, $param1, $param2, $param3, $param4, $param5);
        }

        return $tx;
    }
}

## queries the database for the translation of a translation key
function auxi_lang($txkey, $param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null) {
    $tx = auxi_get_tx_generic("auxi_lang", $txkey, $GLOBALS['lang'], $param1, $param2, $param3, $param4, $param5);
    if (empty($tx)) {
        trigger_error("TRANSLATION MISSING: '".$txkey."'",E_USER_NOTICE);
    }
    return $tx;
}

#
# returns with static page content, usually html
#
function auxi_static_content($txkey, $param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null) {
    $tx = auxi_get_tx_generic("auxi_static_content", $txkey, $GLOBALS['lang'], $param1, $param2, $param3, $param4, $param5);
    if (empty($tx)) {
        trigger_error("STATIC CONTENT MISSING: '".$txkey."'",E_USER_NOTICE);
    }
    return $tx;
}

?>