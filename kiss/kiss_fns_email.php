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

function generate_token() {
    return bin2hex(random_bytes(24));
}

function send_email_verifier($cuid, 
    $email, 
    $contkey = "email_verify", 
    $aname = null, 
    $avalue = null) {
    
    $token = generate_token();

    # TODO handle "type (verify/invite)" or drop it entirely"
    if (!isset($aname)) {
        db_insert("INSERT INTO kiss_email_tokens (`type`, sender_cuid, email, token) VALUES ('verify',?,?,?)",$cuid,$email,$token);
    } else {
        db_insert("INSERT INTO kiss_email_tokens (`type`, sender_cuid, email, token,granted_attribute_name,granted_attribute_value) VALUES ('verify',?,?,?,?,?)",$cuid,$email,$token,$aname,$avalue);
    }

    $link = $GLOBALS['core']['baseurl'] . "/kiss_email_verify.php?token=" . $token;

    $content = core_static_content($contkey, $link);

    core_send_email($email,core_lang($contkey . "_subject"),$content, true);

}

?>