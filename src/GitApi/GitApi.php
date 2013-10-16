<?php
/**
 * CarteBlanche - PHP framework package - Git API bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/carte-blanche>
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
	public static function &create($repo_path, $source = null)
	{
		return Repository::create_new($repo_path, $source);
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