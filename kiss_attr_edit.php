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

$cuid = load_user($kiss);

$attrname = filter_input(INPUT_GET, 'attrname', FILTER_SANITIZE_STRING);

if(!isset($attribute_defs[$attrname])) {
    make_header();
    make_error_message(core_lang("undefined_attribute",$attrname));
    make_footer();
    exit(0); 
}

if($attribute_defs[$attrname]["customizable"] != "Y") {
    make_header();
    make_error_message(core_lang("attr_not_editable",$attrname));
    make_footer();
    exit(0); 
}

# This edits the first one. TODO multi-value edit
$attrdata = query_matrix("SELECT name,source,value FROM kiss_attributes WHERE name = ? AND cuid = ? AND source = 'user_input' LIMIT 1",$attrname, $cuid);

make_header();
make_important_box(core_lang("attr_edit_head"));
make_text(core_lang("your_cuid",$cuid));
if (!empty($attrdata)) {
    make_attr_edit_form($attrdata);
} else {
    make_attr_edit_form(array(array("name" => $attrname, "source" => "user_input", "value" =>"")));
}
make_footer();

?>