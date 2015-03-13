<?php
/**
 * This file is part of the GitApi package.
 *
 * Copyright (c) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * The source code of this package is available online at
 * <http://github.com/atelierspierrot/git-api>.
 */

namespace GitApi;

/**
 * Largely inspired from <http://github.com/kbjr/Git.php>
 *
 * @author  piwi <me@e-piwi.fr>
 */
class GitApi
{

    /**
     * Git executable location
     *
     * @var string
     */
    protected static $bin = '/usr/bin/git';

    /**
     * Sets git executable path
     *
     * @param string $path The `git` executable location
     */
    public static function setBin($path)
    {
        self::$bin = $path;
    }

    /**
     * Gets git executable path
     */
    public static function getBin()
    {
        return self::$bin;
    }

    /**
     * Create a new git repository
     *
     * @param string $repo_path The repository path
     * @param string $source The sources directory
     * @return object A `GitApi\Repository` instance
     */
    public static function create($repo_path, $source = null)
    {
        try {
            $repo = new Repository($repo_path);
            $remote = $repo->getRemoteOriginUrl();
            if ($remote===$source || $remote.'.git'===$source || $remote===$source.'.git') {
                $branch = $repo->getCurrentBranch();
                $repo->pull($remote, $branch);
                return $repo;
            }
        } catch (\Exception $e) {}
        return Repository::createNew($repo_path, $source);
    }

    /**
     * Open an existing git repository
     *
     * @param string $repo_path The repository path
     * @return object A `GitApi\Repository` instance
     */
    public static function open($repo_path)
    {
        return new Repository($repo_path);
    }

    /**
     * Checks if a variable is an instance of GitRepo
     *
     * @param mixed $var The variable to test
     * @return bool `true` if `$var` is a `GitApi\Repository` instance
     */
    public static function isRepository($var)
    {
        return ($var instanceof Repository);
    }

}

// Endfile