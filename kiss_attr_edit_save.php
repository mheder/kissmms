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

$attribute_defs = load_attribute_definitions();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach($_POST as $iname => $value ) {
        
        $name = base64_decode($iname);

        if(isset($attribute_defs[$name]) and $attribute_defs[$name]["customizable"] == "Y") {
            attr_validate($attribute_defs[$name],$value);
            db_update("REPLACE kiss_attributes (cuid,`name`,`source`,`value`) VALUES (?,?,'user_input',?) ",$cuid,$name,$value);            
        } 
        else {
            make_header();
            make_error_message(core_lang("you_cannot_edit_this_attribute"));
            make_footer();    
            exit(0);
        }
        
    }
    audit_log($_SESSION["cuid"],$_SESSION["cuid"],"attribute modified",$name);
    make_redirect("index.php");
    exit(0);
} else {
    make_header();
    make_error_message(core_lang("no_incoming_form"));
    make_footer();    
    exit(0);
}


?>