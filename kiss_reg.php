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

include "init.php";

$attribute_defs = load_attribute_definitions();
# attribute_mappings come from config
$incoming_mapped_attributes = load_attributes($kiss['attribute_mappings']);

###########################################################
# look up user based on IUIDs, see if new user or existing
# user
###########################################################

sanity_check_incoming_attributes($incoming_mapped_attributes);

$user_found = user_exists_by_iuid($incoming_mapped_attributes["iuid"]);

# already existing user, showing message
# the incoming attributes are discarded as ending up here is a mistake
if ($user_found) {
    make_header();
    make_info_message(core_lang("already_have_account_no_reg"));
    make_footer();
    exit(0);
} else {
    #attempting heuristic lookup
            # UNKNOWN IUID -> new account or account linking needs to happen
        # First, we look for similar accounts
        if (check_for_similar_account($incoming_mapped_attributes)) {
            # display acc. linking link
            # store data to session and forward to acc. linking
        } else {
            # assume new user, generate CUID
            $cuid = generate_cuid();

            session_unset();

            $_SESSION["iuid"] = $incoming_mapped_attributes["iuid"];
            $_SESSION["cuid"] = $cuid;
        }

}

###########################################################
# generate screen
###########################################################

$regscreen_data = array();

# adding the mandatory nickname
# TODO make this a regular attr?
$regscreen_data["nickname"] = array($attribute_defs["nickname"], array());

foreach($incoming_mapped_attributes as $key => $vals) {

    if (!isset($attribute_defs[$key])) {
        # no config at all for attribute, discarding
        continue;
    }

    # TODO multi-value checking
    # TODO regex checking
    $_SESSION["attributes"][$key] = $vals;

    $regscreen_data[$key] = array($attribute_defs[$key],$vals);

}

if(isset($_GET['redirect_url'])) {
    $regscreen_data['redirect_url'] = array(array("displayed" => "N"),array($_GET['redirect_url']));
}

make_header();   

make_regscreen($regscreen_data, $cuid);

make_footer();

?>