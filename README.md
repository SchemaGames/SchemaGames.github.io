# sg-website

This is the project for the Schema Games website. This acts as a showcase for the free jam games, short stories, and other open source creative works we've assembled over the years.

This is currently hosted on a Gitlab Pages page.

## Website

The website uses an ancient breed of Angular.js on the frontend to render the pages dynamically, and uses data pulled from static json (migrated from the previously used database layer). In addition to the standard Angular.js libraries, there are plugins for:
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