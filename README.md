GIT API
=======

A PHP API to get infos and manage a [GIT](http://git-scm.com/) distant or local repository.


## Usage

Working with a local repository clone:

    // open a repo
    $local_repo = ~/repositories/git-api
    $repo = \GitApi\GitApi::open($local_repo)

    // commiters list
    $repo->getCommitersList()

    // branches list
    $repo->getBranchesList()

    // commits list
    $repo->getCommitsList()

    // current branch
    $repo->getCurrentBranch()

    // description
    $repo->getDescription()

    // tags list
    $repo->getTagsList()

    // last commit infos
    $repo->getLastCommitInfos()

    // tree
    $repo->getTree()

    // tree for first dir
    $repo->getTree('HEAD', $first_dir)

    // files info
    $repo->getFilesInfo()

    // recursive tree
    $repo->getRecursiveTree()

    // commits history
    $repo->getCommitsHistory()

Working with a distant repository URL will create a local clone:

    // create a clone of a distant repo in a local dir
    // if the local clone already exists, a `git pull` will be processed on it
    $distant_repo = https://github.com/atelierspierrot/git-api
    $local_repo = ~/repositories/git-api
    $repo = \GitApi\GitApi::create($local_repo, $distant_repo)
    
    // ... same as above


## Installation & usage

You can use this package in your work in many ways.

First, you can clone the [GitHub](https://github.com/atelierspierrot/git-api) repository
and include it "as is" in your poject:

    https://github.com/atelierspierrot/git-api

You can also download an [archive](https://github.com/atelierspierrot/git-api/downloads)
from Github.

Then, to use the package classes, you just need to register the `GitApi` namespace directory
using the [SplClassLoader](https://gist.github.com/jwage/221634) or any other custom autoloader:

    require_once '.../src/SplClassLoader.php'; // if required, a copy is proposed in the package
    $classLoader = new SplClassLoader('GitApi', '/path/to/package/src');
    $classLoader->register();

If you are a [Composer](http://getcomposer.org/) user, just add the package to your requirements
in your `composer.json`:

    "require": {
        ...
        "atelierspierrot/git-api": "dev-master"
    }


## Development

To install all PHP packages for development, just run:

    ~$ composer install --dev

A documentation can be generated with [Sami](https://github.com/fabpot/Sami) running:

    ~$ php vendor/sami/sami/sami.php render sami.config.php

The latest version of this documentation is available online at <http://docs.ateliers-pierrot.fr/git-api/>.


## Author & License

>    GIT API

>    http://github.com/atelierspierrot/git-api

>    Copyleft (ↄ) 2013-2015 Pierre Cassat and contributors

>    Licensed under the GPL Version 3 license.

>    http://opensource.org/licenses/GPL-3.0

>    ----

>    Les Ateliers Pierrot - Paris, France

>    <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>


