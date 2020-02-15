# kissreg
This is kissreg, a simple (as in KISS) php application that implements an account regsitry and membership management system for AARC-style AAI deployments. 

* [Installation](docs/install.md)
* [Configuration](docs/config.md) including theming & branding
* [API documentation](docs/api.md)

# Feature highlights

## Platform and Language

kissreg is a **PHP+mysql/mariadb** application without many bells and whistles. This stack is chosen so that this application is easy to be co-hosted with COmanage or HEXAA which also rely on this stack. In certain deployments it may even make sense to use the same SAML SP Entity.

## User-facing features

### Account Registration

kissreg features an account registration page that will collect a pre-configured sed of attributes from the authentication source and enforce tha acceptance of an AUP. At the time of the registration a new Community User Identifier (CUID) is also generated.

### Account Management

In the account management screen users can adjust the values of certain attributes. The attributes editable by th user are configured in the attribute definitions in the [AdminTool](docs/config.md#AdminTool).

### Account Linking

kissreg account linking supports the connection between a known local account and a yet unknown remote account. This way any of the linked remote accounts will yield the same CUID. The account linking functionality however does not support linking of two master accounts (with two CUIDs)

## Attribute sources

Attributes may come from the web server environment (the usual way for a SAML stack), or from a pre-configured relying party in an AES256-encrypted, KMAC (SHA-3 "Keccak" Message Authentication Code) - signed message. For details, visit the [setup documentation](docs/config.md).

## Branding and Theming

kissreg is fully brandeable and themeable. Everything presented to the user is stored in a few files that may be overridden. For details, visit the [setup documentation](docs/config.md).

## Simple and Advanced translations

kissreg supports simple (**string-to-string**), advanced (**sprintf**-style) translation strings. These can be edited in the [AdminTool](docs/config.md#AdminTool).

## Append-only audit log

kissreg features an audit log functionality. Currently it will insert new rows into the database table **audit_logs**. To achieve anything more complex, like externally signed/timestamped audit logs, the function in **kiss/kiss_fns_audit.php** has to be overridden.

## API

For integrating with the proxy, kissreg provides an API. For details,visit the [API documentation](docs/api.md). 

# Licence
```
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

```