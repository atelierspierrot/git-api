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

use Library\Command;

/**
 * Largely inspired from <http://github.com/kbjr/Git.php>
 *
 * @author  piwi <me@e-piwi.fr>
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