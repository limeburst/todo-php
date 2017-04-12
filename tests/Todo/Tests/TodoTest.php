<?php
namespace Todo\Tests;

use Silex\WebTestCase;

class TodoTest extends WebTestCase
{
	public function createApplication()
	{
		$app = require __DIR__ . '/../../../src/app.php';
		$app['session.test'] = true;
		$app['debug'] = true;
		unset($app['exception_handler']);
		return $app;
	}

	public function testHome()
	{
		$client = $this->createClient();
		$crawler = $client->request('GET', '/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1, $crawler->filter('p:contains("todo.")'));
	}

	public function testLoginPage()
	{
		$client = $this->createClient();
		$crawler = $client->request('GET', '/login/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1, $crawler->filter('h2:contains("login")'));
		$this->assertCount(1, $crawler->filter('h2:contains("create account")'));
	}
}
