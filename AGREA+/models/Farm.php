<?php

class Farm
{
    private $farm_id;
    private $user_id;
    private $farm_name;
    private $location;
    private $area_size;
    private $tree_type_count;

    public function __construct($farm_name = "", $location = "", $area_size = 0, $user_id = null, $farm_id = null, $tree_type_count = 0)
    {
        $this->farm_name = $farm_name;
        $this->location = $location;
        $this->area_size = $area_size;
        $this->user_id = $user_id;
        $this->farm_id = $farm_id;
        $this->tree_type_count = $tree_type_count;
    }

    public function getFarmId() { return $this->farm_id; }
    public function getUserId() { return $this->user_id; }
    public function getFarmName() { return $this->farm_name; }
    public function getLocation() { return $this->location; }
    public function getAreaSize() { return $this->area_size; }
    public function getTreeTypeCount() { return $this->tree_type_count; }

    public function setFarmId($farm_id) { $this->farm_id = $farm_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setFarmName($farm_name) { $this->farm_name = $farm_name; }
    public function setLocation($location) { $this->location = $location; }
    public function setAreaSize($area_size) { $this->area_size = $area_size; }
    public function setTreeTypeCount($tree_type_count) { $this->tree_type_count = $tree_type_count; }
}
