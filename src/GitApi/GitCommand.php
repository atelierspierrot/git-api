<?php
/**
 * CarteBlanche - PHP framework package - Git API bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/carte-blanche>
 */

namespace GitApi;

use Library\Command;

/**
 * Largely inspired from <http://github.com/kbjr/Git.php>
 */
class GitCommand extends Command
{

	/**
	 * Run a command in the git repository
	 *
	 * Accepts a shell command to run
	 *
	 * @param   string  command to run
	 * @return  string
	 */
    public function run($command, $repository = null, $force = false)
	{
        list( $stdout, $status, $stderr ) = parent::run($command, $repository);
        if (!empty($stderr)) {
            throw new GitRuntimeException($stderr);
        }
		return $stdout;
	}

}

// Endfile