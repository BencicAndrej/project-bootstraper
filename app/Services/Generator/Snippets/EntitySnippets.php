<?php namespace Norm\Services\Generator\Snippets;

abstract class EntitySnippets {

	const TIMESTAMPS = <<<EOF
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public \$timestamps = true;
EOF;

	public static function hasOne($name, $relation, $foreignKey = null, $localKey = null) {
		if ($foreignKey == null && $localKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->hasOne('Norm\\Entities\\$relation\\$relation');
		}
EOF;
		}
		if ($localKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->hasOne('Norm\\Entities\\$relation\\$relation', '$foreignKey');
		}
EOF;
		}

		return <<<EOF
		public function $name() {
			return \$this->hasOne('Norm\\Entities\\$relation\\$relation', '$foreignKey', '$localKey');
		}
EOF;
	}

	public static function belongsTo($name, $relation, $localKey = null, $parentKey = null) {
		if ($localKey == null && $parentKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation');
		}
EOF;
		}
		if ($parentKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation', '$localKey');
		}
EOF;
		}

		return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation', '$localKey', '$parentKey');
		}
EOF;
	}

	public static function hasMany($name, $relation, $foreignKey = null, $localKey = null) {
		if ($foreignKey == null && $localKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation');
		}
EOF;
		}
		if ($localKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation', '$foreignKey');
		}
EOF;
		}

		return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation', '$foreignKey', '$localKey');
		}
EOF;
	}

	public static function belongsToMany($name, $relation, $table = null, $localKey = null, $foreignKey = null) {
		if ($table = null && $localKey == null && $foreignKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation');
		}
EOF;
		}
		if ($foreignKey == null) {
			return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation', '$localKey');
		}
EOF;
		}

		return <<<EOF
		public function $name() {
			return \$this->belongsTo('Norm\\Entities\\$relation\\$relation', '$localKey', '$foreignKey');
		}
EOF;
	}
}