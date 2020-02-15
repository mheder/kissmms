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

function audit_log($actor_cuid, $target_cuid, $action, $data = "") {
    $connection = "Netaddr:" . $_SERVER['REMOTE_ADDR'];
    db_insert("INSERT INTO audit_logs (actor_cuid, target_cuid, `action`, connection, `data`) VALUES (?,?,?,?,?)",
        $actor_cuid,$target_cuid,$action,$connection, $data);
}


?>