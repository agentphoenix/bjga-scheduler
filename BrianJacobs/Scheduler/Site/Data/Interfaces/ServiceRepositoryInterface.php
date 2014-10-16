<?php namespace Scheduler\Data\Interfaces;

use Illuminate\Support\Collection;

interface ServiceRepositoryInterface {

	public function all($onlyActive = false);
	public function allByCategory($onlyActive = false);
	public function allForDropdownByCategory($onlyActive = false);
	public function allPrograms($timeframe = false, $onlyActive = false);
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function findBySlug($slug);
	public function forDropdown(Collection $collection, $key, $value);
	public function getAttendees($id);
	public function getValues($category, $onlyActive = false);
	public function getValuesByInstructor($category, $onlyActive = false);
	public function update($id, array $data);
	
}