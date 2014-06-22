<?php
/**
 * PHP Git API package
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/git-api>
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
     * @param   string  $command command to run
     * @param   string  $repository
     * @param   bool    $force
     * @return  string
     * @throws  \GitApi\GitRuntimeException for any caught exception
     */
    public function run($command, $repository = null, $force = false)
    {
        list($stdout, $status, $stderr) = parent::run($command, $repository);
        if (!empty($stderr)) {
            throw new GitRuntimeException($stderr);
        }
        return $stdout;
    }

}

// Endfile