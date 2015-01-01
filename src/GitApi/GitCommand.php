<?php
/**
 * PHP Git API package
 * Copyleft (ↄ) 2013-2015 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/git-api>
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