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

$attribute_defs = load_attribute_definitions();

# this page is special in that it requires a different sanity check for incoming attrs

$user_found = user_exists_by_iuid($_SESSION["iuid"]);

# already existing user, showing message
# could be a form resubmission, user has no business being here
if ($user_found) {
    make_header($menuitems);
    make_error_message(core_lang("already_have_account_no_reg"));
    make_footer();
    exit(0);
} 


###########################################################
# Processing POST submission, if this is a form submission
# All server-side validation checks are done here
###########################################################
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # this post variable is signalling us that it was an
    # incoming registration form
    // there cannot be multiple values of source_id
    $source_id = array_pop($_SESSION["attributes"]["source_id"]);

    if (isset($_POST["aup_checkbox"])) {
        if($_POST["aup_checkbox"] != "check") {
            make_header($menuitems);
            make_error_message(core_lang("aup_unchecked"));
            make_footer();    
            exit(0);
        }
        else {
            $attributes_to_save = array();
            foreach($_POST as $key => $val) {
                # submitted attribute names should start with "attribute_" if not, continue
                # also, no cuid, iuid values are allowed to come from form, continue
                if(!startsWith($key,"attribute_") or $key == "attribute_cuid" or $key == "attribute_iuid") {
                    continue;
                }
                $name = str_replace("attribute_","",$key);

                # if this was not a customizable attr, don't even load it
                if ($attribute_defs[$name]["customizable"] == "Y") {
                    # TODO regex, multival
                    $attributes_to_save[$name] = array($val);
                } 
            }
            # non-customizable variables may come from session
            foreach($attribute_defs as $key => $def) {
                if ($def["customizable"] == "N") {
                    if (isset($_SESSION["attributes"][$key])) {
                        $attributes_to_save[$key] = $_SESSION["attributes"][$key];
                    }
                }
            }
            
            #final check before continue: is all required attrs present?
            foreach($attribute_defs as $key => $def) {
                if ($def["required"] == "Y") {
                    if (!isset($attributes_to_save[$key])) {
                        make_header($menuitems);
                        make_error_message(core_lang("missing required_attr", core_lang("attribute_".$key)));
                        make_footer();    
                        exit(0);
                    }
                }
            }
            # final check: all validators pass?
            foreach ($attributes_to_save as $key => $values) {
                foreach ($values as $value) {
                    attr_validate($attribute_defs[$key],$value);
                }
            }

            # now create the new account
            db_insert("INSERT INTO kiss_accounts (cuid,created_at,updated_at) VALUES (?,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP())",$_SESSION["cuid"]);
            $rid = db_insert("INSERT INTO kiss_remote_accounts (cuid, source_id, created_at) VALUES (?,?, CURRENT_TIMESTAMP())", $_SESSION["cuid"], $source_id);
            foreach ($_SESSION["iuid"] as $iuid) {
                db_insert("INSERT INTO kiss_iuids (iuid, remote_account_id) VALUES (?,?)",$iuid,$rid);
            }
            foreach ($attributes_to_save as $key => $values) {
                foreach ($values as $value) {

                    $effectiveSource = $source_id;

                    if ($attribute_defs[$key]["customizable"] == "Y") {
                        # even if the user left it untouched, since it is customozable we save as user_input
                        $effectiveSource = "user_input";
                    }

                    db_insert("INSERT INTO kiss_attributes (cuid, `name`, `value`, `source`) VALUES (?,?,?,?)",$_SESSION["cuid"],$key,$value,$effectiveSource);
                }
            }
            $email = array_pop($_SESSION["attributes"]["email"]);
            if (isset($email)) {
                send_email_verifier($_SESSION["cuid"],$email);
            } else {
                core_log_error("No email address to send the invite to.");
            }
            audit_log($_SESSION["cuid"],$_SESSION["cuid"],"registration successful");
        }
    }
}
else {
    make_header($menuitems);
    make_error_message(core_lang("no_incoming_form"));
    make_footer();    
    exit(0);
}

###########################################################
# Redirect: 
# - if there is a redirect parameter, use that
# TODO - if no redirect parameter, but there is an after-reg landing page defined, use that
# - otherwise, welcome message
###########################################################

if (isset($_POST['attribute_redirect_url'])) {
    $url = $_POST['attribute_redirect_url'];
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        make_redirect(urldecode($url));
        exit(0);
    } else {
        make_header($menuitems);
        make_info_message(core_lang("account_successfully_saved"));
        make_info_message(core_lang("your_cuid",$_SESSION["cuid"]));
        make_error_message(core_lang("invalid_redirect_url", $url));
        make_footer();
        exit(0);
    }
}

# no redirect, we are generating html
make_header($menuitems);
make_info_message(core_lang("account_successfully_saved"));
make_info_message(core_lang("your_cuid",$_SESSION["cuid"]));
make_footer();