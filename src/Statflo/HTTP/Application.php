<?php
namespace Statflo\HTTP;

use Silex\Application as BaseApplication;

class Application extends BaseApplication
{
	public function offsetGet($key)
	{
		if (parent::offsetExists($key)) {
			return parent::offsetGet($key);
		}
		return $this->offsetGet('container')->get($key);
	}
	public function offsetExists($key)
	{
		return parent::offsetExists($key) || $this->offsetGet('container')->getContainer()->has($key);
	}
}
