<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Beverage\Filesystem;

/**
 * This is the FilesystemTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class FilesystemTest extends TestCase
{

    public function testGetRetrievesFiles()
    {
        file_put_contents(__DIR__.'/file.txt', 'Hello World');
        $files = new Filesystem;
        $this->assertEquals('Hello World', $files->get(__DIR__.'/file.txt'));
        @unlink(__DIR__.'/file.txt');
    }
    public function testPutStoresFiles()
    {
        $files = new Filesystem;
        $files->put(__DIR__.'/file.txt', 'Hello World');
        $this->assertEquals('Hello World', file_get_contents(__DIR__.'/file.txt'));
        @unlink(__DIR__.'/file.txt');
    }
    public function testDeleteRemovesFiles()
    {
        file_put_contents(__DIR__.'/file.txt', 'Hello World');
        $files = new Filesystem;
        $files->delete(__DIR__.'/file.txt');
        $this->assertFileNotExists(__DIR__.'/file.txt');
        @unlink(__DIR__.'/file.txt');
    }
    public function testPrependExistingFiles()
    {
        $files = new Filesystem;
        $files->put(__DIR__.'/file.txt', 'World');
        $files->prepend(__DIR__.'/file.txt', 'Hello ');
        $this->assertEquals('Hello World', file_get_contents(__DIR__.'/file.txt'));
        @unlink(__DIR__.'/file.txt');
    }
    public function testPrependNewFiles()
    {
        $files = new Filesystem;
        $files->prepend(__DIR__.'/file.txt', 'Hello World');
        $this->assertEquals('Hello World', file_get_contents(__DIR__.'/file.txt'));
        @unlink(__DIR__.'/file.txt');
    }
    public function testDeleteDirectory()
    {
        mkdir(__DIR__.'/foo');
        file_put_contents(__DIR__.'/foo/file.txt', 'Hello World');
        $files = new Filesystem;
        $files->deleteDirectory(__DIR__.'/foo');
        $this->assertFalse(is_dir(__DIR__.'/foo'));
        $this->assertFileNotExists(__DIR__.'/foo/file.txt');
    }
    public function testCleanDirectory()
    {
        mkdir(__DIR__.'/foo');
        file_put_contents(__DIR__.'/foo/file.txt', 'Hello World');
        $files = new Filesystem;
        $files->cleanDirectory(__DIR__.'/foo');
        $this->assertTrue(is_dir(__DIR__.'/foo'));
        $this->assertFileNotExists(__DIR__.'/foo/file.txt');
        @rmdir(__DIR__.'/foo');
    }
    public function testFilesMethod()
    {
        mkdir(__DIR__.'/foo');
        file_put_contents(__DIR__.'/foo/1.txt', '1');
        file_put_contents(__DIR__.'/foo/2.txt', '2');
        mkdir(__DIR__.'/foo/bar');
        $files = new Filesystem;
        $this->assertEquals([__DIR__.'/foo/1.txt', __DIR__.'/foo/2.txt'], $files->files(__DIR__.'/foo'));
        unset($files);
        @unlink(__DIR__.'/foo/1.txt');
        @unlink(__DIR__.'/foo/2.txt');
        @rmdir(__DIR__.'/foo/bar');
        @rmdir(__DIR__.'/foo');
    }
    public function testCopyDirectoryReturnsFalseIfSourceIsntDirectory()
    {
        $files = new Filesystem;
        $this->assertFalse($files->copyDirectory(__DIR__.'/foo/bar/baz/breeze/boom', __DIR__));
    }
    public function testCopyDirectoryMovesEntireDirectory()
    {
        mkdir(__DIR__.'/tmp', 0777, true);
        file_put_contents(__DIR__.'/tmp/foo.txt', '');
        file_put_contents(__DIR__.'/tmp/bar.txt', '');
        mkdir(__DIR__.'/tmp/nested', 0777, true);
        file_put_contents(__DIR__.'/tmp/nested/baz.txt', '');
        $files = new Filesystem;
        $files->copyDirectory(__DIR__.'/tmp', __DIR__.'/tmp2');
        $this->assertTrue(is_dir(__DIR__.'/tmp2'));
        $this->assertFileExists(__DIR__.'/tmp2/foo.txt');
        $this->assertFileExists(__DIR__.'/tmp2/bar.txt');
        $this->assertTrue(is_dir(__DIR__.'/tmp2/nested'));
        $this->assertFileExists(__DIR__.'/tmp2/nested/baz.txt');
        unlink(__DIR__.'/tmp/nested/baz.txt');
        rmdir(__DIR__.'/tmp/nested');
        unlink(__DIR__.'/tmp/bar.txt');
        unlink(__DIR__.'/tmp/foo.txt');
        rmdir(__DIR__.'/tmp');
        unlink(__DIR__.'/tmp2/nested/baz.txt');
        rmdir(__DIR__.'/tmp2/nested');
        unlink(__DIR__.'/tmp2/foo.txt');
        unlink(__DIR__.'/tmp2/bar.txt');
        rmdir(__DIR__.'/tmp2');
    }
    /**
     * @expectedException \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testGetThrowsExceptionNonexisitingFile()
    {
        $files = new Filesystem;
        $files->get(__DIR__.'/unknown-file.txt');
    }
    public function testGetRequireReturnsProperly()
    {
        file_put_contents(__DIR__.'/file.php', '<?php return "Howdy?"; ?>');
        $files = new Filesystem;
        $this->assertEquals('Howdy?', $files->getRequire(__DIR__.'/file.php'));
        @unlink(__DIR__.'/file.php');
    }
    /**
     * @expectedException Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testGetRequireThrowsExceptionNonexisitingFile()
    {
        $files = new Filesystem;
        $files->getRequire(__DIR__.'/file.php');
    }
    public function testAppendAddsDataToFile()
    {
        file_put_contents(__DIR__.'/file.txt', 'foo');
        $files = new Filesystem;
        $bytesWritten = $files->append(__DIR__.'/file.txt', 'bar');
        $this->assertEquals(mb_strlen('bar', '8bit'), $bytesWritten);
        $this->assertFileExists(__DIR__.'/file.txt');
        $this->assertStringEqualsFile(__DIR__.'/file.txt', 'foobar');
        @unlink(__DIR__.'/file.txt');
    }
    public function testMoveMovesFiles()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        $files = new Filesystem;
        $files->move(__DIR__.'/foo.txt', __DIR__.'/bar.txt');
        $this->assertFileExists(__DIR__.'/bar.txt');
        $this->assertFileNotExists(__DIR__.'/foo.txt');
        @unlink(__DIR__.'/bar.txt');
    }
    public function testExtensionReturnsExtension()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        $files = new Filesystem;
        $this->assertEquals('txt', $files->extension(__DIR__.'/foo.txt'));
        @unlink(__DIR__.'/foo.txt');
    }
    public function testTypeIndentifiesFile()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        $files = new Filesystem;
        $this->assertEquals('file', $files->type(__DIR__.'/foo.txt'));
        @unlink(__DIR__.'/foo.txt');
    }
    public function testTypeIndentifiesDirectory()
    {
        mkdir(__DIR__.'/foo');
        $files = new Filesystem;
        $this->assertEquals('dir', $files->type(__DIR__.'/foo'));
        @rmdir(__DIR__.'/foo');
    }
    public function testSizeOutputsSize()
    {
        $size = file_put_contents(__DIR__.'/foo.txt', 'foo');
        $files = new Filesystem;
        $this->assertEquals($size, $files->size(__DIR__.'/foo.txt'));
        @unlink(__DIR__.'/foo.txt');
    }
    public function testMimeTypeOutputsMimeType()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        $files = new Filesystem;
        $this->assertEquals('text/plain', $files->mimeType(__DIR__.'/foo.txt'));
        @unlink(__DIR__.'/foo.txt');
    }
    public function testIsWritable()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        $files = new Filesystem;
        @chmod(__DIR__.'/foo.txt', 0444);
        $this->assertFalse($files->isWritable(__DIR__.'/foo.txt'));
        @chmod(__DIR__.'/foo.txt', 0777);
        $this->assertTrue($files->isWritable(__DIR__.'/foo.txt'));
        @unlink(__DIR__.'/foo.txt');
    }
    public function testGlobFindsFiles()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        file_put_contents(__DIR__.'/bar.txt', 'bar');
        $files = new Filesystem;
        $glob = $files->glob(__DIR__.'/*.txt');
        $this->assertContains(__DIR__.'/foo.txt', $glob);
        $this->assertContains(__DIR__.'/bar.txt', $glob);
        @unlink(__DIR__.'/foo.txt');
        @unlink(__DIR__.'/bar.txt');
    }
    public function testAllFilesFindsFiles()
    {
        file_put_contents(__DIR__.'/foo.txt', 'foo');
        file_put_contents(__DIR__.'/bar.txt', 'bar');
        $files = new Filesystem;
        $allFiles = [];
        foreach ($files->allFiles(__DIR__) as $file) {
            $allFiles[] = $file->getFilename();
        }
        $this->assertContains('foo.txt', $allFiles);
        $this->assertContains('bar.txt', $allFiles);
        @unlink(__DIR__.'/foo.txt');
        @unlink(__DIR__.'/bar.txt');
    }
    public function testDirectoriesFindsDirectories()
    {
        mkdir(__DIR__.'/foo');
        mkdir(__DIR__.'/bar');
        $files = new Filesystem;
        $directories = $files->directories(__DIR__);
        $this->assertContains(__DIR__.DIRECTORY_SEPARATOR.'foo', $directories);
        $this->assertContains(__DIR__.DIRECTORY_SEPARATOR.'bar', $directories);
        @rmdir(__DIR__.'/foo');
        @rmdir(__DIR__.'/bar');
    }
    public function testMakeDirectory()
    {
        $files = new Filesystem;
        $this->assertTrue($files->makeDirectory(__DIR__.'/foo'));
        $this->assertFileExists(__DIR__.'/foo');
        @rmdir(__DIR__.'/foo');
    }





    /**
     * @var Filesystem
     */
    private $filesystem = null;
    private $umask;
    /**
     * @var string
     */
    protected $workspace = null;
    protected static $symlinkOnWindows = null;
    public static function setUpBeforeClass()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            static::$symlinkOnWindows = true;
            $originDir = tempnam(sys_get_temp_dir(), 'sl');
            $targetDir = tempnam(sys_get_temp_dir(), 'sl');
            if (true !== @symlink($originDir, $targetDir)) {
                $report = error_get_last();
                if (is_array($report) && false !== strpos($report['message'], 'error code(1314)')) {
                    static::$symlinkOnWindows = false;
                }
            }
        }
    }
    public function setUp()
    {
        parent::setUp();
        $this->umask = umask(0);
        $this->workspace = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.time().mt_rand(0, 1000);
        mkdir($this->workspace, 0777, true);
        $this->workspace = realpath($this->workspace);
        $this->filesystem = new Filesystem();
    }
    public function tearDown()
    {
        parent::tearDown();
        $this->clean($this->workspace);
        umask($this->umask);
    }
    /**
     * @param string $file
     */
    protected function clean($file)
    {
        if (is_dir($file) && !is_link($file)) {
            $dir = new \FilesystemIterator($file);
            foreach ($dir as $childFile) {
                $this->clean($childFile);
            }
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    /**
     * @param int    $expectedFilePerms expected file permissions as three digits (i.e. 755)
     * @param string $filePath
     */
    protected function assertFilePermissions($expectedFilePerms, $filePath)
    {
        $actualFilePerms = (int) substr(sprintf('%o', fileperms($filePath)), -3);
        $this->assertEquals(
            $expectedFilePerms,
            $actualFilePerms,
            sprintf('File permissions for %s must be %s. Actual %s', $filePath, $expectedFilePerms, $actualFilePerms)
        );
    }
    protected function getFileOwner($filepath)
    {
        $this->markAsSkippedIfPosixIsMissing();
        $infos = stat($filepath);
        if ($datas = posix_getpwuid($infos['uid'])) {
            return $datas['name'];
        }
    }
    protected function getFileGroup($filepath)
    {
        $this->markAsSkippedIfPosixIsMissing();
        $infos = stat($filepath);
        if ($datas = posix_getgrgid($infos['gid'])) {
            return $datas['name'];
        }
        $this->markTestSkipped('Unable to retrieve file group name');
    }
    protected function markAsSkippedIfSymlinkIsMissing()
    {
        if (!function_exists('symlink')) {
            $this->markTestSkipped('symlink is not supported');
        }
        if ('\\' === DIRECTORY_SEPARATOR && false === static::$symlinkOnWindows) {
            $this->markTestSkipped('symlink requires "Create symbolic links" privilege on windows');
        }
    }
    protected function markAsSkippedIfChmodIsMissing()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('chmod is not supported on windows');
        }
    }
    protected function markAsSkippedIfPosixIsMissing()
    {
        if ('\\' === DIRECTORY_SEPARATOR || !function_exists('posix_isatty')) {
            $this->markTestSkipped('Posix is not supported');
        }
    }


    public function testCopyCreatesNewFile()
    {
        $sourceFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_source_file';
        $targetFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_target_file';
        file_put_contents($sourceFilePath, 'SOURCE FILE');
        $this->filesystem->copy($sourceFilePath, $targetFilePath);
        $this->assertFileExists($targetFilePath);
        $this->assertEquals('SOURCE FILE', file_get_contents($targetFilePath));
    }
    public function testCopyOverridesExistingFileIfModified()
    {
        $sourceFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_source_file';
        $targetFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_target_file';
        file_put_contents($sourceFilePath, 'SOURCE FILE');
        file_put_contents($targetFilePath, 'TARGET FILE');
        touch($targetFilePath, time() - 1000);
        $this->filesystem->copy($sourceFilePath, $targetFilePath);
        $this->assertFileExists($targetFilePath);
        $this->assertEquals('SOURCE FILE', file_get_contents($targetFilePath));
    }
    public function testCopyOverridesExistingFileIfForced()
    {
        $sourceFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_source_file';
        $targetFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_target_file';
        file_put_contents($sourceFilePath, 'SOURCE FILE');
        file_put_contents($targetFilePath, 'TARGET FILE');
        // make sure both files have the same modification time
        $modificationTime = time() - 1000;
        touch($sourceFilePath, $modificationTime);
        touch($targetFilePath, $modificationTime);
        $this->filesystem->copy($sourceFilePath, $targetFilePath, true);
        $this->assertFileExists($targetFilePath);
        $this->assertEquals('SOURCE FILE', file_get_contents($targetFilePath));
    }


    public function testCopyForOriginUrlsAndExistingLocalFileDefaultsToNotCopy()
    {
        $sourceFilePath = 'http://symfony.com/images/common/logo/logo_symfony_header.png';
        $targetFilePath = $this->workspace.DIRECTORY_SEPARATOR.'copy_target_file';
        file_put_contents($targetFilePath, 'TARGET FILE');
        $this->filesystem->copy($sourceFilePath, $targetFilePath, false);
        $this->assertFileExists($targetFilePath);
        $this->assertEquals(file_get_contents($sourceFilePath), file_get_contents($targetFilePath));
    }
    public function testMkdirCreatesDirectoriesRecursively()
    {
        $directory = $this->workspace
            .DIRECTORY_SEPARATOR.'directory'
            .DIRECTORY_SEPARATOR.'sub_directory';
        $this->filesystem->mkdir($directory);
        $this->assertTrue(is_dir($directory));
    }
    public function testMkdirCreatesDirectoriesFromArray()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $directories = array(
            $basePath.'1', $basePath.'2', $basePath.'3',
        );
        $this->filesystem->mkdir($directories);
        $this->assertTrue(is_dir($basePath.'1'));
        $this->assertTrue(is_dir($basePath.'2'));
        $this->assertTrue(is_dir($basePath.'3'));
    }
    public function testMkdirCreatesDirectoriesFromTraversableObject()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $directories = new \ArrayObject(array(
            $basePath.'1', $basePath.'2', $basePath.'3',
        ));
        $this->filesystem->mkdir($directories);
        $this->assertTrue(is_dir($basePath.'1'));
        $this->assertTrue(is_dir($basePath.'2'));
        $this->assertTrue(is_dir($basePath.'3'));
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testMkdirCreatesDirectoriesFails()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $dir = $basePath.'2';
        file_put_contents($dir, '');
        $this->filesystem->mkdir($dir);
    }
    public function testTouchCreatesEmptyFile()
    {
        $file = $this->workspace.DIRECTORY_SEPARATOR.'1';
        $this->filesystem->touch($file);
        $this->assertFileExists($file);
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testTouchFails()
    {
        $file = $this->workspace.DIRECTORY_SEPARATOR.'1'.DIRECTORY_SEPARATOR.'2';
        $this->filesystem->touch($file);
    }
    public function testTouchCreatesEmptyFilesFromArray()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $files = array(
            $basePath.'1', $basePath.'2', $basePath.'3',
        );
        $this->filesystem->touch($files);
        $this->assertFileExists($basePath.'1');
        $this->assertFileExists($basePath.'2');
        $this->assertFileExists($basePath.'3');
    }
    public function testTouchCreatesEmptyFilesFromTraversableObject()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $files = new \ArrayObject(array(
            $basePath.'1', $basePath.'2', $basePath.'3',
        ));
        $this->filesystem->touch($files);
        $this->assertFileExists($basePath.'1');
        $this->assertFileExists($basePath.'2');
        $this->assertFileExists($basePath.'3');
    }
    public function testRemoveCleansFilesAndDirectoriesIteratively()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR.'directory'.DIRECTORY_SEPARATOR;
        mkdir($basePath);
        mkdir($basePath.'dir');
        touch($basePath.'file');
        $this->filesystem->remove($basePath);
        $this->assertTrue(!is_dir($basePath));
    }
    public function testRemoveCleansArrayOfFilesAndDirectories()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        mkdir($basePath.'dir');
        touch($basePath.'file');
        $files = array(
            $basePath.'dir', $basePath.'file',
        );
        $this->filesystem->remove($files);
        $this->assertTrue(!is_dir($basePath.'dir'));
        $this->assertTrue(!is_file($basePath.'file'));
    }
    public function testRemoveCleansTraversableObjectOfFilesAndDirectories()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        mkdir($basePath.'dir');
        touch($basePath.'file');
        $files = new \ArrayObject(array(
            $basePath.'dir', $basePath.'file',
        ));
        $this->filesystem->remove($files);
        $this->assertTrue(!is_dir($basePath.'dir'));
        $this->assertTrue(!is_file($basePath.'file'));
    }
    public function testRemoveIgnoresNonExistingFiles()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        mkdir($basePath.'dir');
        $files = array(
            $basePath.'dir', $basePath.'file',
        );
        $this->filesystem->remove($files);
        $this->assertTrue(!is_dir($basePath.'dir'));
    }
    public function testRemoveCleansInvalidLinks()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $basePath = $this->workspace.DIRECTORY_SEPARATOR.'directory'.DIRECTORY_SEPARATOR;
        mkdir($basePath);
        mkdir($basePath.'dir');
        // create symlink to nonexistent file
        @symlink($basePath.'file', $basePath.'link');
        $this->filesystem->remove($basePath);
        $this->assertTrue(!is_dir($basePath));
    }
    public function testFilesExists()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR.'directory'.DIRECTORY_SEPARATOR;
        mkdir($basePath);
        touch($basePath.'file1');
        mkdir($basePath.'folder');
        $this->assertTrue($this->filesystem->exists($basePath.'file1'));
        $this->assertTrue($this->filesystem->exists($basePath.'folder'));
    }


    public function testInvalidFileNotExists()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR.'directory'.DIRECTORY_SEPARATOR;
        $this->assertFalse($this->filesystem->exists($basePath.time()));
    }
    public function testChmodChangesFileMode()
    {
        $this->markAsSkippedIfChmodIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $file = $dir.DIRECTORY_SEPARATOR.'file';
        touch($file);
        $this->filesystem->chmod($file, 0400);
        $this->filesystem->chmod($dir, 0753);
        $this->assertFilePermissions(753, $dir);
        $this->assertFilePermissions(400, $file);
    }
    public function testChmodWrongMod()
    {
        $this->markAsSkippedIfChmodIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'file';
        touch($dir);
        $this->filesystem->chmod($dir, 'Wrongmode');
    }
    public function testChmodRecursive()
    {
        $this->markAsSkippedIfChmodIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $file = $dir.DIRECTORY_SEPARATOR.'file';
        touch($file);
        $this->filesystem->chmod($file, 0400, 0000, true);
        $this->filesystem->chmod($dir, 0753, 0000, true);
        $this->assertFilePermissions(753, $dir);
        $this->assertFilePermissions(753, $file);
    }
    public function testChmodAppliesUmask()
    {
        $this->markAsSkippedIfChmodIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        touch($file);
        $this->filesystem->chmod($file, 0770, 0022);
        $this->assertFilePermissions(750, $file);
    }
    public function testChmodChangesModeOfArrayOfFiles()
    {
        $this->markAsSkippedIfChmodIsMissing();
        $directory = $this->workspace.DIRECTORY_SEPARATOR.'directory';
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $files = array($directory, $file);
        mkdir($directory);
        touch($file);
        $this->filesystem->chmod($files, 0753);
        $this->assertFilePermissions(753, $file);
        $this->assertFilePermissions(753, $directory);
    }
    public function testChmodChangesModeOfTraversableFileObject()
    {
        $this->markAsSkippedIfChmodIsMissing();
        $directory = $this->workspace.DIRECTORY_SEPARATOR.'directory';
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $files = new \ArrayObject(array($directory, $file));
        mkdir($directory);
        touch($file);
        $this->filesystem->chmod($files, 0753);
        $this->assertFilePermissions(753, $file);
        $this->assertFilePermissions(753, $directory);
    }
    public function testChown()
    {
        $this->markAsSkippedIfPosixIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $this->filesystem->chown($dir, $this->getFileOwner($dir));
    }
    public function testChownRecursive()
    {
        $this->markAsSkippedIfPosixIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $file = $dir.DIRECTORY_SEPARATOR.'file';
        touch($file);
        $this->filesystem->chown($dir, $this->getFileOwner($dir), true);
    }
    public function testChownSymlink()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        touch($file);
        $this->filesystem->symlink($file, $link);
        $this->filesystem->chown($link, $this->getFileOwner($link));
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testChownSymlinkFails()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        touch($file);
        $this->filesystem->symlink($file, $link);
        $this->filesystem->chown($link, 'user'.time().mt_rand(1000, 9999));
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testChownFail()
    {
        $this->markAsSkippedIfPosixIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $this->filesystem->chown($dir, 'user'.time().mt_rand(1000, 9999));
    }
    public function testChgrp()
    {
        $this->markAsSkippedIfPosixIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $this->filesystem->chgrp($dir, $this->getFileGroup($dir));
    }
    public function testChgrpRecursive()
    {
        $this->markAsSkippedIfPosixIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $file = $dir.DIRECTORY_SEPARATOR.'file';
        touch($file);
        $this->filesystem->chgrp($dir, $this->getFileGroup($dir), true);
    }
    public function testChgrpSymlink()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        touch($file);
        $this->filesystem->symlink($file, $link);
        $this->filesystem->chgrp($link, $this->getFileGroup($link));
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testChgrpSymlinkFails()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        touch($file);
        $this->filesystem->symlink($file, $link);
        $this->filesystem->chgrp($link, 'user'.time().mt_rand(1000, 9999));
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testChgrpFail()
    {
        $this->markAsSkippedIfPosixIsMissing();
        $dir = $this->workspace.DIRECTORY_SEPARATOR.'dir';
        mkdir($dir);
        $this->filesystem->chgrp($dir, 'user'.time().mt_rand(1000, 9999));
    }
    public function testRename()
    {
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $newPath = $this->workspace.DIRECTORY_SEPARATOR.'new_file';
        touch($file);
        $this->filesystem->rename($file, $newPath);
        $this->assertFileNotExists($file);
        $this->assertFileExists($newPath);
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testRenameThrowsExceptionIfTargetAlreadyExists()
    {
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $newPath = $this->workspace.DIRECTORY_SEPARATOR.'new_file';
        touch($file);
        touch($newPath);
        $this->filesystem->rename($file, $newPath);
    }
    public function testRenameOverwritesTheTargetIfItAlreadyExists()
    {
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $newPath = $this->workspace.DIRECTORY_SEPARATOR.'new_file';
        touch($file);
        touch($newPath);
        $this->filesystem->rename($file, $newPath, true);
        $this->assertFileNotExists($file);
        $this->assertFileExists($newPath);
    }
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testRenameThrowsExceptionOnError()
    {
        $file = $this->workspace.DIRECTORY_SEPARATOR.uniqid('fs_test_', true);
        $newPath = $this->workspace.DIRECTORY_SEPARATOR.'new_file';
        $this->filesystem->rename($file, $newPath);
    }
    public function testSymlink()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        // $file does not exists right now: creating "broken" links is a wanted feature
        $this->filesystem->symlink($file, $link);
        $this->assertTrue(is_link($link));
        // Create the linked file AFTER creating the link
        touch($file);
        $this->assertEquals($file, readlink($link));
    }
    /**
     * @depends testSymlink
     */
    public function testRemoveSymlink()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        $this->filesystem->remove($link);
        $this->assertTrue(!is_link($link));
    }
    public function testSymlinkIsOverwrittenIfPointsToDifferentTarget()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        touch($file);
        symlink($this->workspace, $link);
        $this->filesystem->symlink($file, $link);
        $this->assertTrue(is_link($link));
        $this->assertEquals($file, readlink($link));
    }
    public function testSymlinkIsNotOverwrittenIfAlreadyCreated()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link = $this->workspace.DIRECTORY_SEPARATOR.'link';
        touch($file);
        symlink($file, $link);
        $this->filesystem->symlink($file, $link);
        $this->assertTrue(is_link($link));
        $this->assertEquals($file, readlink($link));
    }
    public function testSymlinkCreatesTargetDirectoryIfItDoesNotExist()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $file = $this->workspace.DIRECTORY_SEPARATOR.'file';
        $link1 = $this->workspace.DIRECTORY_SEPARATOR.'dir'.DIRECTORY_SEPARATOR.'link';
        $link2 = $this->workspace.DIRECTORY_SEPARATOR.'dir'.DIRECTORY_SEPARATOR.'subdir'.DIRECTORY_SEPARATOR.'link';
        touch($file);
        $this->filesystem->symlink($file, $link1);
        $this->filesystem->symlink($file, $link2);
        $this->assertTrue(is_link($link1));
        $this->assertEquals($file, readlink($link1));
        $this->assertTrue(is_link($link2));
        $this->assertEquals($file, readlink($link2));
    }
    /**
     * @dataProvider providePathsForMakePathRelative
     */
    public function testMakePathRelative($endPath, $startPath, $expectedPath)
    {
        $path = $this->filesystem->makePathRelative($endPath, $startPath);
        $this->assertEquals($expectedPath, $path);
    }
    /**
     * @return array
     */
    public function providePathsForMakePathRelative()
    {
        $paths = array(
            array('/var/lib/symfony/src/Symfony/', '/var/lib/symfony/src/Symfony/Component', '../'),
            array('/var/lib/symfony/src/Symfony/', '/var/lib/symfony/src/Symfony/Component/', '../'),
            array('/var/lib/symfony/src/Symfony', '/var/lib/symfony/src/Symfony/Component', '../'),
            array('/var/lib/symfony/src/Symfony', '/var/lib/symfony/src/Symfony/Component/', '../'),
            array('var/lib/symfony/', 'var/lib/symfony/src/Symfony/Component', '../../../'),
            array('/usr/lib/symfony/', '/var/lib/symfony/src/Symfony/Component', '../../../../../../usr/lib/symfony/'),
            array('/var/lib/symfony/src/Symfony/', '/var/lib/symfony/', 'src/Symfony/'),
            array('/aa/bb', '/aa/bb', './'),
            array('/aa/bb', '/aa/bb/', './'),
            array('/aa/bb/', '/aa/bb', './'),
            array('/aa/bb/', '/aa/bb/', './'),
            array('/aa/bb/cc', '/aa/bb/cc/dd', '../'),
            array('/aa/bb/cc', '/aa/bb/cc/dd/', '../'),
            array('/aa/bb/cc/', '/aa/bb/cc/dd', '../'),
            array('/aa/bb/cc/', '/aa/bb/cc/dd/', '../'),
            array('/aa/bb/cc', '/aa', 'bb/cc/'),
            array('/aa/bb/cc', '/aa/', 'bb/cc/'),
            array('/aa/bb/cc/', '/aa', 'bb/cc/'),
            array('/aa/bb/cc/', '/aa/', 'bb/cc/'),
            array('/a/aab/bb', '/a/aa', '../aab/bb/'),
            array('/a/aab/bb', '/a/aa/', '../aab/bb/'),
            array('/a/aab/bb/', '/a/aa', '../aab/bb/'),
            array('/a/aab/bb/', '/a/aa/', '../aab/bb/'),
        );
        if ('\\' === DIRECTORY_SEPARATOR) {
            $paths[] = array('c:\var\lib/symfony/src/Symfony/', 'c:/var/lib/symfony/', 'src/Symfony/');
        }
        return $paths;
    }
    public function testMirrorCopiesFilesAndDirectoriesRecursively()
    {
        $sourcePath = $this->workspace.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR;
        $directory = $sourcePath.'directory'.DIRECTORY_SEPARATOR;
        $file1 = $directory.'file1';
        $file2 = $sourcePath.'file2';
        mkdir($sourcePath);
        mkdir($directory);
        file_put_contents($file1, 'FILE1');
        file_put_contents($file2, 'FILE2');
        $targetPath = $this->workspace.DIRECTORY_SEPARATOR.'target'.DIRECTORY_SEPARATOR;
        $this->filesystem->mirror($sourcePath, $targetPath);
        $this->assertTrue(is_dir($targetPath));
        $this->assertTrue(is_dir($targetPath.'directory'));
        $this->assertFileEquals($file1, $targetPath.'directory'.DIRECTORY_SEPARATOR.'file1');
        $this->assertFileEquals($file2, $targetPath.'file2');
        $this->filesystem->remove($file1);
        $this->filesystem->mirror($sourcePath, $targetPath, null, array('delete' => false));
        $this->assertTrue($this->filesystem->exists($targetPath.'directory'.DIRECTORY_SEPARATOR.'file1'));
        $this->filesystem->mirror($sourcePath, $targetPath, null, array('delete' => true));
        $this->assertFalse($this->filesystem->exists($targetPath.'directory'.DIRECTORY_SEPARATOR.'file1'));
        file_put_contents($file1, 'FILE1');
        $this->filesystem->mirror($sourcePath, $targetPath, null, array('delete' => true));
        $this->assertTrue($this->filesystem->exists($targetPath.'directory'.DIRECTORY_SEPARATOR.'file1'));
        $this->filesystem->remove($directory);
        $this->filesystem->mirror($sourcePath, $targetPath, null, array('delete' => true));
        $this->assertFalse($this->filesystem->exists($targetPath.'directory'));
        $this->assertFalse($this->filesystem->exists($targetPath.'directory'.DIRECTORY_SEPARATOR.'file1'));
    }
    public function testMirrorCreatesEmptyDirectory()
    {
        $sourcePath = $this->workspace.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR;
        mkdir($sourcePath);
        $targetPath = $this->workspace.DIRECTORY_SEPARATOR.'target'.DIRECTORY_SEPARATOR;
        $this->filesystem->mirror($sourcePath, $targetPath);
        $this->assertTrue(is_dir($targetPath));
        $this->filesystem->remove($sourcePath);
    }
    public function testMirrorCopiesLinks()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $sourcePath = $this->workspace.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR;
        mkdir($sourcePath);
        file_put_contents($sourcePath.'file1', 'FILE1');
        symlink($sourcePath.'file1', $sourcePath.'link1');
        $targetPath = $this->workspace.DIRECTORY_SEPARATOR.'target'.DIRECTORY_SEPARATOR;
        $this->filesystem->mirror($sourcePath, $targetPath);
        $this->assertTrue(is_dir($targetPath));
        $this->assertFileEquals($sourcePath.'file1', $targetPath.DIRECTORY_SEPARATOR.'link1');
        $this->assertTrue(is_link($targetPath.DIRECTORY_SEPARATOR.'link1'));
    }
    public function testMirrorCopiesLinkedDirectoryContents()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $sourcePath = $this->workspace.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR;
        mkdir($sourcePath.'nested/', 0777, true);
        file_put_contents($sourcePath.'/nested/file1.txt', 'FILE1');
        // Note: We symlink directory, not file
        symlink($sourcePath.'nested', $sourcePath.'link1');
        $targetPath = $this->workspace.DIRECTORY_SEPARATOR.'target'.DIRECTORY_SEPARATOR;
        $this->filesystem->mirror($sourcePath, $targetPath);
        $this->assertTrue(is_dir($targetPath));
        $this->assertFileEquals($sourcePath.'/nested/file1.txt', $targetPath.DIRECTORY_SEPARATOR.'link1/file1.txt');
        $this->assertTrue(is_link($targetPath.DIRECTORY_SEPARATOR.'link1'));
    }
    public function testMirrorCopiesRelativeLinkedContents()
    {
        $this->markAsSkippedIfSymlinkIsMissing();
        $sourcePath = $this->workspace.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR;
        $oldPath = getcwd();
        mkdir($sourcePath.'nested/', 0777, true);
        file_put_contents($sourcePath.'/nested/file1.txt', 'FILE1');
        // Note: Create relative symlink
        chdir($sourcePath);
        symlink('nested', 'link1');
        chdir($oldPath);
        $targetPath = $this->workspace.DIRECTORY_SEPARATOR.'target'.DIRECTORY_SEPARATOR;
        $this->filesystem->mirror($sourcePath, $targetPath);
        $this->assertTrue(is_dir($targetPath));
        $this->assertFileEquals($sourcePath.'/nested/file1.txt', $targetPath.DIRECTORY_SEPARATOR.'link1/file1.txt');
        $this->assertTrue(is_link($targetPath.DIRECTORY_SEPARATOR.'link1'));
        $this->assertEquals($sourcePath.'nested', readlink($targetPath.DIRECTORY_SEPARATOR.'link1'));
    }
    /**
     * @dataProvider providePathsForIsAbsolutePath
     */
    public function testIsAbsolutePath($path, $expectedResult)
    {
        $result = $this->filesystem->isAbsolutePath($path);
        $this->assertEquals($expectedResult, $result);
    }
    /**
     * @return array
     */
    public function providePathsForIsAbsolutePath()
    {
        return array(
            array('/var/lib', true),
            array('c:\\\\var\\lib', true),
            array('\\var\\lib', true),
            array('var/lib', false),
            array('../var/lib', false),
            array('', false),
            array(null, false),
        );
    }
    public function testDumpFile()
    {
        $filename = $this->workspace.DIRECTORY_SEPARATOR.'foo'.DIRECTORY_SEPARATOR.'baz.txt';
        $this->filesystem->dumpFile($filename, 'bar');
        $this->assertFileExists($filename);
        $this->assertSame('bar', file_get_contents($filename));

    }
    public function testDumpFileOverwritesAnExistingFile()
    {
        $filename = $this->workspace.DIRECTORY_SEPARATOR.'foo.txt';
        file_put_contents($filename, 'FOO BAR');
        $this->filesystem->dumpFile($filename, 'bar');
        $this->assertFileExists($filename);
        $this->assertSame('bar', file_get_contents($filename));
    }
}
