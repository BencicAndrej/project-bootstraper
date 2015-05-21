<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	protected $basePath;

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		$this->basePath = __DIR__ . '/../resources/temp/';

		return $app;
	}

	protected function deleteDirectory($dir) {
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}

}
