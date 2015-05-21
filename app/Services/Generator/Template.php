<?php namespace Norm\Services\Generator;

use Norm\Services\Generator\Exceptions\MissingTagException;

class Template {

	/**
	 * @var string
	 */
	protected $templateData;
	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * @var File
	 */
	protected $file;

	/**
	 * @param string $templatePath
	 * @param array $attributes
	 */
	function __construct($templatePath, array $attributes = []) {
		$this->file = new File();

		$this->templateData = $this->file->get($templatePath);
		$this->attributes = $attributes;
	}

	/**
	 * Get all tags detected in the template.
	 *
	 * @return array
	 */
	public function getTags() {
		preg_match_all('/\?\{(.*?)\}/s', $this->templateData, $tags);

		return $tags[1];
	}

	/**
	 * Get all tags missing from the arguments array.
	 *
	 * @return array
	 */
	public function getMissingTags() {
		$presentTags = [];
		foreach ($this->attributes as $key => $value) {
			$presentTags[] = $key;
		}
		return array_values(array_diff($this->getTags(), $presentTags));
	}

	/**
	 * Setter for the attribute: $attributes.
	 *
	 * @param array $attributes
	 * @return Template
	 */
	public function setAttributes(array $attributes) {
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * Add a key-value pair to the list of attributes.
	 *
	 * @param string $key
	 * @param string $value
	 * @return Template
	 */
	public function setAttribute($key, $value) {
		$this->attributes[$key] = $value;

		return $this;
	}

	/**
	 * Generate an instance of the template on the specified location.
	 *
	 * @param string $outputPath
	 * @throws MissingTagException
	 */
	public function generate($outputPath) {
		$diffTags = $this->getMissingTags();

		if ($diffTags !== []) {
			$tags = array_pop($diffTags);
			foreach ($diffTags as $tag) {
				$tags .= ", $tag";
			}
			throw new MissingTagException("Tags [ $tags ] not found.");
		}

		$output = $this->templateData;

		foreach ($this->attributes as $key => $value) {
			$output = preg_replace('/\?\{' . $key . '\}/s', $value, $output);
		}

		$this->file->createStructure($outputPath);

		$this->file->put($outputPath, $output);
	}

}