# API

The kissmms api provides programmatic access to the kissmms database. Since there are no end users envisioned for the API, it is protected by a simple header-based authentication mechanism. The only remote clients of the API is supposed to be the proxy or an MMS.

## GET CUID by a single IUID
```
GET <api_basepath>/master-accounts/by-account-id/<iuid>
```
Queries the database for a single IUID value.

*Return value*: **CUID** in plain text or an empty string