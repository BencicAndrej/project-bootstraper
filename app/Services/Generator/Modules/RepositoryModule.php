<?php namespace Norm\Services\Generator\Modules;

use Norm\Services\Generator\Engine;
use Norm\Services\Generator\Node;
use Norm\Services\Generator\Template;

class RepositoryModule implements Module {

	protected $repositoryTemplate = 'repository.template';
	protected $interfaceTemplate = 'repositoryInterface.template';

	protected $baseNamespace = "Norm\\Entities";

	protected $model;

	public function generate(Node $node) {
		$this->model = $node->findParent('entity', 'name');

		$repositoryName =
			$node->getAttribute(($node->getAttribute('interface') === 'true') ? 'implementation' : 'name');

		$repositoryAttributes = [
			'model' => $this->model,
			'use'   => $this->formRepositoryUse(),
			'name'  => "{$repositoryName} extends AbstractEloquentRepository"
		];

		if ($node->getAttribute("interface") === "true") {
			$repositoryAttributes['name'] .= " implements {$this->model}Repository";

			$template = new Template(config('generator.templates_path') . $this->interfaceTemplate, [
				'model' => $this->model
			]);

			$targetPath = config('generator.workbench_path')
				. $node->findParent('project', 'name') . '/app/Entities/'
				. "{$this->model}/Repository/{$node->getAttribute('name')}.php";

			$template->generate($targetPath);
		}

		$template = new Template(config('generator.templates_path') . $this->repositoryTemplate, $repositoryAttributes);

		$targetPath = config('generator.workbench_path')
			. $node->findParent('project', 'name') . '/app/Entities/'
			. "{$this->model}/Repository/{$repositoryName}.php";

		$template->generate($targetPath);

		$serviceNode = new Node('service', $node);
		$serviceNode->setAttribute('name', 'Repository');
		Engine::runModule(ServiceModule::class, $serviceNode);
	}


	protected function formRepositoryUse() {
		return <<<EOF
use Norm\\Entities\\{$this->model}}\\{$this->model};
use Norm\\Services\\Repository\\AbstractEloquentRepository;
EOF;

	}
}