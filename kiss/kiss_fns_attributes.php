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

function generate_cuid() {
    return bin2hex(random_bytes(12)) . $GLOBALS['kiss']['community_scope'];
}

function check_for_similar_account($attributes) {
    return false;
}

function load_attribute_definitions() {

    $attribute_defs_raw = query_matrix("SELECT name, required, multival, customizable, displayed, validator_regex FROM attribute_defs");

    $attribute_defs = array();
    foreach($attribute_defs_raw as $def) {
        $attribute_defs[$def["name"]] = array();
        $attribute_defs[$def["name"]]["name"] = $def["name"];
        $attribute_defs[$def["name"]]["required"] = $def["required"];
        $attribute_defs[$def["name"]]["multival"] = $def["multival"];
        $attribute_defs[$def["name"]]["displayed"] = $def["displayed"];
        $attribute_defs[$def["name"]]["customizable"] = $def["customizable"];
        $attribute_defs[$def["name"]]["validator_regex"] = $def["validator_regex"];
    }


    #make_info_message(print_r($attribute_defs,true));
    return $attribute_defs;
}

function sanity_check_incoming_attributes($incoming) {
    # No IUID incoming which is always an error
    if (!isset($incoming["iuid"])) {
        make_error_message(auxi_lang("no_iuid_received"));
        make_footer();
        exit(0);
    }

    # No source incoming which is always an error
    if (!isset($incoming["source_id"])) {
        make_error_message(auxi_lang("no_source_id_received"));
        make_footer();
        exit(0);
    }
}

function user_exists_by_iuid($iuids) {
    foreach($iuids as $iuid) {
        $remote_accounts = query_vector("SELECT remote_account_id FROM iuids WHERE iuid = ?", $iuid);
    
        if(!empty($remote_accounts)) {
            return true;
        }
    }
    return false;
}

function get_remote_accts_for_iuids($iuids) {
    $remote_accounts = array();
    foreach($iuids as $iuid) {

        $remote_account_id = query_scalar("SELECT remote_account_id FROM iuids WHERE iuid = ?", $iuid);
    
        if(!empty($remote_account_id)) {
            
            if(!in_array($remote_account_id, $remote_accounts)) {
                array_push($remote_accounts,$remote_account_id);
            }
        }
    }
    return $remote_accounts;
}

function get_cuid_for_remote_accts($remote_accounts) {
    $cuid = null;
    foreach($remote_accounts as $remote) {
        $remote_acc = query_matrix("SELECT cuid, source_id, created_at FROM remote_accounts WHERE id = ?", $remote);
        if (empty($cuid)) {
            $cuid = $remote_acc[0]['cuid'];
        } else {
            if ($remote_acc[0]['cuid'] != $cuid) {
                auxi_log_error("Multiple CUIDs:". $cuid . " vs:" .$remote_acc[0]['cuid']);
                make_header($menuitems);
                make_error_message(auxi_lang("multiple_cuids"));
                make_footer();
                exit(0);
            }
        }
    }
    return $cuid;
}

function load_attributes($mappings) {

    // source array: an associative array 

    // TODO: check KMAC

    $source = $_SERVER;

    $ret = array();

    foreach($mappings as $key => $mapping) {
        foreach($mapping as $item) {
            auxi_log_trace("Checking $item for attr $key");
            if (isset($source[$item])) {
                auxi_log_trace("Found $item for attr $key");
                if (!isset($ret[$key])) {
                    if(is_array($source[$item])) {
                        $ret[$key] = $source[$item];
                    }
                    else {
                        $ret[$key] = array();
                        array_push($ret[$key], $source[$item]);   
                    }
                }
                else {
                    $ret[$key] = array_merge($ret[$key],$source[$item]);
                }
            }
        }
    }

    return $ret;
}

function attr_validate($def,$value) {
    $name = $def["name"];
    if (isset($def['validator_regex']) and ($def['validator_regex'] != "NULL") and !preg_match($def['validator_regex'],$value)) {
        make_header($menuitems);
        make_error_message(auxi_lang("value_validation_fail",$value));
        make_info_message(auxi_lang("attribute_".$name."_validation_info"));
        make_footer();    
        exit(0);
    }
}


?>