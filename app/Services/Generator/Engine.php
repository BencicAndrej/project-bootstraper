<?php namespace Norm\Services\Generator;

use Norm\Services\Generator\Modules\EntityModule;
use Norm\Services\Generator\Modules\Module;
use Norm\Services\Generator\Readers\XmlNodeReader;

class Engine {
	/**
	 * Loaded modules.
	 *
	 * @var array
	 */
	protected $modules = [
		'entity'    =>      EntityModule::class
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
			/** @var Module $module */
			$module = new $this->modules[$nodeName]();
			$module->generate($node);
		}

		foreach($node->getChildren() as $child) {
			$this->traverseTree($child);
		}
	}
}