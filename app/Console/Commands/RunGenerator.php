<?php namespace Norm\Console\Commands;

use Illuminate\Console\Command;
use Norm\Services\Generator\Engine;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RunGenerator extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generator:run';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the project generator.';

	/**
	 * Create a new command instance.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$engine = new Engine($this->argument('configPath'));

		$engine->run();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
			['configPath', InputArgument::OPTIONAL, 'Location of the configuration xml file.', 'generator.xml'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
