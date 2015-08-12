<!---
title: Filesystem
subtitle: Extra filesystem methods 
author: Robin Radic and Shea Lewis
-->

- The `Filesystem` class extends the default Laravel `Illuminate\Filesystem\Filesystem` class.
- By registering the `BeverageServiceProvider`, it will automatically bind itself to `fs`. 
- The `BeverageServiceProvider` will also include the `helpers.php` file, that have a all methods provided by the `Filesystem` class prefixed by `file_` (eg: `file_delete`). 

| Method signature | Returns | Description |
|:-----------------|:-------:|:-----------|
| `rglob($pattern, $flags = 0)` | `array` of file paths | Recursively find pathnames matching the given pattern.
| `rsearch($folder, $pattern)` | `array` of file paths | Search the given folder recursively for files using a regular expression pattern. |
| `touch($files, $time = null, $atime = null)` | - | Sets access and modification time of file. |
| `chmod($files, $mode, $umask = 0000, $recursive = false)` | - | Change mode for an array of files or directories. |
| `chown($files, $user, $recursive = false)` | - | Change the owner of an array of files or directories. |
| `chgrp($files, $group, $recursive = false)` | - | Change the group of an array of files or directories. |
| `rename($origin, $target, $overwrite = false)` | - | Renames a file or a directory. |
| `symlink($originDir, $targetDir, $copyOnWindows = false)` | - | Creates a symbolic link or copy a directory. |
| `mirror($originDir, $targetDir)` | - | Mirrors a directory to another. |
| `dumpFile($filename, $content, $mode = 0666)` | - | Atomically dumps content into a file. |
