<?php

use Norm\Services\Generator\Template;

class TemplateTest extends TestCase {

	protected $templatePath;

	public function setUp() {
		parent::setUp();

		if (!is_dir($this->basePath))
			mkdir($this->basePath, 0755);

		$this->templatePath = config('generator.templates_path') . "model.template";
	}

	public function testGettingTags() {
		$template = new Template($this->templatePath, []);
		$this->assertEquals([
			'namespace', 'name', 'table', 'guarded', 'body'
		], $template->getTags());
	}

	public function testGettingMissingTags() {
		$template = new Template($this->templatePath, [
			'namespace' => "I'm here",
			'name'      => "I'm also here"
		]);
		$this->assertEquals([
			'table', 'guarded', 'body'
		], $template->getMissingTags());
	}

	/**
	 * @expectedException Norm\Services\Generator\Exceptions\MissingTagException
	 */
	public function testGenerationWithIncompleteArguments() {
		$template = new Template($this->templatePath, []);

		$template->generate($this->basePath . "output.php");
	}

	public function testTemplateGeneration() {
		$template = new Template($this->templatePath, []);

		$attributes = [];
		foreach ($template->getMissingTags() as $tag) {
			$attributes[$tag] = "BLAAAH";
		}

		$template->setAttributes($attributes);

		$template->generate($this->basePath . "output.php");


		$this->assertEquals(0, preg_match_all('/\?\{(.*?)\}/s', file_get_contents($this->basePath . "output.php")));
	}


	public function tearDown() {
		parent::tearDown();

		$this->deleteDirectory($this->basePath);
	}
}