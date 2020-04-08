# Installation
## requirements
* recent php5 or php7
* database access
* SAML stack to protect access to the web pages (Shibboleth or simplesamlphp)

## preparing the web server

kissreg needs to be protected by SAML middleware like shibboleth and the incoming variables need to be mapped to web server environment variables. 

When the user visits the web pages of kissreg, the following attributes are necessary:
* **iuid** (one or more)
* **source_id** (e.g. the entityId the user is originated in the current session)
  
Moreover, for any active accounts there needs to be a verified email address. Therefore, the *internal* attribute names of **iuid**, **source_id** and **email** cannot be changed as there are explicit source code references to them. Since these names are internal only, there should be no need to do that. Also, in the **attribute_mapping** configuration **iuid** and **source_id** need to be included as these cannot be supplied by the user. 

The key of the attribute mappings should be the attribute internal names, while the value(s) are the web server environment variable names to source from.

## Installing the code

To install the code, set up a location protected by your SAML middleware and then simply unzip the code bundle and edit **conf.php** as described in the [setup documentation](config.md). You may brand and theme your deployment by editing the appropriate files (see the setup docs).
For production developments always use the latest bundle available and only install the latest git if you are developing.

# Database schema

First, you need to create a mysql/mariadb database and get a user to it, which you then supply in **conf.php**. 

**mysqli**, the php-mysql implementation kissreg uses relies on connection pool-like behavior called "persistent" connections by default. Makes sure that is indeed the case and this feature is not turned off in (what you want is: **mysqli.allow_persistent = On**), otherwise you will suffer a performance hit.

Then, log in to the database and create the schema  and fill in the attribute specifications and translations with the sql commands documented in:
[database documentation](database.md)

# Account Linking

For account linking the crucial point is to be able to delete your SAML session while retaining the application session and then force reauthentication. In case of Shibboleth this is achieved by deleting the session cookie (this is a rare event and shib will clean up after expiration anyways) and making the Shib handler do the reauth.

```
$shib_cookie_name = "_shibsession";
$forceauthn_header = "Location: /Shibboleth.sso/Login?forceAuthn=true&target=" . urlencode($baseurl. '/kiss_acc_link.php');
```

# API

The API should not be SAML-protected, instead it should rely on Header-based authentication or on some more advanced method. At any rate, API authentication is handled by the web server.

The following example turns off Shibboleth for the API location then establishes http basic authentication, which is good enough over https and with an ip filtering. For more open setups you may consider stronger API auth.

```
        <Location /<yourpath>/kissreg/kiss_api.php>
                ShibRequestSetting requireSession false
                AuthType Basic
                AuthName "Restricted Files"
                AuthBasicProvider file
                AuthUserFile "/etc/apache2/api.pwd"
                <RequireAll>
                        Require ip 127.0.0.1
                        Require user api1
                </RequireAll>
        </Location>
```