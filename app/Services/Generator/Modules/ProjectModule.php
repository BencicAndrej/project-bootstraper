<?php namespace Norm\Services\Generator\Modules;

use Illuminate\Filesystem\Filesystem;
use Norm\Services\Generator\Node;

class ProjectModule implements Module {

	public function generate(Node $node) {
		$projectPath = config('generator.workbench_path') . $node->getAttribute('name');

		$file = new Filesystem();

		//Clear staging are if necessary.
		if ($file->isDirectory($projectPath)) {
			$file->deleteDirectory($projectPath);
		}

		//Copy core files.
		$file->copyDirectory(config('generator.base_project_path'), $projectPath);

		$envPath = config('generator.workbench_path') . "{$node->getAttribute('name')}/.env";
		if ($node->getAttribute('database')) {
			$file->put(
				$envPath,
				preg_replace(
					"/DB_DATABASE=homestead/",
					"DB_DATABASE={$node->getAttribute('database')}",
					$file->get($envPath)
				)
			);
		}
	}
}