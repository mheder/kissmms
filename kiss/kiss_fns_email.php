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

function send_email_verifier($cuid,$email) {
    $token = generate_token();

    db_insert("INSERT INTO kiss_email_tokens (`type`, sender_cuid, email, token) VALUES ('verify',?,?,?)",$cuid,$email,$token);

    $link = $GLOBALS['baseurl'] . "/kiss_email_verify.php?token=" . $token;

    $content = core_static_content("email_verify", $link);

    core_send_email($email,core_lang("email_verify_subject"),$content, true);

}

?>