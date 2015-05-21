<?php namespace Norm\Services\Generator\Modules;

use Norm\Services\Generator\Node;
use Norm\Services\Generator\Snippets\EntitySnippets;
use Norm\Services\Generator\Template;

class EntityModule implements Module {

	/**
	 * Location of the template.
	 *
	 * @var string
	 */
	protected $templatePath = 'app/Services/Generator/Templates/model.template';

	/**
	 * Base namespace for generated entities.
	 *
	 * @var string
	 */
	protected $baseNamespace = 'Norm\\Entities\\';

	public function generate(Node $node) {
		$attributes = $node->getAttributes();
		$attributes['namespace'] = $this->baseNamespace . $node->getAttribute('name');
		$attributes['table'] = "'{$attributes['table']}'";
		$attributes['body'] = $this->formBody($node);
		$attributes['guarded'] = $this->formGuarded($node);

		$template = new Template(base_path($this->templatePath), $attributes);

		$targetPath = config('generator.workbench_path') .
			$node->findParent('project', 'name') . '/app/Entities/' .
			$node->getAttribute('name') . '/' . $node->getAttribute('name') . '.php';

		$template->generate($targetPath);
	}

	protected function formBody(Node $node) {
		$body = "";
		if($node->getAttribute('timestamps') == true) {
			$body .= EntitySnippets::TIMESTAMPS;
		}

		foreach ($node->getChildren('relation') as $relation) {

		}

		return $body;
	}

	protected function formGuarded(Node $node) {
		$guarded = "";
		/** @var Node $guard */
		foreach($node->getChildren('guarded') as $guard) {
			if (!empty($guarded)) $guarded .= ", ";
			$guarded .= "'{$guard->getAttribute('name')}'";
		}

		return $guarded;
	}
}