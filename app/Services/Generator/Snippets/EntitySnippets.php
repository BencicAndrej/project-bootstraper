<?php namespace Norm\Services\Generator\Snippets;

use Norm\Services\Generator\Node;

abstract class EntitySnippets {

	const TIMESTAMPS = <<<EOF
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public \$timestamps = false;
EOF;

	public static function relation(Node $relationNode) {

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
}