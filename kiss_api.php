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

//echo $_SERVER['REQUEST_URI'] . "<br/>";

$api_actual = str_replace($api_basepath,"",$_SERVER['REQUEST_URI']);

$apicall = explode("/",$api_actual);

if ($apicall[0] == "master-accounts" and $apicall[1] == "by-account-id") {
    $iuid = $apicall[2];
    core_log_debug("single IUID lookup:".$iuid);
    $remote_accounts = get_remote_accts_for_iuids(array($iuid));
    $cuid = get_cuid_for_remote_accts($remote_accounts);
    echo $cuid;
}

?>