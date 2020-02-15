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

include "core/init.php";

$attribute_defs = load_attribute_definitions();
# attribute_mappings come from config
$incoming_mapped_attributes = load_attributes($attribute_mappings);

sanity_check_incoming_attributes($incoming_mapped_attributes);

$remote_accounts = get_remote_accts_for_iuids($incoming_mapped_attributes["iuid"]);

# unknown user
if (empty($remote_accounts)) {
    make_header($menuitems);
    make_error_message(auxi_lang("unknown_user"));
    make_footer();
    exit(0);
} 

$cuid = get_cuid_for_remote_accts($remote_accounts);

###########################################################
# generate screen
###########################################################

# manscreen_data is a 3xn table: <some attribute name>,<value>,<extra info>
$manscreen_data = array();

// display cuid
$cuid_date = query_scalar("SELECT created_at FROM accounts WHERE cuid = ?",$cuid);
array_push($manscreen_data,array('cuid',"<b>" . $cuid . "</b>","local","",$cuid_date,"",""));

// display current source
$source_id = array_pop($incoming_mapped_attributes["source_id"]);
array_push($manscreen_data,array('current_source',"<b>" . $source_id . "</b>","","","",""));

// display remote_accounts
$stored_remote_accounts = query_matrix("SELECT source_id, created_at FROM remote_accounts WHERE cuid = ?", $cuid);
foreach($stored_remote_accounts as $remote) {
    array_push($manscreen_data, array('remote_act',$remote['source_id'],"","",$remote['created_at'],""));
}

$editable_but_empty = array();

//display attributes
foreach($attribute_defs as $name => $def) {
    $attrval = query_matrix("SELECT value, source, assurance,updated_at FROM attributes WHERE cuid = ? AND name = ?", $cuid, $name);
    if (empty($attrval)) {
        if ($def["customizable"] != "Y") {
            continue;
        } else {
            array_push($editable_but_empty,$name);
            continue;
        }
    }

    $attr_str = "";
    $assurance_str = "";
    $source_str = "";
    $update_str = "";
    $first = true;

    foreach($attrval as $val) {
        if (!$first) {
            $attr_str .= "<br/>";
        }
        $firstv = true;
        foreach($val as $k => $v) {
            if ($firstv) {
                $attr_str .= "<br/>";
            }
            if ($k == "value") {
                $attr_str .= "<b>" . $v ."</b>";
            }
            if ($k == "source") {
                $source_str .= $v;
            }
            if ($k == "assurance") {
                $assurance_str .= $v;
            }
            if ($k == "updated_at") {
                $update_str .= $v;
            }
            $firstv = false;
        }
        $first = false;
    }
    
    $edit_str = "";
    if ($def["customizable"] == "Y") {
        $edit_str = '<a href="attr_edit.php?attrname=' . urlencode($name) .'">'. auxi_lang("edit_attribute") . "</a>";
    }
    else {
        $edit_str = auxi_lang("attribute_read_only");
    }
    
    array_push($manscreen_data, array("attribute_".$name, $attr_str, $source_str , $assurance_str ,$update_str, $edit_str ));
}

foreach ($editable_but_empty as $aname) {
    $edit_str = '<a href="attr_edit.php?attrname=' . urlencode($aname) .'">'. auxi_lang("edit_attribute") . "</a>";
    array_push($manscreen_data, array("attribute_".$aname, auxi_lang("no_value") , $edit_str));
}

make_header($menuitems);
make_important_box(auxi_lang("your_account_data"));
make_manscreen($manscreen_data);
make_footer();

?>