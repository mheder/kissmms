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
$incoming_mapped_attributes = load_attributes($attribute_mappings);

sanity_check_incoming_attributes($incoming_mapped_attributes);

$remote_accounts = get_remote_accts_for_iuids($incoming_mapped_attributes["iuid"]);

$cuid = get_cuid_for_remote_accts($remote_accounts);

# unknown user
if (empty($remote_accounts)) {
    make_header($menuitems);
    make_error_message(auxi_lang("unknown_user"));
    make_footer();
    exit(0);
} 

$data = query_matrix("SELECT actor_cuid, target_cuid, `action`,`data`,`timestamp` FROM audit_logs WHERE target_cuid = ? ORDER BY `timestamp` DESC LIMIT 100",$cuid);

make_header($menuitems);
make_important_box(auxi_lang("your_audit_logs"));
make_audit_log_screen($data);
make_footer();

?>