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
$incoming_mapped_attributes = load_attributes($kiss['attribute_mappings']);

sanity_check_incoming_attributes($incoming_mapped_attributes);

$remote_accounts = get_remote_accts_for_iuids($incoming_mapped_attributes["iuid"]);

$cuid = get_cuid_for_remote_accts($remote_accounts);

# unknown user
if (empty($remote_accounts)) {
    make_header($menuitems);
    make_error_message(core_lang("unknown_user"));
    make_footer();
    exit(0);
} 

$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);

$email = query_scalar("SELECT email FROM kiss_email_tokens WHERE sender_cuid = ? AND token = ? AND consumed_at IS NULL",$cuid,$token);

$consumed_email = query_scalar("SELECT email FROM kiss_email_tokens WHERE sender_cuid = ? AND token = ?",$cuid,$token);

if (empty($email)) {
    make_header($menuitems);
    if (!empty($consumed_email)) {
        make_error_message(core_lang("token_already_consumed",$consumed_email));
    }
    else {
        make_error_message(core_lang("token_error"));
    }
    make_footer();
    exit(0);  
}

db_update("UPDATE kiss_email_tokens SET consumed_at = CURRENT_TIMESTAMP() WHERE sender_cuid = ? AND token = ?",$cuid,$token);

db_update("UPDATE kiss_attributes SET assurance = 'verified' WHERE cuid = ? AND `name` = 'email' AND value = ?",$cuid,$email);

make_header($menuitems);
make_info_message(core_lang("email_verified",$email));
make_footer();
exit(0);

?>