<?php namespace Norm\Services\Generator;

use Norm\Services\Generator\Modules\EntityModule;
use Norm\Services\Generator\Modules\MigrationModule;
use Norm\Services\Generator\Modules\Module;
use Norm\Services\Generator\Modules\ProjectModule;
use Norm\Services\Generator\Modules\RepositoryModule;
use Norm\Services\Generator\Modules\ServiceModule;
use Norm\Services\Generator\Readers\XmlNodeReader;

class Engine {
	/**
	 * Loaded modules.
	 *
	 * @var array
	 */
	protected $modules = [
		'project'    => ProjectModule::class,
		'entity'     => [EntityModule::class, MigrationModule::class],
		'repository' => RepositoryModule::class,
		'service'    => ServiceModule::class,
	];

	/**
	 * Relative path of the config file.
	 *
	 * @var string
	 */
	protected $configPath = 'generator.xml';

	/**
	 * Relative path of the workbench directory.
	 *
	 * @var string
	 */
	protected $workbenchPath = 'resources/workbench/';

	/**
	 * Main function that loads the node tree from the config file, and searches for modules that match.
	 *
	 * @return bool
	 */
	public function run() {
		$nodeReader = new XmlNodeReader(base_path($this->configPath));
		try {
			$tree = $nodeReader->getNodeTree();
			$this->traverseTree($tree);
		} catch (\Exception $e) {
			return false;
		}

		return false;
	}

	protected function traverseTree(Node $node) {
		$nodeName = $node->getName();
		if (array_key_exists($nodeName, $this->modules)) {
			$matchedModules = $this->modules[$nodeName];

			if (!is_array($matchedModules)) $matchedModules = [$matchedModules];

			foreach ($matchedModules as $moduleName) {
				$this->runModule($moduleName, $node);
			}
		}

		foreach ($node->getChildren() as $child) {
			$this->traverseTree($child);
		}
	}

	public static function runModule($moduleName, Node $node) {
		/** @var Module $module */
		$module = new $moduleName();
		$module->generate($node);
	}
}