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

include "kiss/init.php";

###########################################################
# Account linking
###########################################################
#
# What it does: adds new remote account for an account (CUID)
# What it WONT do: connect two existing cuid-s (because, why?)
#
# Step1: login with known account (existing CUID)
# Step2: store old cuid in session & force reauth
# Step3: arrive here with new account (no cuid allowed)
# Step4: add new remote account to old cuid
#

$attribute_defs = load_attribute_definitions();
# attribute_mappings come from config
$incoming_mapped_attributes = load_attributes($kiss['attribute_mappings']);

sanity_check_incoming_attributes($incoming_mapped_attributes);

$remote_accounts = get_remote_accts_for_iuids($incoming_mapped_attributes["iuid"]);

# We are in STEP1 since there are stored remote acc: store old account
if (!empty($remote_accounts) and empty($_SESSION["acc_link_stored_cuid"])) {
    $cuid = get_cuid_for_remote_accts($remote_accounts);
    $source_id = array_pop($incoming_mapped_attributes["source_id"]);

    #step 2
    $_SESSION["acc_link_stored_cuid"] = $cuid;
    
    make_header($menuitems);
    make_important_box(core_lang("al_head_text"));
    make_text(core_lang("your_cuid",$cuid));
    make_text(core_lang("your_current_source",$source_id));
    make_text(core_lang("al_step_1"));
    make_acc_link_form();
    make_footer();
    exit(0);
} 

# user came back with a new login, but this remote account is already linked
if (!empty($remote_accounts) and !empty($_SESSION["acc_link_stored_cuid"])) {
    $cuid = $_SESSION["acc_link_stored_cuid"];
    $source_id = array_pop($incoming_mapped_attributes["source_id"]);
    make_header($menuitems);
    make_important_box(core_lang("al_head_text"));
    make_text(core_lang("your_cuid",$cuid));
    make_text(core_lang("your_current_source",$source_id));
    make_error_message(core_lang("this_remote_already_linked"));
    make_text(core_lang("al_step_1"));
    make_acc_link_form();
    make_footer();
    exit(0);
}

# step 3 now the account linking can happen: new remotes, but known old cuid
if (empty($remote_accounts) and !empty($_SESSION["acc_link_stored_cuid"])) {
    $cuid = $_SESSION["acc_link_stored_cuid"];
    $source_id = array_pop($incoming_mapped_attributes["source_id"]);

    $attributes_to_save = array();

    foreach($attribute_defs as $key => $def) {
        if ($def["customizable"] == "N") {
            if (isset($incoming_mapped_attributes[$key])) {
                $attributes_to_save[$key] = $incoming_mapped_attributes[$key];
            }
        }
    }

    $rid = db_insert("INSERT INTO kiss_remote_accounts (cuid, source_id, created_at) VALUES (?,?, CURRENT_TIMESTAMP())", $cuid, $source_id);
    foreach ($incoming_mapped_attributes["iuid"] as $iuid) {
        db_insert("INSERT INTO kiss_iuids (iuid, remote_account_id) VALUES (?,?)",$iuid,$rid);
    }
    foreach ($attributes_to_save as $key => $values) {
        foreach ($values as $value) {
            db_insert("INSERT INTO kiss_attributes (cuid, `name`, `value`, `source`) VALUES (?,?,?,?)",$cuid,$key,$value,$source_id);
        }
    }

    make_header($menuitems);
    make_info_message(core_lang("account_linking_success"));
    make_text(core_lang("your_cuid",$cuid));
    make_text(core_lang("your_current_source",$source_id));
    make_footer();
    exit(0);

}

# wrong state: user arrived with unknown account, no stored acc should go to registration instead
$source_id = array_pop($incoming_mapped_attributes["source_id"]);
make_header($menuitems);
make_error_message(core_lang("acc_link_account_unknown"));
make_text(core_lang("your_current_source",$source_id));
make_footer();
exit(0);

?>