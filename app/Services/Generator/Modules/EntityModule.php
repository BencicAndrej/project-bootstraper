<?php namespace Norm\Services\Generator\Modules;

use Norm\Services\Generator\Node;
use Norm\Services\Generator\Template;

class EntityModule implements Module {

	/**
	 * Location of the template.
	 *
	 * @var string
	 */
	protected $template = 'model.template';

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

		$template = new Template(config('generator.templates_path') . $this->template, $attributes);

		$targetPath = config('generator.workbench_path') .
			$node->findParent('project', 'name') . '/app/Entities/' .
			$node->getAttribute('name') . '/' . $node->getAttribute('name') . '.php';

		$template->generate($targetPath);
	}

	protected function formBody(Node $node) {
		$body = "";
		if ($node->getAttribute('timestamps') === 'false') {
			$body .= self::TIMESTAMPS . "\n\n";
		}

		/** @var Node $relation */
		foreach ($node->getChildren('relation') as $relation) {
			$body .= $this->formRelation($relation) . "\n\n";
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

	protected function formRelation(Node $relationNode) {

		$hasOne = ['foreignKey', 'localKey'];
		$belongsTo = ['localKey', 'foreignKey'];
		$hasMany = ['foreignKey', 'localKey'];
		$belongsToMany = ['table', 'localKey', 'foreignKey'];

		$type = $relationNode->getAttribute('type');
		$name = $relationNode->getAttribute('name');
		$relatedEntity = $relationNode->getAttribute('entity');

		$args = "'Norm\\Entities\\$relatedEntity\\$relatedEntity'";
		foreach ($$type as $var) {
			$value = $relationNode->getAttribute($var);
			if (!empty($value)) {
				$args .= ", '$value'";
			}
			else {
				break;
			}
		}

		return <<<EOF
	public function $name() {
		return \$this->$type($args);
	}
EOF;
	}

	const TIMESTAMPS = <<<EOF
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public \$timestamps = false;
EOF;

}