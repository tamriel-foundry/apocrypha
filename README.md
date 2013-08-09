# Apocrypha
_Custom Wordpress theme for [TamrielFoundry.com](http://www.tamrielfoundry.com)_

** !!! DOCUMENTATION IS A WORK IN PROGRESS !!! **

### Development

Automation via [Grunt](http://gruntjs.com) has been added to the Apocrypha theme project. The following documentation should help anyone new to working with Grunt.

##### File Structure

This project uses a `src/` directory (_short for source_) and a `dist/` directory (_short for distribution_) file structure. **Only files in the** `src/` **directory should be edited during development**. Files in the `dist/` directory are generated automatically by the build and release scripts, and should never be edited directly.

For installation into your local development Wordpress you will want to copy and paste the `dist/apocryphatwo/` folder into your local Wordpress themes folder.

##### Getting up and running with Grunt

You will need first download and install [Node.js](http://nodejs.org). Installing Node.js is very easy as it provides a packaged installer for each operating system (Window, OSX, Linux).

Node.js will also install Node Package Manager (NPM). Grunt runs on Node.js, and uses NPM to help install/uninstall Grunt packages and manage dependencies.

Once you have Node.js installed, head to the [Grunt - Getting started page](http://gruntjs.com/getting-started). You will only need to worry about following the instructions up to installing Grunt and Grunt-CLI.

I've gone ahead and already setup some Grunt tasks, so once you have Grunt installed you'll be good to go. The last thing you'll need to do is from the Command Line (Windows) or Terminal (OSX, Linux) run the command `npm install`. This will automatically install all of the development dependencies for using Grunt that are contained with the `package.json` file.

##### Grunt tasks

From your Command Line (Windows) or Terminal (OSX, Linux) you can run any of the following commands:

`grunt build`: This will run all of the linting and minification tasks and push new versions of the files into `dist/`.
`grunt watch`: You can run this command at the beginning of a development session to have Grunt actively watch files for changes and run the tasks on file save.

** Use the following tasks commands only for release!!! **

If you haven't done your first release, make sure and check with Zaydok so he can walk you through it the first time. It is easy to do, but there are certain steps that need to be taken that may be confusing the first time around.

`grunt rel:major`: This will do a major version release adding a Git release tag and commit which will be instantly pushed up to the remote repo. This should only be used when jumping a full major version, i.e. 2.x.x to 3.0.0.

`grunt rel:minor`: This will do a minor version release adding a Git release tag and commit which will be instantly pushed up to the remote repo. This should only be used when jumping a full minor version, i.e. 2.0.x to 2.1.0.

`grunt rel:patch`: This will do a patch version release adding a Git release tag and commit which will be instantly pushed up to the remote repo. This should only be used when just release a minor patch, i.e. 2.0.0 to 2.0.1.

`grunt rel:prerelease`: This will do a prerelease version release adding a Git release tag and commit which will be instantly pushed up to the remote repo. This should only be used when you only want to do a prerelease, i.e. 2.0.0 to 2.0.0-1.

###### Release Info

The version number system goes off [SemVer](http://semver.org). It is highly recommended that you take time to read through the SemVer site so you can understand what the proper release task to use would be given the amount of changes made to the repo. 

It is also important to note that not every commit needs to be a release. In fact very few should be releases. A release should only be done when the theme is at a point where it is ready to be rolled out to the live site.

** !!! DOCUMENTATION IS A WORK IN PROGRESS !!! **