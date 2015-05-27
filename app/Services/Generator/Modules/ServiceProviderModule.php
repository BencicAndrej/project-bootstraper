<?php namespace Norm\Services\Generator\Modules;

use Illuminate\Filesystem\Filesystem;
use Norm\Services\Generator\Node;
use Norm\Services\Generator\Template;

class ServiceProviderModule implements Module {

	/**
	 * Location of the template.
	 *
	 * @var string
	 */
	protected $template = 'serviceProvider.template';

	/**
	 * @var Filesystem
	 */
	protected $file;

	public function generate(Node $node) {
		$this->file = new Filesystem();

		$this->repositoryProvider($node);
	}

	protected function repositoryProvider(Node $node) {

		$repositories = $node->getChildren('repository', true);

		$providers = "";
		/** @var Node $repository */
		foreach ($repositories as $repository) {
			$entity = $repository->findParent('entity', 'name');
			$namespace = "Norm\\Entities\\{$entity}\\Repository\\";

			$providers .= $this->provider(
				$namespace . $repository->getAttribute('name'),
				"\\" . $namespace . $repository->getAttribute(
					$repository->getAttribute('interface') === "true" ?
						'implementation' : 'name'
				),
				"new \\Norm\\Entities\\{$entity}\\{$entity}()"
			);


		}

		$template = new Template(config('generator.templates_path') . $this->template, [
			'providers' => $providers
		]);

		$targetPath = config('generator.workbench_path') . $node->getAttribute('name')
			. '/app/Providers/RepositoryServiceProvider.php';

		$template->generate($targetPath);

		//Include the service provider
		//'providers' => [
		$appConfigPath = config('generator.workbench_path') . "{$node->getAttribute('name')}/config/app.php";
		$appConfigData = $this->file->get($appConfigPath);

		preg_match("/'providers' => \[(.*?)\],/s", $this->file->get($appConfigPath), $res);

		$original = $res[1];
		$providers = $original . "\t'Norm\\Providers\\RepositoryServiceProvider',\n";

		$this->file->put(
			$appConfigPath,
			preg_replace(
				"/'providers' => \[(.*?)\],/s",
				"'providers' => [$providers\n\t],",
				$appConfigData
			)
		);

	}

	protected function provider($interface, $implementation, $arguments = "") {
		return <<<EOF
		\$this->app->bind('$interface', function (\$app) {
			return new $implementation($arguments);
		});
EOF;
	}
}