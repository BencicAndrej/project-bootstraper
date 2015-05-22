<?php namespace Norm\Services\Generator\Modules;

use Illuminate\Filesystem\Filesystem;
use Norm\Services\Generator\Node;

class ProjectModule implements Module {

	public function generate(Node $node) {
		$projectPath = config('generator.workbench_path') . $node->getAttribute('name');

		$file = new Filesystem();

		if ($file->isDirectory($projectPath)) {
			$file->deleteDirectory($projectPath);
		}

		$file->copyDirectory(config('generator.base_project_path'), $projectPath);

		
		shell_exec("php {$projectPath}/artisan app:name Norm");
	}
}