<?php

class Tree
{
    private $farm_tree_id;
    private $farm_id;
    private $tree_type;
    private $quantity;
    private $area_size;
    private $planting_year;

    public function __construct($farm_id = null, $tree_type = "", $quantity = 0, $area_size = 0, $planting_year = null, $farm_tree_id = null)
    {
        $this->farm_id = $farm_id;
        $this->tree_type = $tree_type;
        $this->quantity = $quantity;
        $this->area_size = $area_size;
        $this->planting_year = $planting_year;
        $this->farm_tree_id = $farm_tree_id;
    }

    public function getFarmTreeId() { return $this->farm_tree_id; }
    public function getFarmId() { return $this->farm_id; }
    public function getTreeType() { return $this->tree_type; }
    public function getQuantity() { return $this->quantity; }
    public function getAreaSize() { return $this->area_size; }
    public function getPlantingYear() { return $this->planting_year; }

    public function setFarmTreeId($farm_tree_id) { $this->farm_tree_id = $farm_tree_id; }
    public function setFarmId($farm_id) { $this->farm_id = $farm_id; }
    public function setTreeType($tree_type) { $this->tree_type = $tree_type; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setAreaSize($area_size) { $this->area_size = $area_size; }
    public function setPlantingYear($planting_year) { $this->planting_year = $planting_year; }
}
