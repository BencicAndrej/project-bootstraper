<?php namespace Norm\Services\Generator\Modules;

use Illuminate\Filesystem\Filesystem;
use Norm\Services\Generator\Node;

class ServiceModule implements Module {

	public function generate(Node $node) {
		$serviceName = $node->getAttribute('name');
		$servicePath = config('generator.services_path') . $serviceName;
		$targetPath = config('generator.workbench_path')
			. $node->findParent('project', 'name')
			. '/app/Services/' . $serviceName;


		$file = new Filesystem();

		if ($file->isDirectory($servicePath) && !$file->isDirectory($targetPath)) {
			$file->copyDirectory($servicePath, $targetPath);
		}
	}
}