<?php

use Norm\Services\Generator\File;

class FileTest extends TestCase {

	protected $testFile = 'testFile.php';

	/**
	 * @var File
	 */
	protected $file;

	public function setUp() {
		parent::setUp();

		$this->testFile = $this->basePath . $this->testFile;

		if (!is_dir(pathinfo($this->testFile, PATHINFO_DIRNAME)))
			mkdir(pathinfo($this->testFile, PATHINFO_DIRNAME), 0755);
		touch($this->testFile);
		file_put_contents($this->testFile, "Hello there!");

		$this->file = new File();

		$this->assertTrue(file_exists($this->testFile), "Test file does not exist.");
	}

	public function testFileExistsFunction() {
		$this->assertFalse(file_exists($this->basePath . "imaginary.php"), "Test file that should not exist, exists.");

		$this->assertTrue($this->file->exists($this->testFile));
		$this->assertFalse($this->file->exists($this->basePath . "imaginary.php"));
	}

	/**
	 * @expectedException \Norm\Services\Generator\Exceptions\FileNotFoundException
	 */
	public function testFileGetFunction() {
		$this->assertEquals("Hello there!", $this->file->get($this->testFile), "Contents retrieved do not match the expected");

		$this->file->get($this->basePath . "imaginary.php");
	}

	public function testFilePutFunction() {
		$this->file->put($this->testFile, "A string of characters");

		$this->assertEquals("A string of characters", file_get_contents($this->testFile));
	}

	public function testFilePrependFunction() {
		$this->file->prepend($this->testFile, "Hello. ");

		$this->assertEquals("Hello. Hello there!", file_get_contents($this->testFile));
	}

	public function testFileAppendFunction() {
		$this->file->append($this->testFile, " It is a beautiful day!");

		$this->assertEquals("Hello there! It is a beautiful day!", file_get_contents($this->testFile));
	}

	public function testFileDeleteFunction() {
		touch($this->basePath . "second.php");
		touch($this->basePath . "third.php");

		$this->assertTrue(file_exists($this->testFile));
		$this->assertTrue(file_exists($this->basePath . "second.php"));
		$this->assertTrue(file_exists($this->basePath . "third.php"));

		$this->file->delete($this->basePath . "third.php");

		$this->assertTrue(file_exists($this->testFile));
		$this->assertTrue(file_exists($this->basePath . "second.php"));
		$this->assertFalse(file_exists($this->basePath . "third.php"));

		$this->file->delete($this->basePath . "first.php");

		$this->assertTrue(file_exists($this->testFile));
		$this->assertTrue(file_exists($this->basePath . "second.php"));

		$this->file->delete([
			$this->testFile,
			$this->basePath . "second.php"
		]);

		$this->assertFalse(file_exists($this->testFile));
		$this->assertFalse(file_exists($this->basePath . "second.php"));
	}

	public function testFileMoveFunction() {
		$target = $this->basePath . "moved.php";


		$this->file->move($this->testFile, $target);

		$this->assertTrue(file_exists($target));
		$this->assertFalse(file_exists($this->testFile));
		$this->assertEquals("Hello there!", file_get_contents($target));

		unlink($target);
	}

	public function testFileCopyFunction() {
		$target = $this->basePath . "moved.php";


		$this->file->copy($this->testFile, $target);

		$this->assertTrue(file_exists($target));
		$this->assertTrue(file_exists($this->testFile));
		$this->assertEquals("Hello there!", file_get_contents($target));

		unlink($target);
	}

	public function testFileNameFunction() {
		$this->assertEquals("testFile", $this->file->name($this->testFile));
	}

	public function testFileExtensionFunction() {
		$this->assertEquals("php", $this->file->extension($this->testFile));
	}

	public function testFileTypeFunction() {
		$this->assertEquals("file", $this->file->type($this->testFile));
	}

	public function testFileMimeTypeFunction() {
		$this->assertEquals("text/plain", $this->file->mimeType($this->testFile));
	}

	public function testFileSizeFunction() {
		$this->assertEquals(12, $this->file->size($this->testFile));
	}

	public function testFileIsDirectoryFunction() {
		$this->assertFalse($this->file->isDirectory($this->testFile));
		$this->assertTrue($this->file->isDirectory($this->basePath));
	}

	public function testFileIsFileFunction() {
		$this->assertTrue($this->file->isFile($this->testFile));
		$this->assertFalse($this->file->isFile($this->basePath));
	}

	public function testFileFilesFunction() {
		$this->assertEquals([$this->testFile], $this->file->files($this->basePath));
	}

	public function testFileCreateStructureFunction() {

		$this->file->createStructure($this->basePath . "one/two/test.php");
		$this->assertTrue(is_dir($this->basePath . "one/two/"));

		$this->file->createStructure($this->basePath . "three/four");
		$this->assertTrue(is_dir($this->basePath . "three"));
		$this->assertFalse(is_dir($this->basePath . "three/four")); //!

	}

	public function tearDown() {
		parent::tearDown();

		$this->deleteDirectory($this->basePath);
	}

}
