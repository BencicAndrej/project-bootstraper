<?php namespace Norm\Services\Generator\Modules;

use Norm\Services\Generator\Node;
use Norm\Services\Generator\Template;

class MigrationModule implements Module {

	protected static $index = 0;

	/**
	 * Location of the template.
	 *
	 * @var string
	 */
	protected $template = 'migration.template';

	public function generate(Node $node) {
		$attributes = [
			'uctable' => ucfirst(camel_case($node->getAttribute('table'))),
			'table'   => $node->getAttribute('table'),
			'columns' => $this->formColumns($node)
		];

		$template = new Template(config('generator.templates_path') . $this->template, $attributes);

		$date = getdate();
		$date['mon'] = str_pad($date['mon'], 2, "0", STR_PAD_LEFT);
		$date['mday'] = str_pad($date['mday'], 2, "0", STR_PAD_LEFT);
		$date['index'] = str_pad(static::$index++, 6, "0", STR_PAD_LEFT);
		$targetPath = config('generator.workbench_path') .
			$node->findParent('project', 'name') . '/database/migrations/' .
			"{$date['year']}_{$date['mon']}_{$date['mday']}_{$date['index']}_create_{$node->getAttribute('table')}_table.php";

		$template->generate($targetPath);
	}

	protected function formColumns(Node $node) {
		$columns = "";
		/** @var Node $attribute */
		foreach ($node->getChildren('attribute') as $attribute) {
			if (!empty($columns)) $columns .= "\t\t\t";
			$columns .= "\$table->{$attribute->getAttribute('type')}('{$attribute->getAttribute('name')}')";
			if ($attribute->getAttribute('nullable') === 'true') $columns .= "->nullable()";
			if ($attribute->getAttribute('default')) $columns .= "->default('{$attribute->getAttribute('default')}')";
			if ($attribute->getAttribute('unique') === 'true') $columns .= "->unique()";
			if ($attribute->getAttribute('unsigned') === 'true') $columns .= "->unsigned()";
			$columns .= ";\n";
		}

		if ($node->getAttribute('rememberToken') === 'true') {
			if (!empty($columns)) $columns .= "\t\t\t";
			$columns .= "\$table->rememberToken();\n";
		}

		if (!($node->getAttribute('timestamps') === 'false')) {
			if (!empty($columns)) $columns .= "\t\t\t";
			$columns .= "\$table->timestamps();\n";
		}

		return $columns;
	}
}