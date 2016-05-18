# Alma-Patron-Upload
Use this to add patron information into ExLibris Alma from a csv file. 

# Update the following

## includes/createXML.php
### Where to save files
* Line 8 - $target_path
* Line 434 - $path
* Line 436 - FILEPATH
### Other notes
* Line 60 - Update user groups to match yours
* You will then need to update any user codes

## includes/top.php
* Line 49 - FILEPATH
* Line 53 - FILEPATH

## index.php
* Update instructions to match your own.
* Line 7 - Update link to where you obtain csv file of patron information.

## process.php
* Line 14 - Link to Alma application
* Update any instructions
