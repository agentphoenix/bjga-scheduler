<?php namespace Scheduler\Interfaces;

use Illuminate\Support\Collection;

interface ServiceRepositoryInterface {

	public function all();
	public function allByCategory();
	public function allForDropdownByCategory();
	public function allPrograms($timeframe = false);
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function findBySlug($slug);
	public function forDropdown(Collection $collection, $key, $value);
	public function getAttendees($id);
	public function getValues($category);
	public function update($id, array $data);
	
}