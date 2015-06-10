# sg-website

This is the project for the Schema Games website. General architecture is outlined below:

This is currently hosted on a CentOS 6 box living in the Atlantic.net cloud.


## System

- The system uses nginx as its web server, which combined with DNS records has several sub-domains in use.
- php-fpm runs on the backend, to serve any php quickly.
- MySQL is used as the database layer, though other database structures (OrientDB or raw VCS filesystem contents) are under consideration.
- postfix is used as an outgoing email server.

# Website

Of the subdomains supported, there are
- Analytics (Piwik)
- An administrative dashboard of sorts (customized homebrew cruft)
- The GitLab and GitLab CI portals
- The core website itself.

The website uses Angular.js on the frontend to render the pages dynamically, and php pages on the backend to serve data from the MySQL database later below. In addition to the standard Angular.js libraries, there are plugins for:
- An Angular.js directive for Markdown rendering, called "showdown"
- An angular directive for integrating analytics
- Foundation.css libraries
- A (currently unused) syntax highlighting package

Games are embedded in iframes that pull in the game content as an embedded unit. "Things" operate in a similar style, with embedding of the html template containing each thing. Many pieces of the site are still a work in progress.