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
class Repository
{

	protected $repository_path = null;
	protected $git_console = null;

    public static $full_pretty_format = 'commit:%H%ncommit-abbrev:%h%ntree:%T%ntree-abbrev:%t%nparents:%P%nparents-abbrev:%p%nauthor_name:%an%nauthor_email:%ae%nauthor_date:%ad%ncommiter_name:%cn%ncommiter_email:%ce%ncommiter_date:%cd%ndate:%ai%nnotes:%N%ntitle:%s%nmessage:%b%n';

	/**
	 * Constructor
	 *
	 * @param   string  repository path
	 * @param   bool    create if not exists?
	 */
	public function __construct($repository_path = null, $createNew = false, $_init = true)
	{
	    $this->setGitConsole( new GitCommand );
		if (is_string($repository_path)) {
			$this->setRepositoryPath($repository_path, $createNew, $_init);
		}
	}

    public function setGitConsole(GitCommand $console)
    {
        $this->git_console = $console;
        return $this;
    }

    public function getGitConsole()
    {
        return $this->git_console;
    }

	/**
	 * Create a new git repository
	 *
	 * @param   string  repository path
	 * @param   string  directory to source
	 * @throws Excpetion Throws an `Exception` if the path is already a GIT repository
	 */
	public static function &createNew($repository_path, $source = null) 
	{
		if (is_dir($repository_path) && file_exists($repository_path."/.git") && is_dir($repository_path."/.git")) {
			throw new \Exception(
			    sprintf('"%s" is already a git repository', $repository_path)
			);
		} else {
			$repo = new self($repository_path, true, false);
			if (is_string($source)) {
				$repo->cloneFrom($source);
			} else {
				$repo->run('init');
			}
			return $repo;
		}
	}

	public function getRepositoryPath()
	{
	    return $this->repository_path;
	}
	
	/**
	 * Set the repository's path
	 *
	 * Accepts the repository path
	 *
	 * @access  public
	 * @param   string  repository path
	 * @param   bool    create if not exists?
	 * @return  void
	 */
	public function setRepositoryPath($repository_path, $createNew = false, $_init = true)
	{
		if (is_string($repository_path)) {
			if ($new_path = realpath($repository_path)) {
				$repository_path = $new_path;
				if (is_dir($repository_path)) {
					if (file_exists($repository_path."/.git") && is_dir($repository_path."/.git")) {
						$this->repository_path = $repository_path;
					} else {
						if ($createNew) {
							$this->repository_path = $repository_path;
							if ($_init) {
								$this->run('init');
							}
						} else {
							throw new \Exception(
							    sprintf('"%s" is not a git repository!', $repository_path)
							);
						}
					}
				} else {
					throw new \Exception(
					    sprintf('"%s" is not a directory', $repository_path)
					);
				}
			} else {
				if ($createNew) {
					if ($parent = realpath(dirname($repository_path))) {
						mkdir($repository_path);
						$this->repository_path = $repository_path;
						if ($_init) $this->run('init');
					} else {
						throw new \Exception('Cannot create repository in non-existent directory');
					}
				} else {
					throw new \Exception(
					    sprintf('"%s" does not exist', $repository_path)
					);
				}
			}
		}
	}

	/**
	 * Tests if git is installed
	 *
	 * @access  public
	 * @return  bool
	 */
	public function testGit() 
	{
		$descriptorspec = array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);
		$pipes = array();
		$resource = proc_open(GitApi::getBin(), $descriptorspec, $pipes);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		foreach ($pipes as $pipe) {
			fclose($pipe);
		}

		$status = trim(proc_close($resource));
		return ($status != 127);
	}

	/**
	 * Run a git command in the git repository
	 *
	 * Accepts a git command to run
	 *
	 * @access  public
	 * @param   string  command to run
	 * @return  string
	 */
	public function run($command)
	{
		return $this->git_console->run(GitApi::getBin()." ".$command, $this->getRepositoryPath());
	}

    public function getCommitsList()
    {
		$history_stack = explode("\n", $this->run("log --pretty=oneline"));
		$history=array();
		if (!empty($history_stack)) {
    		foreach ($history_stack as $entry) {
    		    if (strlen($entry)) {
        		    $history[ substr($entry, 0, strpos($entry, ' ')) ] = substr($entry, strpos($entry, ' ')+1);
        		}
    		}
		}
        return $history;
    }

    protected function _parseCommitLog( $log_entry )
    {
        $commit=array();
        $response_items = explode("\n", $log_entry);
        if (!empty($response_items)) {
            foreach ($response_items as $i=>$subentry) {
                $entry_index = substr($subentry, 0, strpos($subentry, ':'));
                $entry_value = substr($subentry, strpos($subentry, ':')+1);
                if (strlen($subentry) && !$in_message) {
                    $commit[ $entry_index ] = $entry_value;
                    if ($entry_index==='date' || strpos($entry_index, 'date')) {
                        $commit[ str_replace('date', 'DateTime', $entry_index) ] = new \DateTime( $entry_value );
                    } elseif ($entry_index==='message') {
                        $in_message = true;
                    }
                } elseif (strlen($subentry) && $in_message) {
                    $commit['message'] .= "\n".$subentry;
                }
            }
        }
        return $commit;
    }

    protected function _parseCommitChanges( $entry )
    {
        $return = array();
        foreach (array_values(self::$file_status_flags) as $name) {
            $return[$name] = array();
        }
        $response_items = explode("\n", $entry);
        if (!empty($response_items)) {
            foreach ($response_items as $i=>$line) {
                $line = trim($line);
                if (!empty($line)) {
                    $action = $line[0];
                    $original = null;
                    $filename = trim(substr($line, 1));
                    if (strpos($filename, "\t")) {
                        $matches = preg_split("/\t/", $filename);
                        $original = $matches[1];
                        $filename = $matches[2];
                    }
                    if (isset(self::$file_status_flags[$action])) {
                        if (is_null($original)) {
                            $return[self::$file_status_flags[$action]][] = $filename;
                        } else {
                            $return[self::$file_status_flags[$action]][] = array($original=>$filename);
                        }
                    }
                }
            }
        }
        return $return;
    }

    public function getCommitsHistory()
    {
        $cmd = sprintf('log --date=iso --pretty=format:%s', "'".self::$full_pretty_format."'");
		$response = explode("\n\n", $this->run($cmd));
		$data=array();
		if (!empty($response)) {
    		foreach ($response as $entry) {
        		$commit = $this->_parseCommitLog( $entry );
        		if (!empty($commit)) {
            		if (isset($commit['commit-abbrev'])) {
                    	$data[ $commit['commit-abbrev'] ] = $commit;
                    } else {
                    	$data[] = $commit;
                    }
        		}
    		}
		}
		array_filter($data);
        return $data;
    }

    public function getLastCommitInfos($path = null)
    {
        $cmd_mask = 'log -1 --date=iso --pretty=format:%s'.(!is_null($path) ? ' -- '.$path : '');
        $cmd = sprintf($cmd_mask, "'".self::$full_pretty_format."'");
        $data = $this->_parseCommitLog( $this->run($cmd) );
		array_filter($data);
        return $data;
    }

    public function getCommitInfos($hash, $path = null)
    {
        $cmd_mask = 'show %s --date=iso --pretty=format:%s'.(!is_null($path) ? ' -- '.$path : '');
        $cmd = sprintf($cmd_mask, $hash, "'".self::$full_pretty_format."'");
        $result = $this->run($cmd);

        $diff = '';
        $diffStart = false;
        if (preg_match('/^diff (?:--git a|--cc)/m', $result, $m, PREG_OFFSET_CAPTURE)) {
            $diffStart = $m[0][1];
        }
        if ($diffStart !== false) {
            $diff = substr($result, $diffStart);
            $result = substr($result, 0, $diffStart);
        }
        $data = $this->_parseCommitLog( $result );
        $data['changes'] = $this->getCommitChanges($hash);
        $data['diff'] = $this->_parseCommitDiff( $diff );
		array_filter($data);
        return $data;
    }

    protected function _parseCommitDiff( $diff )
    {
        $explode = explode("\n".'diff --git ', $diff);
        $data = array();
        if (!empty($explode)) {
            foreach($explode as $file) {
                $data[] = $this->_parseCommitDiffEntry($file);
            }
        }
        return $data;
    }

    static $file_status_flags = array(
        'A'=>'addition',
        'D'=>'deletion',
        'R'=>'rename-edit',
        'C'=>'copy-edit',
        'M'=>'modification',
        'U'=>'unmerged file',
        'T'=>'type change',
        'X'=>'unknown'
    );
    static $diff_from_file_starter = '--- ';
    static $diff_to_file_starter = '+++ ';
    static $diff_change_mask = '@@ \-([\d]+),([\d]+) \+([\d]+),([\d]+) @@(.*)?';
    static $diff_modes = array(
        'change file mode (from)'       =>array('type'=>'T', 'mask'=>'old mode ([\d]{6})'),
        'change file mode (to)'         =>array('type'=>'T', 'mask'=>'new mode ([\d]{6})'),
        'deleted file'                  =>array('type'=>'D', 'mask'=>'deleted file mode ([\d]{6})'),
        'created file'                  =>array('type'=>'A', 'mask'=>'new file mode ([\d]{6})'),
        'copied file (from)'            =>array('type'=>'C', 'mask'=>'copy from (.*)'),
        'copied file (to)'              =>array('type'=>'C', 'mask'=>'copy to (.*)'),
        'renamed file (from)'           =>array('type'=>'R', 'mask'=>'rename from (.*)'),
        'renamed file (to)'             =>array('type'=>'R', 'mask'=>'rename to (.*)'),
        'similarity'                    =>array('type'=>'', 'mask'=>'similarity index ([\d]+)%'),
        'dissimilarity'                 =>array('type'=>'', 'mask'=>'dissimilarity index ([\d]+)%'),
        'history'                       =>array('type'=>'', 'mask'=>'index ([a-z0-9]{7})\.\.([a-z0-9]{7})( [\d]{6})?')
    );

    protected function _parseCommitDiffEntry( $diff )
    {
        $explode = explode("\n", $diff);
        $data = array('from_name'=>null, 'to_name'=>null, 'type'=>'M', 'diff'=>array());
        if (!empty($explode)) {
            $change = null;
            foreach($explode as $i=>$line) {
                $matches=null;
                // the from file name
                if (self::$diff_from_file_starter===substr($line, 0, strlen(self::$diff_from_file_starter))) {
                    $words = explode(' ', $line);
                    $data['from_name'] = substr($words[1], 0, 2)==='a/' ? substr($words[1], 2) : $words[1];

                // the to file name
                } elseif (self::$diff_to_file_starter===substr($line, 0, strlen(self::$diff_to_file_starter))) {
                    $words = explode(' ', $line);
                    $data['to_name'] = substr($words[1], 0, 2)==='b/' ? substr($words[1], 2) : $words[1];

                // the changes
                } elseif (0!==preg_match('/^'.self::$diff_change_mask.'$/i', $line, $matches)) {
                    if (!is_null($change)) {
                        $data['diff'][] = $change;
                    }
                    $change = array(
                        'git_hunk_header'=>$line,
                        'from_line'=>$matches[1],
                        'from_lines_number'=>$matches[2],
                        'to_line'=>$matches[3],
                        'to_lines_number'=>$matches[4],
                        'lines'=>array()
                    );
                } elseif (!is_null($change)) {
                    $change['lines'][] = $line;

                // diff headers
                } elseif ($i>0) {
                    $matches=null;
                    foreach (self::$diff_modes as $str=>$mask) {
                        if (0!==preg_match('/^'.$mask['mask'].'$/i', $line, $matches)) {
                            if ('history'===$str) {
                                $data[$str] = array(
                                    'previous'=>$matches[1],
                                    'next'=>$matches[2]
                                );
                            } else {
                                if (!empty($mask['type'])) {
                                    $data['type'] = $mask['type'];
                                }
                                $data[$str] = $matches[1];
                            }
                        }
                    }
                }
            }
            if (!is_null($change)) {
                $data['diff'][] = $change;
            }
        }
        return $data;
    }

    public function getCommitChanges($hash)
    {
//        $cmd_mask = 'show %s --name-status --pretty="format:" --diff-filter="[A|D|M|R]" -M';
        $cmd_mask = 'show %s --name-status --pretty="format:" -M';
        $cmd = sprintf($cmd_mask, $hash);
        $data = $this->_parseCommitChanges( $this->run($cmd) );
		array_filter($data);
        return $data;
    }

    public function getCommitersList()
    {
        $response = explode("\n", $this->run("log --format='%aN %ae'") );
		$data=array();
		if (!empty($response)) {
    		foreach ($response as $entry) {
        		$name = substr($entry, 0, strrpos($entry, ' '));
        		$email = substr($entry, strrpos($entry, ' ')+1);
        		if (!empty($name)) {
        		    if (!isset($data[$name])) {
                        $data[$name] = array('name'=>$name, 'email'=>$email, 'commits'=>1);
                    } else {
                        $data[$name]['commits']++;
                    }
        		}
    		}
		}
		array_filter($data);
        return $data;
    }

    protected function _parseTreeEntry($entry)
    {
        if (empty($entry)) return null;
        $data = explode("\t", $entry);
        $tree = array();
        if (!empty($data)) {
            $tree['path'] = $data[1];
            $tree['dirname'] = dirname($data[1]);
            $tree['basename'] = basename($data[1]);
            $infos = explode(' ', $data[0]);
            $tree['mode'] = $infos[0];
            $tree['type'] = $infos[1];
            $tree['object'] = $infos[2];
            $tree['size'] = trim(end($infos));
        }
        return $tree;
    }

    public function getRecursiveTree($scope = 'HEAD', $subdir = null)
    {
        $cmd = "ls-tree --abbrev -l -r ".$scope.(!is_null($subdir) ? ' '.$subdir : '');
        $result = explode("\n", $this->run($cmd));
        $tree = array();
        if (!empty($result)) {
            foreach($result as $i=>$entry) {
                $tree_entry = $this->_parseTreeEntry($entry);
                if (!empty($tree_entry)) {
                    $tree[] = $tree_entry;
                }
            }
        }
        array_filter($tree);
        return $tree;
    }

    public function getTree($scope = 'HEAD', $subdir = null, $only_directories = null)
    {
        $cmd = "ls-tree --abbrev -l ".(true===$only_directories ? ' -d' : '').$scope.(!is_null($subdir) ? ' '.rtrim($subdir, '/').'/' : '');
        $result = explode("\n", $this->run($cmd));
        $tree = array();
        if (!empty($result)) {
            foreach($result as $i=>$entry) {
                $tree_entry = $this->_parseTreeEntry($entry);                
                if (!empty($tree_entry)) {
                    $tree[] = $tree_entry;
                }
            }
        }
        array_filter($tree);
        return $tree;
    }

    public function getFilesInfo()
    {
        $cmd = 'ls-files | while read file; do git log -n 1 --pretty="$file %h" -- $file; done';
        $result = explode("\n", $this->run($cmd));
        $files = array();
        if (!empty($result)) {
            foreach($result as $i=>$entry) {
                if (!empty($entry)) {
                    $items = explode(' ', $entry);
                    $files[$items[0]] = $items[1];
                }
            }
        }
        array_filter($files);
        return $files;
    }

    public function getRaw($object)
    {
        return $this->run('cat-file -p '.$object);
    }

    public function isImageContent($content)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($content);
        return preg_match('#^image/(.*)#', $mime);
    }

    public function getRawImageData($content)
    {
        if ($this->isImageContent($content)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($content);
            return 'data:'.$mime.';base64,'.base64_encode($content);
        }
        return '';
    }

	/**
	 * Runs a `git add` call
	 *
	 * Accepts a list of files to add
	 *
	 * @access  public
	 * @param   mixed   files to add
	 * @return  string
	 */
	public function add($files = "*")
	{
		if (is_array($files)) {
			$files = '"'.implode('" "', $files).'"';
		}
		return $this->run("add $files -v");
	}

	/**
	 * Runs a `git commit` call
	 *
	 * Accepts a commit message string
	 *
	 * @access  public
	 * @param   string  commit message
	 * @return  string
	 */
	public function commit($message = "")
	{
		return $this->run("commit -av -m ".escapeshellarg($message));
	}

	/**
	 * Runs a `git clone` call to clone the current repository
	 * into a different directory
	 *
	 * Accepts a target directory
	 *
	 * @access  public
	 * @param   string  target directory
	 * @return  string
	 */
	public function cloneTo($target)
	{
		return $this->run("clone --local ".$this->repository_path." $target");
	}

	/**
	 * Runs a `git clone` call to clone a different repository
	 * into the current repository
	 *
	 * Accepts a source directory
	 *
	 * @access  public
	 * @param   string  source directory
	 * @return  string
	 */
	public function cloneFrom($source)
	{
		return $this->run("clone --local $source ".$this->repository_path);
	}

	/**
	 * Runs a `git clone` call to clone a remote repository
	 * into the current repository
	 *
	 * Accepts a source url
	 *
	 * @access  public
	 * @param   string  source url
	 * @return  string
	 */
	public function cloneRemote($source)
	{
		return $this->run("clone $source ".$this->repository_path);
	}

	/**
	 * Runs a `git clean` call
	 *
	 * Accepts a remove directories flag
	 *
	 * @access  public
	 * @param   bool    delete directories?
	 * @return  string
	 */
	public function clean($dirs = false)
	{
		return $this->run("clean".(($dirs) ? " -d" : ""));
	}

	/**
	 * Runs a `git branch` call
	 *
	 * Accepts a name for the branch
	 *
	 * @access  public
	 * @param   string  branch name
	 * @return  string
	 */
	public function createBranch($branch)
	{
		return $this->run("branch $branch");
	}

	/**
	 * Runs a `git branch -[d|D]` call
	 *
	 * Accepts a name for the branch
	 *
	 * @access  public
	 * @param   string  branch name
	 * @return  string
	 */
	public function deleteBranch($branch, $force = false)
	{
		return $this->run("branch ".(($force) ? '-D' : '-d')." $branch");
	}

	/**
	 * Runs a `git branch` call
	 *
	 * @access  public
	 * @param   bool    keep asterisk mark on active branch
	 * @return  array
	 */
	public function getBranchesList($keep_asterisk = false)
	{
		$branchArray = explode("\n", $this->run("branch"));
		foreach($branchArray as $i => &$branch) {
			$branch = trim($branch);
			if (! $keep_asterisk) {
				$branch = str_replace("* ", "", $branch);
			}
			if ($branch == "") {
				unset($branchArray[$i]);
			}
		}
		return $branchArray;
	}

	/**
	 * Returns name of active branch
	 *
	 * @access  public
	 * @param   bool    keep asterisk mark on branch name
	 * @return  string
	 */
	public function getCurrentBranch($keep_asterisk = false)
	{
		$branchArray = $this->getBranchesList(true);
		$getCurrentBranch = preg_grep("/^\*/", $branchArray);
		reset($getCurrentBranch);
		if ($keep_asterisk) {
			return current($getCurrentBranch);
		} else {
			return str_replace("* ", "", current($getCurrentBranch));
		}
	}

	/**
	 * Runs a `git checkout` call
	 *
	 * Accepts a name for the branch
	 *
	 * @access  public
	 * @param   string  branch name
	 * @return  string
	 */
	public function checkout($branch)
	{
		return $this->run("checkout $branch");
	}


	/**
	 * Runs a `git merge` call
	 *
	 * Accepts a name for the branch to be merged
	 *
	 * @access  public
	 * @param   string $branch
	 * @return  string
	 */
	public function merge($branch)
	{
		return $this->run("merge $branch --no-ff");
	}


	/**
	 * Runs a git fetch on the current branch
	 *
	 * @access  public
	 * @return  string
	 */
	public function fetch() 
	{
		return $this->run("fetch");
	}

    public function getTagsList()
    {
//		return array_filter( explode("\n", $this->run("describe --tags --abbrev=0")) );
		$result = explode("\n", $this->run("tag -l -n"));
		$results=array();
		foreach($result as $_tag) {
		    if (!empty($_tag)) {
        		$_tag_infos = explode(" ", $_tag);
        		$tag_name = $tag_message = '';
        		foreach($_tag_infos as $i=>$_info) {
        		    if ($i===0) $tag_name = $_info;
        		    elseif (!empty($_info)) $tag_message .= ' '.$_info;
        		}
	    	    $results[] = array(
		            'tag_name' => trim($tag_name),
		            'message' => trim($tag_message)
    		    );
    		}
		}
		return $results;
    }

    public function buildTagTarball($tagname, $target_dir, $target_file = 'auto', $format = 'tar')
    {
        if (!file_exists($target_dir)) {
            throw new \InvalidArgumentException(
                sprintf('Target directory "%s" does not exist!', $target_dir)
            );
        }
        if (!is_dir($target_dir) || !is_writable($target_dir)) {
            throw new \InvalidArgumentException(
                sprintf('Target "%s" is not a directory or is not writable!', $target_dir)
            );
        }
        if ($target_file==='auto') $target_file = $tagname.'.'.$format;
        $target_realpath = rtrim($target_dir, '/').'/'.$target_file;
        $cmd = 'archive --format='.$format.' '.$tagname.' > '.$target_realpath;
        $this->run($cmd);
        return $target_realpath;
    }

    public function buildTarball($target_dir, $target_file = 'latest', $format = 'tar', $scope = 'HEAD')
    {
        if (!file_exists($target_dir)) {
            throw new \InvalidArgumentException(
                sprintf('Target directory "%s" does not exist!', $target_dir)
            );
        }
        if (!is_dir($target_dir) || !is_writable($target_dir)) {
            throw new \InvalidArgumentException(
                sprintf('Target "%s" is not a directory or is not writable!', $target_dir)
            );
        }
        if (!strpos($target_file, $format)) $target_file .= '.'.$format;
        $target_realpath = rtrim($target_dir, '/').'/'.$target_file;
        $cmd = 'archive --format='.$format.' '.$scope.' > '.$target_realpath;
        $this->run($cmd);
        return $target_realpath;
    }

	/**
	 * Add a new tag on the current position
	 *
	 * Accepts the name for the tag and the message
	 *
	 * @param string $tag
	 * @param string $message
	 * @return string
	 */
	public function addTag($tag, $message = null)
	{
		if ($message === null) {
			$message = $tag;
		}
		return $this->run("tag -a $tag -m $message");
	}


	/**
	 * Push specific branch to a remote
	 *
	 * Accepts the name of the remote and local branch
	 *
	 * @param string $remote
	 * @param string $branch
	 * @return string
	 */
	public function push($remote, $branch)
	{
		return $this->run("push --tags $remote $branch");
	}
	
	/**
	 * Pull specific branch from remote
	 *
	 * Accepts the name of the remote and local branch
	 *
	 * @param string $remote
	 * @param string $branch
	 * @return string
	 */
	public function pull($remote, $branch)
	{
		return $this->run("pull $remote $branch");
	}

	/**
	 * Sets the project description.
	 *
	 * @param string $new
	 */
	public function setDescription($new)
	{
		file_put_contents($this->repository_path."/.git/description", $new);
	}

	/**
	 * Gets the project description.
	 *
	 * @return string
	 */
	public function getDescription() 
	{
		return file_get_contents($this->repository_path."/.git/description");
	}
	
}

// Endfile