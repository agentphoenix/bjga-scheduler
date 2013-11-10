<?php namespace Scheduler\Repositories;

use Category;
use CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface {

	public function all()
	{
		return Category::all();
	}

}