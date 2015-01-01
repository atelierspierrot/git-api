<?php
/**
 * This file is part of the GitApi package.
 *
 * Copyleft (ↄ) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/git-api>.
 */

namespace GitApi;

/**
 * Largely inspired from <http://github.com/kbjr/Git.php>
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