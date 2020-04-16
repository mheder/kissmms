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

$cuid = load_user($kiss);

$data = query_matrix("SELECT actor_cuid, target_cuid, `action`,`data`,`timestamp` FROM kiss_audit_logs WHERE target_cuid = ? ORDER BY `timestamp` DESC LIMIT 100",$cuid);

make_header();
make_important_box(core_lang("your_audit_logs"));
make_audit_log_screen($data);
make_footer();

?>