# WoW Raid Organizer
This application is a plugin for Wordpress that adds functionality to quickly and easily record your raid group members' attendance and automatically record significant loot aquisitions. You can use this information to see players' attendance trends and award (or punish!) them for it! Mistakes can be made of course, so the interface allows you to easiy change information, and even allows the users to submit disputes for any information they believe to be incorrect. For information on how each feature works, check out the [wiki](https://github.com/sawprogramming/WoW-Raid-Manager/wiki).
![Main Page Screenshot](http://i.imgur.com/buNMl3m.png)

# Installation
The most recent builds are included in the [dist](https://github.com/sawprogramming/WoW-Raid-Manager/tree/master/dist) folder of the repository. Simply go to it, download the version you want, and go through the usual steps to install a plugin using it in Wordpress. The dev version contains the full code while the prod version contains the minified code (recommended).
Admins have the ability to add and remove players from your raid group as well as record the attendance for each of the members. 

You will find the admin controls in the admin panel of WordPress. To show the user page, go to the 'Pages' section of WordPress' admin panel, select the page you want it to appear on, and select the 'Attendance' template for that page.

# Technical Details
- This project uses a RESTful service to provide the data to the AngularJS front-end to provide a smooth, single page app experience to the users.
- To build the production version yourself, you'll need to have NPM installed. With that, just navigate to the directory of the project and type "npm install .", which whill install all of the modules needed to minify and build the code. Batch files are provided to run the commands necessary to build the application after the packages have been installed.
- Pending any bug fixes as they appear (and updates for the appropriate content that gets added), I am finished coding this application. No further features are planned, but suggestions are welcome in the [issues](https://github.com/sawprogramming/WoW-Raid-Manager/issues) section of this github.
