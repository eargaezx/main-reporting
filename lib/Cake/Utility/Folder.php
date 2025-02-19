<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       Cake.Utility
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Folder structure browser, lists folders and files.
 * Provides an Object interface for Common directory related tasks.
 *
 * @package       Cake.Utility
 */
class Folder {

/**
 * Default scheme for Folder::copy
 * Recursively merges subfolders with the same name
 *
 * @var string
 */
	const MERGE = 'merge';

/**
 * Overwrite scheme for Folder::copy
 * subfolders with the same name will be replaced
 *
 * @var string
 */
	const OVERWRITE = 'overwrite';

/**
 * Skip scheme for Folder::copy
 * if a subfolder with the same name exists it will be skipped
 *
 * @var string
 */
	const SKIP = 'skip';

/**
 * Sort mode by name
 */
	const SORT_NAME = 'name';

/**
 * Sort mode by time
 */
	const SORT_TIME = 'time';

/**
 * Path to Folder.
 *
 * @var string
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::$path
 */
	public $path = null;

/**
 * Sortedness. Whether or not list results
 * should be sorted by name.
 *
 * @var bool
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::$sort
 */
	public $sort = false;

/**
 * Mode to be used on create. Does nothing on Windows platforms.
 *
 * @var int
 * https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::$mode
 */
	public $mode = 0755;

/**
 * Functions array to be called depending on the sort type chosen.
 */
	protected $_fsorts = array(
		self::SORT_NAME => 'getPathname',
		self::SORT_TIME => 'getCTime'
	);

/**
 * Holds messages from last method.
 *
 * @var array
 */
	protected $_messages = array();

/**
 * Holds errors from last method.
 *
 * @var array
 */
	protected $_errors = array();

/**
 * Holds array of complete directory paths.
 *
 * @var array
 */
	protected $_directories;

/**
 * Holds array of complete file paths.
 *
 * @var array
 */
	protected $_files;

/**
 * Constructor.
 *
 * @param string $path Path to folder
 * @param bool $create Create folder if not found
 * @param int|bool $mode Mode (CHMOD) to apply to created folder, false to ignore
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder
 */
	public function __construct($path = false, $create = false, $mode = false) {
		if (empty($path)) {
			$path = TMP;
		}
		if ($mode) {
			$this->mode = $mode;
		}

		if (!file_exists($path) && $create === true) {
			$this->create($path, $this->mode);
		}
		if (!Folder::isAbsolute($path)) {
			$path = realpath($path);
		}
		if (!empty($path)) {
			$this->cd($path);
		}
	}

/**
 * Return current path.
 *
 * @return string Current path
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::pwd
 */
	public function pwd() {
		return $this->path;
	}

/**
 * Change directory to $path.
 *
 * @param string $path Path to the directory to change to
 * @return string The new path. Returns false on failure
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::cd
 */
	public function cd($path) {
		$path = $this->realpath($path);
		if (is_dir($path)) {
			return $this->path = $path;
		}
		return false;
	}

/**
 * Returns an array of the contents of the current directory.
 * The returned array holds two arrays: One of directories and one of files.
 *
 * @param string|bool $sort Whether you want the results sorted, set this and the sort property
 *   to false to get unsorted results.
 * @param array|bool $exceptions Either an array or boolean true will not grab dot files
 * @param bool $fullPath True returns the full path
 * @return mixed Contents of current directory as an array, an empty array on failure
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::read
 */
	public function read($sort = self::SORT_NAME, $exceptions = false, $fullPath = false) {
		$dirs = $files = array();

		if (!$this->pwd()) {
			return array($dirs, $files);
		}
		if (is_array($exceptions)) {
			$exceptions = array_flip($exceptions);
		}
		$skipHidden = isset($exceptions['.']) || $exceptions === true;

		try {
			$iterator = new DirectoryIterator($this->path);
		} catch (Exception $e) {
			return array($dirs, $files);
		}
		if (!is_bool($sort) && isset($this->_fsorts[$sort])) {
			$methodName = $this->_fsorts[$sort];
		} else {
			$methodName = $this->_fsorts[self::SORT_NAME];
		}

		foreach ($iterator as $item) {
			if ($item->isDot()) {
				continue;
			}
			$name = $item->getFileName();
			if ($skipHidden && $name[0] === '.' || isset($exceptions[$name])) {
				continue;
			}
			if ($fullPath) {
				$name = $item->getPathName();
			}
			if ($item->isDir()) {
				$dirs[$item->{$methodName}()][] = $name;
			} else {
				$files[$item->{$methodName}()][] = $name;
			}
		}

		if ($sort || $this->sort) {
			ksort($dirs);
			ksort($files);
		}


		if ($dirs) {
			$dirs = call_user_func_array('array_merge',  array_values($dirs));
		}
		if ($files) {
			$files = call_user_func_array('array_merge',  array_values($files));
		}

		return array($dirs, $files);
	}

/**
 * Returns an array of all matching files in current directory.
 *
 * @param string $regexpPattern Preg_match pattern (Defaults to: .*)
 * @param bool $sort Whether results should be sorted.
 * @return array Files that match given pattern
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::find
 */
	public function find($regexpPattern = '.*', $sort = false) {
		list(, $files) = $this->read($sort);
		return array_values(preg_grep('/^' . $regexpPattern . '$/i', $files));
	}

/**
 * Returns an array of all matching files in and below current directory.
 *
 * @param string $pattern Preg_match pattern (Defaults to: .*)
 * @param bool $sort Whether results should be sorted.
 * @return array Files matching $pattern
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::findRecursive
 */
	public function findRecursive($pattern = '.*', $sort = false) {
		if (!$this->pwd()) {
			return array();
		}
		$startsOn = $this->path;
		$out = $this->_findRecursive($pattern, $sort);
		$this->cd($startsOn);
		return $out;
	}

/**
 * Private helper function for findRecursive.
 *
 * @param string $pattern Pattern to match against
 * @param bool $sort Whether results should be sorted.
 * @return array Files matching pattern
 */
	protected function _findRecursive($pattern, $sort = false) {
		list($dirs, $files) = $this->read($sort);
		$found = array();

		foreach ($files as $file) {
			if (preg_match('/^' . $pattern . '$/i', $file)) {
				$found[] = Folder::addPathElement($this->path, $file);
			}
		}
		$start = $this->path;

	

		foreach ($dirs as $dir) {
			$this->cd(Folder::addPathElement($start, $dir));
			$found = array_merge($found, $this->findRecursive($pattern, $sort));
		}
		return $found;
	}

/**
 * Returns true if given $path is a Windows path.
 *
 * @param string $path Path to check
 * @return bool true if Windows path, false otherwise
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::isWindowsPath
 */
	public static function isWindowsPath($path) {
		return (preg_match('/^[A-Z]:\\\\/i', $path) || substr($path, 0, 2) === '\\\\');
	}

/**
 * Returns true if given $path is an absolute path.
 *
 * @param string $path Path to check
 * @return bool true if path is absolute.
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::isAbsolute
 */
	public static function isAbsolute($path) {
		if (empty($path)) {
			return false;
		}

		return $path[0] === '/' ||
			preg_match('/^[A-Z]:\\\\/i', $path) ||
			substr($path, 0, 2) === '\\\\' ||
			static::isRegisteredStreamWrapper($path);
	}

/**
 * Returns true if given $path is a registered stream wrapper.
 *
 * @param string $path Path to check
 * @return bool true If path is registered stream wrapper.
 */
	public static function isRegisteredStreamWrapper($path) {
		if (preg_match('/^[A-Z]+(?=:\/\/)/i', $path, $matches) &&
			in_array($matches[0], stream_get_wrappers())
		) {
			return true;
		}
		return false;
	}

/**
 * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.)
 *
 * @param string $path Path to check
 * @return string Set of slashes ("\\" or "/")
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::normalizePath
 */
	public static function normalizePath($path) {
		return Folder::correctSlashFor($path);
	}

/**
 * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.)
 *
 * @param string $path Path to check
 * @return string Set of slashes ("\\" or "/")
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::correctSlashFor
 */
	public static function correctSlashFor($path) {
		return (Folder::isWindowsPath($path)) ? '\\' : '/';
	}

/**
 * Returns $path with added terminating slash (corrected for Windows or other OS).
 *
 * @param string $path Path to check
 * @return string Path with ending slash
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::slashTerm
 */
	public static function slashTerm($path) {
		if (Folder::isSlashTerm($path)) {
			return $path;
		}
		return $path . Folder::correctSlashFor($path);
	}

/**
 * Returns $path with $element added, with correct slash in-between.
 *
 * @param string $path Path
 * @param string|array $element Element to add at end of path
 * @return string Combined path
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::addPathElement
 */
	public static function addPathElement($path, $element) {
		$element = (array)$element;
		array_unshift($element, rtrim($path, DS));
		return implode(DS, $element);
	}

/**
 * Returns true if the Folder is in the given Cake path.
 *
 * @param string $path The path to check.
 * @return bool
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::inCakePath
 */
	public function inCakePath($path = '') {
		$dir = substr(Folder::slashTerm(ROOT), 0, -1);
		$newdir = $dir . $path;

		return $this->inPath($newdir);
	}

/**
 * Returns true if the Folder is in the given path.
 *
 * @param string $path The absolute path to check that the current `pwd()` resides within.
 * @param bool $reverse Reverse the search, check if the given `$path` resides within the current `pwd()`.
 * @return bool
 * @throws \InvalidArgumentException When the given `$path` argument is not an absolute path.
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::inPath
 */
	public function inPath($path = '', $reverse = false) {
		if (!Folder::isAbsolute($path)) {
			throw new InvalidArgumentException(__d('cake_dev', 'The $path argument is expected to be an absolute path.'));
		}

		$dir = Folder::slashTerm($path);
		$current = Folder::slashTerm($this->pwd());

		if (!$reverse) {
			$return = preg_match('/^' . preg_quote($dir, '/') . '(.*)/', $current);
		} else {
			$return = preg_match('/^' . preg_quote($current, '/') . '(.*)/', $dir);
		}
		return (bool)$return;
	}

/**
 * Change the mode on a directory structure recursively. This includes changing the mode on files as well.
 *
 * @param string $path The path to chmod.
 * @param int $mode Octal value, e.g. 0755.
 * @param bool $recursive Chmod recursively, set to false to only change the current directory.
 * @param array $exceptions Array of files, directories to skip.
 * @return bool Success.
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::chmod
 */
	public function chmod($path, $mode = false, $recursive = true, $exceptions = array()) {
		if (!$mode) {
			$mode = $this->mode;
		}

		if ($recursive === false && is_dir($path)) {
			//@codingStandardsIgnoreStart
			if (@chmod($path, intval($mode, 8))) {
				//@codingStandardsIgnoreEnd
				$this->_messages[] = __d('cake_dev', '%s changed to %s', $path, $mode);
				return true;
			}

			$this->_errors[] = __d('cake_dev', '%s NOT changed to %s', $path, $mode);
			return false;
		}

		if (is_dir($path)) {
			$paths = $this->tree($path);

			foreach ($paths as $type) {
				foreach ($type as $fullpath) {
					$check = explode(DS, $fullpath);
					$count = count($check);

					if (in_array($check[$count - 1], $exceptions)) {
						continue;
					}

					//@codingStandardsIgnoreStart
					if (@chmod($fullpath, intval($mode, 8))) {
						//@codingStandardsIgnoreEnd
						$this->_messages[] = __d('cake_dev', '%s changed to %s', $fullpath, $mode);
					} else {
						$this->_errors[] = __d('cake_dev', '%s NOT changed to %s', $fullpath, $mode);
					}
				}
			}

			if (empty($this->_errors)) {
				return true;
			}
		}
		return false;
	}

/**
 * Returns an array of nested directories and files in each directory
 *
 * @param string $path the directory path to build the tree from
 * @param array|bool $exceptions Either an array of files/folder to exclude
 *   or boolean true to not grab dot files/folders
 * @param string $type either 'file' or 'dir'. null returns both files and directories
 * @return mixed array of nested directories and files in each directory
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::tree
 */
	public function tree($path = null, $exceptions = false, $type = null) {
		if (!$path) {
			$path = $this->path;
		}
		$files = array();
		$directories = array($path);

		if (is_array($exceptions)) {
			$exceptions = array_flip($exceptions);
		}
		$skipHidden = false;
		if ($exceptions === true) {
			$skipHidden = true;
		} elseif (isset($exceptions['.'])) {
			$skipHidden = true;
			unset($exceptions['.']);
		}

		try {
			$directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::KEY_AS_PATHNAME | RecursiveDirectoryIterator::CURRENT_AS_SELF);
			$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
		} catch (Exception $e) {
			if ($type === null) {
				return array(array(), array());
			}
			return array();
		}

		foreach ($iterator as $itemPath => $fsIterator) {
			if ($skipHidden) {
				$subPathName = $fsIterator->getSubPathname();
				if ($subPathName[0] === '.' || strpos($subPathName, DS . '.') !== false) {
					continue;
				}
			}
			$item = $fsIterator->current();
			if (!empty($exceptions) && isset($exceptions[$item->getFilename()])) {
				continue;
			}

			if ($item->isFile()) {
				$files[] = $itemPath;
			} elseif ($item->isDir() && !$item->isDot()) {
				$directories[] = $itemPath;
			}
		}
		if ($type === null) {
			return array($directories, $files);
		}
		if ($type === 'dir') {
			return $directories;
		}
		return $files;
	}

/**
 * Create a directory structure recursively.
 *
 * Can be used to create deep path structures like `/foo/bar/baz/shoe/horn`
 *
 * @param string $pathname The directory structure to create. Either an absolute or relative
 *   path. If the path is relative and exists in the process' cwd it will not be created.
 *   Otherwise relative paths will be prefixed with the current pwd().
 * @param int $mode octal value 0755
 * @return bool Returns TRUE on success, FALSE on failure
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::create
 */
	public function create($pathname, $mode = false) {
		if (is_dir($pathname) || empty($pathname)) {
			return true;
		}

		if (!static::isAbsolute($pathname)) {
			$pathname = static::addPathElement($this->pwd(), $pathname);
		}

		if (!$mode) {
			$mode = $this->mode;
		}

		if (is_file($pathname)) {
			$this->_errors[] = __d('cake_dev', '%s is a file', $pathname);
			return false;
		}
		$pathname = rtrim($pathname, DS);
		$nextPathname = substr($pathname, 0, strrpos($pathname, DS));

		if ($this->create($nextPathname, $mode)) {
			if (!file_exists($pathname)) {
				$old = umask(0);
				if (mkdir($pathname, $mode)) {
					umask($old);
					$this->_messages[] = __d('cake_dev', '%s created', $pathname);
					return true;
				}
				umask($old);
				$this->_errors[] = __d('cake_dev', '%s NOT created', $pathname);
				return false;
			}
		}
		return false;
	}

/**
 * Returns the size in bytes of this Folder and its contents.
 *
 * @return int size in bytes of current folder
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::dirsize
 */
	public function dirsize() {
		$size = 0;
		$directory = Folder::slashTerm($this->path);
		$stack = array($directory);
		$count = count($stack);
		for ($i = 0, $j = $count; $i < $j; ++$i) {
			if (is_file($stack[$i])) {
				$size += filesize($stack[$i]);
			} elseif (is_dir($stack[$i])) {
				$dir = dir($stack[$i]);
				if ($dir) {
					while (false !== ($entry = $dir->read())) {
						if ($entry === '.' || $entry === '..') {
							continue;
						}
						$add = $stack[$i] . $entry;

						if (is_dir($stack[$i] . $entry)) {
							$add = Folder::slashTerm($add);
						}
						$stack[] = $add;
					}
					$dir->close();
				}
			}
			$j = count($stack);
		}
		return $size;
	}

/**
 * Recursively Remove directories if the system allows.
 *
 * @param string $path Path of directory to delete
 * @return bool Success
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::delete
 */
	public function delete($path = null) {
		if (!$path) {
			$path = $this->pwd();
		}
		if (!$path) {
			return false;
		}
		$path = Folder::slashTerm($path);
		if (is_dir($path)) {
			try {
				$directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::CURRENT_AS_SELF);
				$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);
			} catch (Exception $e) {
				return false;
			}

			foreach ($iterator as $item) {
				$filePath = $item->getPathname();
				if ($item->isFile() || $item->isLink()) {
					//@codingStandardsIgnoreStart
					if (@unlink($filePath)) {
						//@codingStandardsIgnoreEnd
						$this->_messages[] = __d('cake_dev', '%s removed', $filePath);
					} else {
						$this->_errors[] = __d('cake_dev', '%s NOT removed', $filePath);
					}
				} elseif ($item->isDir() && !$item->isDot()) {
					//@codingStandardsIgnoreStart
					if (@rmdir($filePath)) {
						//@codingStandardsIgnoreEnd
						$this->_messages[] = __d('cake_dev', '%s removed', $filePath);
					} else {
						$this->_errors[] = __d('cake_dev', '%s NOT removed', $filePath);
						return false;
					}
				}
			}

			$path = rtrim($path, DS);
			//@codingStandardsIgnoreStart
			if (@rmdir($path)) {
				//@codingStandardsIgnoreEnd
				$this->_messages[] = __d('cake_dev', '%s removed', $path);
			} else {
				$this->_errors[] = __d('cake_dev', '%s NOT removed', $path);
				return false;
			}
		}
		return true;
	}

/**
 * Recursive directory copy.
 *
 * ### Options
 *
 * - `to` The directory to copy to.
 * - `from` The directory to copy from, this will cause a cd() to occur, changing the results of pwd().
 * - `mode` The mode to copy the files/directories with as integer, e.g. 0775.
 * - `skip` Files/directories to skip.
 * - `scheme` Folder::MERGE, Folder::OVERWRITE, Folder::SKIP
 *
 * @param array|string $options Either an array of options (see above) or a string of the destination directory.
 * @return bool Success.
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::copy
 */
	public function copy($options) {
		if (!$this->pwd()) {
			return false;
		}
		$to = null;
		if (is_string($options)) {
			$to = $options;
			$options = array();
		}
		$options += array(
			'to' => $to,
			'from' => $this->path,
			'mode' => $this->mode,
			'skip' => array(),
			'scheme' => Folder::MERGE
		);

		$fromDir = $options['from'];
		$toDir = $options['to'];
		$mode = $options['mode'];

		if (!$this->cd($fromDir)) {
			$this->_errors[] = __d('cake_dev', '%s not found', $fromDir);
			return false;
		}

		if (!is_dir($toDir)) {
			$this->create($toDir, $mode);
		}

		if (!is_writable($toDir)) {
			$this->_errors[] = __d('cake_dev', '%s not writable', $toDir);
			return false;
		}

		$exceptions = array_merge(array('.', '..', '.svn'), $options['skip']);
		//@codingStandardsIgnoreStart
		if ($handle = @opendir($fromDir)) {
			//@codingStandardsIgnoreEnd
			while (($item = readdir($handle)) !== false) {
				$to = Folder::addPathElement($toDir, $item);
				if (($options['scheme'] != Folder::SKIP || !is_dir($to)) && !in_array($item, $exceptions)) {
					$from = Folder::addPathElement($fromDir, $item);
					if (is_file($from) && (!is_file($to) || $options['scheme'] != Folder::SKIP)) {
						if (copy($from, $to)) {
							chmod($to, intval($mode, 8));
							touch($to, filemtime($from));
							$this->_messages[] = __d('cake_dev', '%s copied to %s', $from, $to);
						} else {
							$this->_errors[] = __d('cake_dev', '%s NOT copied to %s', $from, $to);
						}
					}

					if (is_dir($from) && file_exists($to) && $options['scheme'] === Folder::OVERWRITE) {
						$this->delete($to);
					}

					if (is_dir($from) && !file_exists($to)) {
						$old = umask(0);
						if (mkdir($to, $mode)) {
							umask($old);
							$old = umask(0);
							chmod($to, $mode);
							umask($old);
							$this->_messages[] = __d('cake_dev', '%s created', $to);
							$options = array('to' => $to, 'from' => $from) + $options;
							$this->copy($options);
						} else {
							$this->_errors[] = __d('cake_dev', '%s not created', $to);
						}
					} elseif (is_dir($from) && $options['scheme'] === Folder::MERGE) {
						$options = array('to' => $to, 'from' => $from) + $options;
						$this->copy($options);
					}
				}
			}
			closedir($handle);
		} else {
			return false;
		}

		if (!empty($this->_errors)) {
			return false;
		}
		return true;
	}

/**
 * Recursive directory move.
 *
 * ### Options
 *
 * - `to` The directory to copy to.
 * - `from` The directory to copy from, this will cause a cd() to occur, changing the results of pwd().
 * - `chmod` The mode to copy the files/directories with.
 * - `skip` Files/directories to skip.
 * - `scheme` Folder::MERGE, Folder::OVERWRITE, Folder::SKIP
 *
 * @param array $options (to, from, chmod, skip, scheme)
 * @return bool Success
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::move
 */
	public function move($options) {
		$to = null;
		if (is_string($options)) {
			$to = $options;
			$options = (array)$options;
		}
		$options += array('to' => $to, 'from' => $this->path, 'mode' => $this->mode, 'skip' => array());

		if ($this->copy($options)) {
			if ($this->delete($options['from'])) {
				return (bool)$this->cd($options['to']);
			}
		}
		return false;
	}

/**
 * get messages from latest method
 *
 * @param bool $reset Reset message stack after reading
 * @return array
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::messages
 */
	public function messages($reset = true) {
		$messages = $this->_messages;
		if ($reset) {
			$this->_messages = array();
		}
		return $messages;
	}

/**
 * get error from latest method
 *
 * @param bool $reset Reset error stack after reading
 * @return array
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::errors
 */
	public function errors($reset = true) {
		$errors = $this->_errors;
		if ($reset) {
			$this->_errors = array();
		}
		return $errors;
	}

/**
 * Get the real path (taking ".." and such into account)
 *
 * @param string $path Path to resolve
 * @return string The resolved path
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::realpath
 */
	public function realpath($path) {
		if (strpos($path, '..') === false) {
			if (!Folder::isAbsolute($path)) {
				$path = Folder::addPathElement($this->path, $path);
			}
			return $path;
		}
		$path = str_replace('/', DS, trim($path));
		$parts = explode(DS, $path);
		$newparts = array();
		$newpath = '';
		if ($path[0] === DS) {
			$newpath = DS;
		}

		while (($part = array_shift($parts)) !== null) {
			if ($part === '.' || $part === '') {
				continue;
			}
			if ($part === '..') {
				if (!empty($newparts)) {
					array_pop($newparts);
					continue;
				}
				return false;
			}
			$newparts[] = $part;
		}
		$newpath .= implode(DS, $newparts);

		return Folder::slashTerm($newpath);
	}

/**
 * Returns true if given $path ends in a slash (i.e. is slash-terminated).
 *
 * @param string $path Path to check
 * @return bool true if path ends with slash, false otherwise
 * @link https://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html#Folder::isSlashTerm
 */
	public static function isSlashTerm($path) {
		$lastChar = $path[strlen($path) - 1];
		return $lastChar === '/' || $lastChar === '\\';
	}

}
