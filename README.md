GIT API
=======

[![demonstration](http://img.ateliers-pierrot-static.fr/see-the-demo.svg)](http://sites.ateliers-pierrot.fr/git-api/)
[![documentation](http://img.ateliers-pierrot-static.fr/read-the-doc.svg)](http://docs.ateliers-pierrot.fr/git-api/)
A PHP API to get infos and manage a [GIT](http://git-scm.com/) distant or local repository.


Usage
-----

Working with a local repository clone:

```php
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
```

Working with a distant repository URL will create a local clone:

```php
// create a clone of a distant repo in a local dir
// if the local clone already exists, a `git pull` will be processed on it
$distant_repo = https://github.com/atelierspierrot/git-api
$local_repo = ~/repositories/git-api
$repo = \GitApi\GitApi::create($local_repo, $distant_repo)

// ... same as above
```


Installation
------------

For a complete information about how to install this package and load its namespace, 
please have a look at [our *USAGE* documentation](http://github.com/atelierspierrot/atelierspierrot/blob/master/USAGE.md).

If you are a [Composer](http://getcomposer.org/) user, just add the package to the 
requirements of your project's `composer.json` manifest file:

```json
"atelierspierrot/git-api": "@stable"
```

You can use a specific release or the latest release of a major version using the appropriate
[version constraint](http://getcomposer.org/doc/01-basic-usage.md#package-versions).

Please note that this package depends on the externals [PHP Patterns](http://github.com/atelierspierrot/patterns)
and [PHP Library](http://github.com/atelierspierrot/library).


Author & License
----------------

>    GIT API

>    http://github.com/atelierspierrot/git-api

>    Copyright (c) 2013-2016 Pierre Cassat and contributors

>    Licensed under the Apache 2.0 license.

>    http://www.apache.org/licenses/LICENSE-2.0

>    ----

>    Les Ateliers Pierrot - Paris, France

>    <http://www.ateliers-pierrot.fr/> - <contact@ateliers-pierrot.fr>


