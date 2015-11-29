# sg-website

This is the project for the Schema Games website. General architecture is outlined below:

This is currently hosted on a CentOS 6.7 box living in the Atlantic.net cloud.


## System

- The system uses nginx as its web server, which combined with DNS records has several sub-domains in use.
- php-fpm runs on the backend, to serve any php quickly.
- PostgreSQL is used as the database layer
- postfix is used as an outgoing email server.

## Website

Of the subdomains supported, there are
- An administrative dashboard of sorts (customized homebrew cruft)
- The GitLab and GitLab CI portals (now one and the same, use a .gitlab-ci.yml to configure ci jobs)
- The core website itself.

The website uses Angular.js on the frontend to render the pages dynamically, and php pages on the backend to serve data from the database layer below. In addition to the standard Angular.js libraries, there are plugins for:
- An Angular.js directive for Markdown rendering, called "showdown"
- Foundation.css libraries
- A (currently unused but functional) syntax highlighting package

Games are embedded in iframes that pull in the game content as an embedded unit.
"Things" operate in a similar style, with embedding of the html template containing each thing.
Many pieces of the site are still a work in progress, but it is working in its current state.

## Building and Deploying

A .gitlab-ci.yml file is used to build and deploy the website to the production webserver (currently, the same server as the Gitlab instance, though this does not necessarily need to be the case). Broadly, the steps currently taken are to:

- Setup all dependencies required for a build
- Combine all the css into a single file (cutting down the need for many network requests)
- Combine all of the Javascript into a single file and minify the result
- Copy over all files expected by the production server to the correct location