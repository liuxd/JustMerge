<?php
namespace controller;
use core as c;
use util as u;
use model as m;

class Base extends c\Controller
{
	public function handle()
	{
		$cfg = c\Config::get('db');
		$result = u\DB::getInstance($cfg['data']['dsn']);

		return parent::handle();
	}
}

# end of this file
