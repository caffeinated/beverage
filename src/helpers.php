<?php
/**
 * Caffeinated Beverage helper methods
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */


if (! function_exists('path_join')) {
    function path_join()
    {
        return forward_static_call_array([ 'Caffeinated\Beverage\Path', 'join' ], func_get_args());
    }
}

if (! function_exists('path_is_absolute')) {
    function path_is_absolute()
    {
        return forward_static_call_array([ 'Caffeinated\Beverage\Path', 'isAbsolute' ], func_get_args());
    }
}

if (! function_exists('path_is_relative')) {
    function path_is_relative()
    {
        return forward_static_call_array([ 'Caffeinated\Beverage\Path', 'isRelative' ], func_get_args());
    }
}

if (! function_exists('path_get_directory')) {
    function path_get_directory()
    {
        return forward_static_call_array([ 'Caffeinated\Beverage\Path', 'getDirectory' ], func_get_args());
    }
}

if (! function_exists('path_get_extension')) {
    function path_get_extension()
    {
        return forward_static_call_array([ 'Caffeinated\Beverage\Path', 'getExtension' ], func_get_args());
    }
}

if (! function_exists('path_get_filename')) {
    function path_get_filename()
    {
        return forward_static_call_array([ 'Caffeinated\Beverage\Path', 'getFilename' ], func_get_args());
    }
}


if (! function_exists('file_put')) {
/**
     * Write the contents of a file.
     *
     * @param  string $path
     * @param  string $contents
     * @param  bool   $lock
     * @return int
     */
    function file_put()
    {
        return call_user_func_array([ app('fs'), 'put' ], func_get_args());
    }
}

if (! function_exists('file_rglob')) {
/**
     * Recursively find pathnames matching the given pattern.
     *
     * @param  string $pattern
     * @param  int    $flags
     * @return array
     */
    function file_rglob()
    {
        return call_user_func_array([ app('fs'), 'rglob' ], func_get_args());
    }
}

if (! function_exists('file_rsearch')) {
/**
     * Search the given folder recursively for files using
     * a regular expression pattern.
     *
     * @param  string  $folder
     * @param  string  $pattern
     * @return array
     */
    function file_rsearch()
    {
        return call_user_func_array([ app('fs'), 'rsearch' ], func_get_args());
    }
}

if (! function_exists('file_exists')) {
/**
     * Determine if a file exists.
     *
     * @param  string  $path
     * @return bool
     */
    function file_exists()
    {
        return call_user_func_array([ app('fs'), 'exists' ], func_get_args());
    }
}

if (! function_exists('file_get')) {
/**
     * Get the contents of a file.
     *
     * @param  string  $path
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function file_get()
    {
        return call_user_func_array([ app('fs'), 'get' ], func_get_args());
    }
}

if (! function_exists('file_getRequire')) {
/**
     * Get the returned value of a file.
     *
     * @param  string  $path
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function file_getRequire()
    {
        return call_user_func_array([ app('fs'), 'getRequire' ], func_get_args());
    }
}

if (! function_exists('file_requireOnce')) {
/**
     * Require the given file once.
     *
     * @param  string  $file
     * @return mixed
     */
    function file_requireOnce()
    {
        return call_user_func_array([ app('fs'), 'requireOnce' ], func_get_args());
    }
}

if (! function_exists('file_prepend')) {
/**
     * Prepend to a file.
     *
     * @param  string  $path
     * @param  string  $data
     * @return int
     */
    function file_prepend()
    {
        return call_user_func_array([ app('fs'), 'prepend' ], func_get_args());
    }
}

if (! function_exists('file_append')) {
/**
     * Append to a file.
     *
     * @param  string  $path
     * @param  string  $data
     * @return int
     */
    function file_append()
    {
        return call_user_func_array([ app('fs'), 'append' ], func_get_args());
    }
}

if (! function_exists('file_delete')) {
/**
     * Delete the file at a given path.
     *
     * @param  string|array  $paths
     * @return bool
     */
    function file_delete()
    {
        return call_user_func_array([ app('fs'), 'delete' ], func_get_args());
    }
}

if (! function_exists('file_move')) {
/**
     * Move a file to a new location.
     *
     * @param  string  $path
     * @param  string  $target
     * @return bool
     */
    function file_move()
    {
        return call_user_func_array([ app('fs'), 'move' ], func_get_args());
    }
}

if (! function_exists('file_copy')) {
/**
     * Copy a file to a new location.
     *
     * @param  string  $path
     * @param  string  $target
     * @return bool
     */
    function file_copy()
    {
        return call_user_func_array([ app('fs'), 'copy' ], func_get_args());
    }
}

if (! function_exists('file_name')) {
/**
     * Extract the file name from a file path.
     *
     * @param  string  $path
     * @return string
     */
    function file_name()
    {
        return call_user_func_array([ app('fs'), 'name' ], func_get_args());
    }
}

if (! function_exists('file_extension')) {
/**
     * Extract the file extension from a file path.
     *
     * @param  string  $path
     * @return string
     */
    function file_extension()
    {
        return call_user_func_array([ app('fs'), 'extension' ], func_get_args());
    }
}

if (! function_exists('file_type')) {
/**
     * Get the file type of a given file.
     *
     * @param  string  $path
     * @return string
     */
    function file_type()
    {
        return call_user_func_array([ app('fs'), 'type' ], func_get_args());
    }
}

if (! function_exists('file_mimeType')) {
/**
     * Get the mime-type of a given file.
     *
     * @param  string  $path
     * @return string|false
     */
    function file_mimeType()
    {
        return call_user_func_array([ app('fs'), 'mimeType' ], func_get_args());
    }
}

if (! function_exists('file_size')) {
/**
     * Get the file size of a given file.
     *
     * @param  string  $path
     * @return int
     */
    function file_size()
    {
        return call_user_func_array([ app('fs'), 'size' ], func_get_args());
    }
}

if (! function_exists('file_lastModified')) {
/**
     * Get the file's last modification time.
     *
     * @param  string  $path
     * @return int
     */
    function file_lastModified()
    {
        return call_user_func_array([ app('fs'), 'lastModified' ], func_get_args());
    }
}

if (! function_exists('file_isDirectory')) {
/**
     * Determine if the given path is a directory.
     *
     * @param  string  $directory
     * @return bool
     */
    function file_isDirectory()
    {
        return call_user_func_array([ app('fs'), 'isDirectory' ], func_get_args());
    }
}

if (! function_exists('file_isWriteable')) {
/**
     * Determine if the given path is writable.
     *
     * @param  string  $path
     * @return bool
     */
    function file_isWriteable()
    {
        return call_user_func_array([ app('fs'), 'isWriteable' ], func_get_args());
    }
}

if (! function_exists('file_isFile')) {
/**
     * Determine if the given path is a file.
     *
     * @param  string  $file
     * @return bool
     */
    function file_isFile()
    {
        return call_user_func_array([ app('fs'), 'isFile' ], func_get_args());
    }
}

if (! function_exists('file_glob')) {
/**
     * Find path names matching a given pattern.
     *
     * @param  string  $pattern
     * @param  int     $flags
     * @return array
     */
    function file_glob()
    {
        return call_user_func_array([ app('fs'), 'glob' ], func_get_args());
    }
}

if (! function_exists('file_files')) {
/**
     * Get an array of all files in a directory.
     *
     * @param  string  $directory
     * @return array
     */
    function file_files()
    {
        return call_user_func_array([ app('fs'), 'files' ], func_get_args());
    }
}

if (! function_exists('file_allFiles')) {
/**
     * Get all of the files from the given directory (recursive).
     *
     * @param  string  $directory
     * @return array
     */
    function file_allFiles()
    {
        return call_user_func_array([ app('fs'), 'allFiles' ], func_get_args());
    }
}

if (! function_exists('file_directories')) {
/**
     * Get all of the directories within a given directory.
     *
     * @param  string  $directory
     * @return array
     */
    function file_directories()
    {
        return call_user_func_array([ app('fs'), 'directories' ], func_get_args());
    }
}

if (! function_exists('file_makeDirectory')) {
/**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     * @param  bool    $force
     * @return bool
     */
    function file_makeDirectory()
    {
        return call_user_func_array([ app('fs'), 'makeDirectory' ], func_get_args());
    }
}

if (! function_exists('file_copyDirectory')) {
/**
     * Copy a directory from one location to another.
     *
     * @param  string  $directory
     * @param  string  $destination
     * @param  int     $options
     * @return bool
     */
    function file_copyDirectory()
    {
        return call_user_func_array([ app('fs'), 'copyDirectory' ], func_get_args());
    }
}

if (! function_exists('file_deleteDirectory')) {
/**
     * Recursively delete a directory.
     *
     * The directory itself may be optionally preserved.
     *
     * @param  string  $directory
     * @param  bool    $preserve
     * @return bool
     */
    function file_deleteDirectory()
    {
        return call_user_func_array([ app('fs'), 'deleteDirectory' ], func_get_args());
    }
}

if (! function_exists('file_cleanDirectory')) {
/**
     * Empty the specified directory of all files and folders.
     *
     * @param  string  $directory
     * @return bool
     */
    function file_cleanDirectory()
    {
        return call_user_func_array([ app('fs'), 'cleanDirectory' ], func_get_args());
    }
}

if (! function_exists('file_touch')) {
/**
     * Sets access and modification time of file.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to create
     * @param int                       $time  The touch time as a Unix timestamp
     * @param int                       $atime The access time as a Unix timestamp
     *
     * @throws IOException When touch fails
     */
    function file_touch()
    {
        return call_user_func_array([ app('fs'), 'touch' ], func_get_args());
    }
}

if (! function_exists('file_chmod')) {
/**
     * Change mode for an array of files or directories.
     *
     * @param string|array|\Traversable $files     A filename, an array of files, or a \Traversable instance to change mode
     * @param int                       $mode      The new mode (octal)
     * @param int                       $umask     The mode mask (octal)
     * @param bool                      $recursive Whether change the mod recursively or not
     *
     * @throws IOException When the change fail
     */
    function file_chmod()
    {
        return call_user_func_array([ app('fs'), 'chmod' ], func_get_args());
    }
}

if (! function_exists('file_chown')) {
/**
     * Change the owner of an array of files or directories.
     *
     * @param string|array|\Traversable $files     A filename, an array of files, or a \Traversable instance to change owner
     * @param string                    $user      The new owner user name
     * @param bool                      $recursive Whether change the owner recursively or not
     *
     * @throws IOException When the change fail
     */
    function file_chown()
    {
        return call_user_func_array([ app('fs'), 'chown' ], func_get_args());
    }
}

if (! function_exists('file_chgrp')) {
/**
     * Change the group of an array of files or directories.
     *
     * @param string|array|\Traversable $files     A filename, an array of files, or a \Traversable instance to change group
     * @param string                    $group     The group name
     * @param bool                      $recursive Whether change the group recursively or not
     *
     * @throws IOException When the change fail
     */
    function file_chgrp()
    {
        return call_user_func_array([ app('fs'), 'chgrp' ], func_get_args());
    }
}

if (! function_exists('file_rename')) {
/**
     * Renames a file or a directory.
     *
     * @param string $origin    The origin filename or directory
     * @param string $target    The new filename or directory
     * @param bool   $overwrite Whether to overwrite the target if it already exists
     *
     * @throws IOException When target file or directory already exists
     * @throws IOException When origin cannot be renamed
     */
    function file_rename()
    {
        return call_user_func_array([ app('fs'), 'rename' ], func_get_args());
    }
}

if (! function_exists('file_symlink')) {
/**
     * Creates a symbolic link or copy a directory.
     *
     * @param string $originDir     The origin directory path
     * @param string $targetDir     The symbolic link name
     * @param bool   $copyOnWindows Whether to copy files if on Windows
     *
     * @throws IOException When symlink fails
     */
    function file_symlink()
    {
        return call_user_func_array([ app('fs'), 'symlink' ], func_get_args());
    }
}

if (! function_exists('file_mirror')) {
/**
     * Mirrors a directory to another.
     *
     * @param string       $originDir The origin directory
     * @param string       $targetDir The target directory
     * @param \Traversable $iterator  A Traversable instance
     * @param array        $options   An array of boolean options
     *                                Valid options are:
     *                                - $options['override'] Whether to override an existing file on copy or not (see copy())
     *                                - $options['copy_on_windows'] Whether to copy files instead of links on Windows (see symlink())
     *                                - $options['delete'] Whether to delete files that are not in the source directory (defaults to false)
     *
     * @throws IOException When file type is unknown
     */
    function file_mirror()
    {
        return call_user_func_array([ app('fs'), 'mirror' ], func_get_args());
    }
}

if (! function_exists('file_dumpFile')) {
/**
     * Atomically dumps content into a file.
     *
     * @param string   $filename The file to be written to.
     * @param string   $content  The data to write into the file.
     * @param null|int $mode     The file mode (octal). If null, file permissions are not modified
     *                           Deprecated since version 2.3.12, to be removed in 3.0.
     *
     * @throws IOException If the file cannot be written to.
     */
    function file_dumpFile()
    {
        return call_user_func_array([ app('fs'), 'dumpFile' ], func_get_args());
    }
}
