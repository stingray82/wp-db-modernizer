WP DB Modernizer
================

A one-time-use WordPress plugin to modernize your database for full emoji,
Unicode, and multibyte character support.

 

This plugin:

\- Converts all core WordPress tables to use `utf8mb4_unicode_ci`

\- Fixes invalid `DATETIME` defaults in `wp_posts`

\- Automatically creates a full `.sql` backup in `wp-content/backups/`

\- Disables itself after running once - **Safe and recommended  to delete after
use**

 

Why Use This?
-------------

Modern WordPress features (like emojis, special characters, and full
multilingual support) require `utf8mb4` charset.

Older sites using `utf8` (actually `utf8mb3`) may run into: - Emoji save
failures 😭 - Database errors on certain characters - Incompatibility with block
editor plugins like Code Block Pro when saving to the post database

 

What It Does
------------

1.  **Backup** your database using `mysqldump` (if available)

2.  **Fix datetime columns** in `wp_posts` that use invalid defaults
    (`0000-00-00 00:00:00`)

3.  **Convert all WordPress tables** (those with the configured prefix, usually
    `wp_`) to:

    -   Character Set: `utf8mb4`

    -   Collation: `utf8mb4_unicode_ci`

4.  **Sets a flag** to ensure it only runs once

5.  Displays a success notice in the admin panel with the backup path

 

Installation
------------

1.  Drop the plugin into your WordPress install:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
wp-content/plugins/wp-db-modernizer/
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1.  File structure should look like:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
wp-db-modernizer/
├── wp-db-modernizer.php
├── readme.md
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1.  Go to **WP Admin \> Plugins** and **Activate** "WP DB Modernizer"

 

 

Cleanup
-------

-   Once the operation completes, you’ll see a success notice.

-   The plugin **won’t run again** unless manually reset.

-   You can now **deactivate and delete** the plugin.

 

Backup Location
---------------

Backups are saved as:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
wp-content/backups/db-backup-YYYY-MM-DD_HH-MM-SS.sql
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

 

Requirements
------------

-   PHP `exec()` must be enabled (for backups via `mysqldump`)

-   Database user must have `ALTER` privileges

-   Works with MySQL 5.7+, MariaDB 10.x

 

Disclaimer
----------

Use at your own risk. Always back up your site before running database-altering
scripts.

 

📝 License
---------

 

This plugin is free software: you can redistribute it and/or modify it under the
terms of the **GNU General Public License** as published by the Free Software
Foundation, either version 2 of the License, or (at your option) any later
version.

This plugin is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the [GNU General Public
License](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html) for more
details.
