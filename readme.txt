=== CSV to ACF Importer ===
Contributors: @matteodf
Donate link: https://matteodefilippis.com/
Tags: acf, csv, import, importer, csv to acf, csv to acf importer, csv to acf import, csv
Requires at least: 5.8
Tested up to: 6.1.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Import CSV data into new pages creating proper ACF fields.

== Description ==

This plugin allows you to import CSV data into new pages creating proper ACF fields.
It takes the first row of the CSV file as the field names and the following rows as the data.
The plugin will create a new ACF group for each CSV file and will create a new page for each row of data.
Each ACF group will have a field for each column of the CSV file. The user can choose the field type for each field. **Currently supports text, image, link and oEmbed fields.**
After the creation of the fields, the plugin will import the data into the new pages. The user can choose if the pages should be published or not.

== Installation ==

1. Upload `csv-to-acf` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the plugin settings page and upload your CSV file

== Changelog ==

= 0.1.0 =
-   First alpha release.
