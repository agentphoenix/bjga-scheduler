<?php namespace Scheduler\Interfaces;

interface ServiceRepositoryInterface {

	public function all();
	public function allByCategory();
	public function allForDropdownByCategory();
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function findBySlug($slug);
	public function getValues($category);
	public function update($id, array $data);
	
}