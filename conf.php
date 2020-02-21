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
$core['baseurl']="http://localhost/dev/kissmms";

###########################################################
# branding
###########################################################
# logo, css, relative to baseurl.  
# No need for '/' at the beginning.
#
# See docs/config.md "Branding" section!
#
###########################################################

$core['left_logo'] = "img/logo.jpg";
# $left_logo = "customizations/logo_reg.jpg";
$core['head_logo'] = "customizations/yourbrand_logo.png";

# "core/style/style.css" is the primary css file
# if you change it it will be "overridden". You can also 
# use additional css with customcss (see next item)
$core['css'] = "core/style/style.css";

# $customcss = "customizations/extrastyles.css";

###########################################################
# Menuitems
###########################################################
#
# You can hide things so that 
# they will need explicit link to access
#
$core['menuitems'] = array("index.php","audit_logs.php","acc_link.php","aup_new_user.php");

###########################################################
# Database connection info
###########################################################

#replace these to the actual db credentials!
$core['db_host'] = "localhost"; 
$core['db_user'] = "kissmms";
$core['db_pass'] = "your-kissmms-database-password";
$core['db_name'] = "kissmms";

###########################################################
# language codes
###########################################################
# Enumerate the languages you support by a lang code.
# You will have to write the proper strings for all 
# languages.
# See docs/config.md "Translations" section!
###########################################################

$core['ls_languages'] = array("en");

###########################################################
# log settings
###########################################################
#
# See docs/config.md "Log" section!
#
###########################################################

# debug log
$core['debug_log'] = true;

# trace log: very aggressive, really use it as last resort
$core['trace_log'] = false;

###########################################################
# attribute mappings
###########################################################
#
# See docs/config.md "Attribute Mappings" section!
#
###########################################################

$kiss['attribute_mappings'] = array();

# it is mandatory to have a mapping value for these:
$kiss['attribute_mappings']["iuid"] = ["schacPersonalUniqueCode"];
$kiss['attribute_mappings']["source_id"] = ["schacHomeOrganization"];
    
# one-to-one mapping example:
$kiss['attribute_mappings']["orcid"] = ["eduPersonOrcid"];
# many-to-one mapping example:
$kiss['attribute_mappings']["email"] = ["mail","email"];

#some common mappings
$kiss['attribute_mappings']["first_name"] = ["givenName", "firstName"];
$kiss['attribute_mappings']["last_name"] = ["sn", "lastName"];
$kiss['attribute_mappings']["assurance"] = ["assurance"];

###########################################################
# Account Linking
###########################################################
#
# See docs/install.md "Account linking" section!
# 
###########################################################
$kiss['shib_cookie_name'] = "_shibsession";
$kiss['forceauthn_header'] = "Location: /Shibboleth.sso/Login?forceAuthn=true&target=" . urlencode($baseurl. '/acc_link.php');

###########################################################
# API
###########################################################
#
# See docs/install.md "API" section!
#
###########################################################
$kiss['api_basepath'] = "/dev/kissmms/api.php/";

###########################################################
# email settings
###########################################################
#
# See docs/config.md "Email" section!
#
###########################################################

# the From: field of the emails sent out by us
$kiss['email_from'] = "root@localhost";

###########################################################
# community scope
###########################################################
#
# See docs/config.md "scope" section!
#
###########################################################
# community scope for CUID generation
$kiss['community_scope'] = "@yourcommunity.org";

?>