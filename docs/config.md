# Setting up the application

## Pre-requisites
* a working [installation](install.md)
* please read the **Terminology** (below)

## <a name="Terminology" style="color:black">Terminology</a>

The field of AAI has a tendency of having slightly diverging semantics for the same word, so please bear with us and read the following:
* **sysadmin** somebody that has file-system level access to this application. This person is the one that installs and configures the system and is able to change anything any time, except the audit logs (if there is an external timestamp service used)
* **platform admin**: somebody that has full access to an [AdminTool](install.md#AdminTool) that allows to change AUP and email texts, translations, attribute definitions, etc, as well as access to user data.
* **community admin**: a person that has limited access to an [AdminTool](install.md#AdminTool) and may set some user data like entitlements or possibly can import/export users, gain statistics.
* **end user**: a simple user
* **local account**: it is the main user account, over which this system has authority. It is an important distinction, since there are remote accounts as well. The local account is the only one that "officially" has attributes, that may be released. There can be multiple remote accounts connected to a local account and there might be an incoming attribute set recorded for them, but those are not part of the official user record.

## Source of the configurations
### conf.php
You can configure the basic aspects of the application in conf.php. These are the baseurl, some theming and branding, html, css files, database connection, other secrets, attribute mapping to environment variables, email and other basic aspects.

### <a name="AdminTool" style="color:black">AdminTool</a>
Every aspect of the system configuration except the basics enumerated in the section above is stored in dababase tables. 

The reason for this is to enable consistency checks, history and better reporting.

This includes AUP, Email, landing and other text elements, translations, attribute configuration. The **sysadmin** may manipulate these deployment-time by adjusting the sql files that populate the database, or after, deployment by manipulating the database manually. For the **plaform admin**, an admintool is provided that allows changing some SQL-based configuration without having sysadmin level access. 

The AdminTool is a separate application. It is optional. It can be co-located with the main app, be at a remote location or not used at all. There might be multiple AdminTools for the same deployment with different access levels.

The current AdminTool is based on [adminer editor](https://www.adminer.org/en/editor/) and can be configured to allow different degrees of freedom to the **platform admin**. It can also be configured to serve **community admins** in which case only basic import-export functions and some attribute changes are allowed.

## Branding and Theming
Every aspect of the application that is presented to the user can be easily modified. 

### Basics (css, html, images)

**Fundamental branding and theming bits** can be modified on the file system level. The logo images and the css file are relative to the ```$baseurl``` setting in **conf.php**. No '/' at the beginning required.

* **left-side logo**: in **conf.php** find the variable named ```$left-logo```. 
* **logo/banner on the top of the main content**: in **conf.php** find the variable named ```$head-logo```.  
* **additional css**: in conf.php find the variable named ```$customcss```. This css will be loaded after the main css.
* **main css**: if you want to replace the build-in css completely, you can override ```$css```. Note that in this case your new css has to provide styling for everything that is displayed.

**Note**: the above variables are are all loaded by **core/style/header.php** The following section explains how to override this file, and all the other html generator files completely. In this case you can still use access the variables above as global values, but obviously in your html themes you have to make sure they take effect. 

**Overriding html completely**: to override everything that is presented on the web, you can override any or all of the following three files
* **header.php**: contains html head, css/js loading, page title, etc, the left side bar and the top banner. 
* **footer.php**: contains the footer html.
* **elements.php**: contains functions that generate tables, forms, simple paragraphs, etc.

The default files are located at **core/style/<header|footer|elements>.php**. If you create your own files and put them under **customizations/<header|footer|elements>.php** respectively. If any of these customization files exist, it will take precedence and the original won't be loaded. The recommended method to create theming files is to copy the default and modify it.

**Favicon**: No branding is complete without a favicon. Simply replace **favicon.ico** in the root of the application.

### Advanced (texts, AUP, emails)

In the AdminTool, **Static Content** contains all the rich (html) content. For this kind of content,the translations are also kept here.

### Translations

Translations form shorter text elements are in the AdminTool Translatios table. The entries may be sprintf strings in which values will be replaced, like:
```
Email successfully verified: %s
```
Obviously, this only makes sense if the system actually passes on a parameter. As a reference, always look at the original English translation and use as many wildcards as the origical does.

## Attributes

The system manages attributes that may be **sourced** from the **authentication** session, from the **end user**, from an **administrator**. Values initially sourced from authentication system or from an administrator may or may not be overridden by the end user. 

### <a name="AttributeDefinitions" style="color:black">Attribute definitions</a>

Attributes are defined in the **attribute_defs** SQL table. The **sysadmin** normally configures these deployment-time by adjusting the SQL that sets up the database, or, after deployment, the **platform admin**, using the admin tool. 

Attributes are configured the following way (in table.column format)
* The **name** of the attribute is really an internal name-identifier. On the web (and possibly in emails) it will be represented to the user by using its translation key: ``attribute_<attribute name>``
* **attribute_defs.required**: (Y|N), default: N. To have a valid user account in the system, it is mandatory that this attribute has a value. 
* **attribute_defs.multival**: (Y,N), default: N
Multi-valued attribute.
* **attribute_defs.derived**: (Y|N), default: N
If derived, the value(s) will be looked up from the authentication sources. If an attribute is not multi-valued but there are multiple incoming values, the first one will be picked.
* **attribute_defs.customizable**: (Y,N), default: N
The user can set/override the value(s) of this attribute. 
*Note*: **displayed** takes precedence over **customizable**. Therefore, if you set an attribute to be customizable, but not displayed, it won't be presented on any web pages, and so the user cannot customize it after all. Hence, any customizable attribute must also be displayed.
* **attribute_defs.displayed**: (Y|N), default: N
If this is Y, there will be an attribute name - value(s) pair displayed on the registration and manamegement screens. If this is N, but the attribute is derived(Y), it will not be present on the registration form (and will beprotected from user tampering), but become part of the **local account**.
* **attribute_defs.validator_regex**: (varchar 256), default: Null
A regular expression to validate the value of the attribute (e.g. make sure email format is followed). If null, no validation happens.

**Important Notes**:
* If there is a **validator_regex**, invalid values will be discarded regardless of the source (user or incoming)
* If an attribute is **required**, it either has to be **derived** or **customizable** or both, otherwise there is nowhere to get the value from. 
* If an attribute is both **derived** and **customizable**, the incoming value will be pre-loaded into an editable field. If no incoming value, the field will be empty but editable. If it is also **required**, the user will be unable to save the attribute if empty.
* A **required** but **not customizable** attribute will be presented in an un-editable manner. If there is no incoming attribute where the value can be derived from, or the incoming value is invalid, an error is displayed.
* The variables **iuid** and **idsource** are special. These are handled specially and need not to be defined in this table.

### Attribute mappings

Attribute mappings are defined in **conf.php** in the ```$attribute_mappings``` variable. 

* the key for each entry is the internal "name" ad defined in the database in **attribute_defs** table, as explained at [attribute definitions](install.md#AttributeDefinitions) 
* mapping rules are are only relevant for **derived** attributes. Other rules are discarded. *Exceptionare the iuid and idsource, which are built-in attributes, need not to be defined and are always required and derived.* They still needs a mapping!
* if an attribute is displayed, don't forget about the translations
* the value for each entry is looked for in the apache environment ($_SERVER array), provided by the SAML stack or in an incoming kmac message
* the value of the array is multi-valued so that the attribute can have multiple incoming names. The final value will be the last non-empty value in this list. **NOTE** this is not about multi-valued attributes. Rather, it is about attributes that may have different names. 

Examples:
```
# one-to-one mapping example:
$kiss['attribute_mappings']["orcid"] = ["eduPersonOrcid"];
# many-to-one mapping example:
$kiss['attribute_mappings']["email"] = ["mail","email"];

# it is mandatory to have a mapping value for these:
$kiss['attribute_mappings']["iuid"] = ["iuid"];
$kiss['attribute_mappings']["source"] = ["shacHomeOrganization"];

#some common mappings
$kiss['attribute_mappings']["first_name"] = ["first_name"];
$kiss['attribute_mappings']["last_name"] = ["last_name"];
$kiss['attribute_mappings']["assurance"] = ["eduPersonAssurance"];

```

### community scope

Community scope is the suffix of the Community Identifier. This is the same for all users and should be community-specific. In the config, see the following line:
```
$kiss['community_scope'] = "@yourcommunity.org";
```