<?php namespace Norm\Services\Generator;

use Norm\Services\Generator\Exceptions\FileNotFoundException;

class File {

	/**
	 * Determine if a file exists.
	 *
	 * @param string $path
	 * @return bool
	 */
	public function exists($path) {
		return file_exists($path);
	}

	/**
	 * Get the contents of a file.
	 *
	 * @param string $path
	 * @return string
	 * @throws FileNotFoundException
	 */
	public function get($path) {
		if ($this->isFile($path)) return file_get_contents($path);

		throw new FileNotFoundException("File does not exist at path {$path}");
	}

	/**
	 * Write the contents of a file.
	 *
	 * @param string $path
	 * @param string $contents
	 * @param bool $lock
	 * @return int
	 */
	public function put($path, $contents, $lock = false) {
		$this->makeDirectory($path);

		return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
	}

	/**
	 * Prepend to a file.
	 *
	 * @param string $path
	 * @param string $data
	 * @return int
	 */
	public function prepend($path, $data) {
		if ($this->exists($path)) {
			return $this->put($path, $data . $this->get($path));
		}

		return $this->put($path, $data);
	}

	/**
	 * Append to a file.
	 *
	 * @param string $path
	 * @param string $data
	 * @return int
	 */
	public function append($path, $data) {
		if ($this->exists($path)) {
			return file_put_contents($path, $data, FILE_APPEND);
		}

		return $this->put($path, $data);
	}

	/**
	 * Delete the file at the given path(s).
	 *
	 * @param string|array $paths
	 * @return bool
	 */
	public function delete($paths) {
		$paths = is_array($paths) ? $paths : func_get_args();

		$success = true;

		foreach ($paths as $path) {
			try {
				unlink($path);
			} catch (\Exception $e) {
				$success = false;
			}
		}

		return $success;
	}

	/**
	 * Move a file to a new location.
	 *
	 * @param string $path
	 * @param string $target
	 * @return bool
	 */
	public function move($path, $target) {
		$this->makeDirectory($target);

		return rename($path, $target);
	}

	/**
	 * Copy a file to a new location.
	 *
	 * @param string $path
	 * @param string $target
	 * @return bool
	 */
	public function copy($path, $target) {
		$this->makeDirectory($target);

		return copy($path, $target);
	}

	/**
	 * Extract the file name from a file path.
	 *
	 * @param string $path
	 * @return string
	 */
	public function name($path) {
//		if (!$this->exists($path)) return null;

		return pathinfo($path, PATHINFO_FILENAME);
	}

	/**
	 * Extract the file extension from a file path.
	 *
	 * @param string $path
	 * @return string
	 */
	public function extension($path) {
		return pathinfo($path, PATHINFO_EXTENSION);
	}

	/**
	 * Get the file type of a given file.
	 *
	 * @param string $path
	 * @return string
	 */
	public function type($path) {
		return filetype($path);
	}

	/**
	 * Get the mime-type of a given file.
	 *
	 * @param  string $path
	 * @return string|false
	 */
	public function mimeType($path) {
		return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
	}

	/**
	 * Get the file size of a given file.
	 *
	 * @param  string $path
	 * @return int
	 */
	public function size($path) {
		return filesize($path);
	}

	/**
	 * Determine if the given path is a directory.
	 *
	 * @param  string $directory
	 * @return bool
	 */
	public function isDirectory($directory) {
		return is_dir($directory);
	}

	/**
	 * Determine if the given path is writable.
	 *
	 * @param  string $path
	 * @return bool
	 */
	public function isWritable($path) {
		return is_writable($path);
	}

	/**
	 * Determine if the given path is a file.
	 *
	 * @param  string $file
	 * @return bool
	 */
	public function isFile($file) {
		return is_file($file);
	}

	/**
	 * Get an array of all files in a directory.
	 *
	 * @param  string $directory
	 * @return array
	 */
	public function files($directory) {
		$glob = glob($directory . '/*');

		if ($glob === false) return array();

		// To get the appropriate files, we'll simply glob the directory and filter
		// out any "files" that are not truly files so we do not end up with any
		// directories in our list, but only true files within the directory.
		return array_filter($glob, function ($file) {
			return filetype($file) == 'file';
		});
	}

	/**
	 * Create directory structure recursively if the directory does not exist.
	 *
	 * @param string $path
	 * @param int $mode
	 * @return bool
	 */
	public function makeDirectory($path, $mode = 0755) {
		$directory = pathinfo($path, PATHINFO_DIRNAME);

		if (is_dir($directory)) {
			return true;
		}
		else {
			if ($this->makeDirectory($directory, $mode)) {
				if (mkdir($directory, $mode)) {
					return true;
				}
			}
		}

		return false;
	}

}