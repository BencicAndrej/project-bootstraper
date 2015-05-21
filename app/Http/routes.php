<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Norm\Services\Generator\Engine;
use Norm\Services\Generator\Readers\XmlNodeReader;

get('/', function() {
	$engine = (new Engine())->run();

	dd("Done");

	dd("true" == true);

	$reader = new XmlNodeReader(base_path('generator.xml'));
	dd(base_path('resources/workbench'), __DIR__ . "/resources/workbench",$_SERVER['DOCUMENT_ROOT']);

	$node = $reader->getNodeTree();

	$template = new \Norm\Services\Generator\Template(base_path('app/Services/Generator/Templates/model.template'), [
		'namespace' => 'Norm\\User',
		'name' => 'User',
		'tableName' => "'users'",
		'guarded' => "'id'",
	]);

//	$template->generate(base_path('test_generator.php'));
	dd($node);
});