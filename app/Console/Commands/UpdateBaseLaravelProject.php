<?php namespace Norm\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateBaseLaravelProject extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generator:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update base files used for a Laravel project.';
	/**
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * Create a new command instance.
	 * @param Filesystem $filesystem
	 */
	public function __construct(Filesystem $filesystem) {
		parent::__construct();
		$this->filesystem = $filesystem;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$basePath = config('generator.base_project_path');

		if ($this->filesystem->isDirectory($basePath)) {
			$this->filesystem->deleteDirectory($basePath);
		}

		shell_exec("composer create-project laravel/laravel {$basePath} --prefer-dist");
		shell_exec("php {$basePath}artisan app:name Norm");

		$envPath = $basePath . ".env";
		file_put_contents($envPath, preg_replace("/SomeRandomString/", str_random(32), file_get_contents($envPath)));

		$appConfigPath = $basePath . "config/app.php";
		file_put_contents($appConfigPath, preg_replace("/UTC/", "Europe/Belgrade", file_get_contents($appConfigPath)));

		$ignorePath = $basePath . ".gitignore";
		file_put_contents($ignorePath, "/.idea", FILE_APPEND);

		$this->filesystem->delete($basePath . "composer.lock");

		$this->filesystem->cleanDirectory($basePath . "database/migrations");

		$this->filesystem->deleteDirectory($basePath . "vendor");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
		];
	}

}
