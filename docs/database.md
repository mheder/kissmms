## Creating the database

First, you will need an empty database and a database user to go along with it. 

For instance, if you have local root access to a mariadb, here are the steps to follow:
```
CREATE DATABASE `kissmms`;
CREATE USER `kissmms`@localhost IDENTIFIED BY 'putyourpasswordhere';
GRANT ALL privileges ON `kissmms`.* TO `kissmms`@localhost;
FLUSH PRIVILEGES;
```

Of course, alternatively you may use other tools, like phpadmin, adminer, etc. to create the database itself, or in a hosted environment it may be provided to you.

## Setting up the database schema
Within the database you just created and run the SQL commands in **kissmms_schema.sql** to create the tables:
```
cat docs/kissmms_schema.sql|mysql -u root -p
```
Or, if you are not using command line, but some other tool, copy-paste the contents of **kissmms_schema.sql** and run them as SQL.

## Translations
Finally, you have to add at least one translation. It is highly recommended that you add the English translation as a minimum.

Like in the previous step, you can the SQL commands.
```
cat docs/kissmms_translation_en.sql|mysql -u root -p
```
**If you don't do this step, there will be no textual labels whatsoever appearing on the web interface and you will get dozens of NOTICE messages about missing labels in your error log.**