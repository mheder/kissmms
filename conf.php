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

###########################################################
# baseurl
###########################################################
# baseurl for static content like images, style sheets, etc.
# No trailing '/' character please
###########################################################

# replace this to your FQDN!
$baseurl="http://localhost/dev/kissmms";

###########################################################
# branding
###########################################################
# logo, css, relative to baseurl.  
# No need for '/' at the beginning.
#
# See docs/install.md "Branding" section!
#
###########################################################

$left_logo = "img/logo.jpg";
# $left_logo = "customizations/logo_reg.jpg";
$head_logo = "customizations/yourbrand_logo.png";

# "core/style/style.css" is the primary css file
# if you change it it will be "overridden". You can also 
# use additional css with customcss (see next item)
$css = "core/style/style.css";

# $customcss = "customizations/extrastyles.css";

###########################################################
# Menuitems
###########################################################
#
# You can hide things so that 
# they will need explicit link to access
#
$menuitems = array("index.php","audit_logs.php","acc_link.php","aup_new_user.php");

###########################################################
# Database connection info
###########################################################

$db_host = "localhost"; 
$db_user = "kissmms";
$db_pass = "your-kissmms-database-password";
$db_name = "kissmms";

###########################################################
# language codes
###########################################################
# Enumerate the languages you support by a lang code.
# You will have to write the proper strings for all 
# languages.
# See docs/install.md "Translations" section!
###########################################################

$ls_languages = array("en");

###########################################################
# email settings
###########################################################
#
# See docs/install.md "Email" section!
#
###########################################################

# the From: field of the emails sent out by us
$email_from = "root@localhost";

###########################################################
# log settings
###########################################################
#
# See docs/install.md "Log" section!
#
###########################################################

# debug log
$debug_log = true;

# trace log: very aggressive, really use it as last resort
$trace_log = false;

###########################################################
# attribute mappings
###########################################################
#
# See docs/install.md "Attribute Mappings" section!
#
###########################################################

$attribute_mappings = array();

# it is mandatory to have a mapping value for these:
$attribute_mappings["iuid"] = ["schacPersonalUniqueCode"];
$attribute_mappings["source_id"] = ["schacHomeOrganization"];
    
# one-to-one mapping example:
$attribute_mappings["orcid"] = ["eduPersonOrcid"];
# many-to-one mapping example:
$attribute_mappings["email"] = ["mail","email"];

#some common mappings
$attribute_mappings["first_name"] = ["givenName", "firstName"];
$attribute_mappings["last_name"] = ["sn", "lastName"];
$attribute_mappings["assurance"] = ["assurance"];


###########################################################
# Account Linking
###########################################################
#
# See docs/install.md "Account linking" section!
#
###########################################################
$shib_cookie_name = "_shibsession";
$forceauthn_header = "Location: /Shibboleth.sso/Login?forceAuthn=true&target=" . urlencode($baseurl. '/acc_link.php');

###########################################################
# API
###########################################################
#
# See docs/install.md "API" section!
#
###########################################################
$api_basepath = "/dev/kissmms/api.php/";

?>