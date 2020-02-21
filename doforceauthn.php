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

# in case of shibboleth,
# instead of curl-ing the logout url (uncertain) we get rid of the shib session cookie
# harmless: account linking is rare, and the stale session will expire
if (isset($shib_cookie_name)) {
    foreach ($_COOKIE as $key => $value) {
            if(startsWith($key,$shib_cookie_name)) {
                    unset($_COOKIE[$key]);
            }
    }
}

# todo : simplesaml logout

header($forceauthn_header);

?>