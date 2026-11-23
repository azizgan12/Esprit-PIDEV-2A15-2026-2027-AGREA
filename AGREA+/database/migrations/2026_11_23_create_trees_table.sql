CREATE TABLE IF NOT EXISTS trees (
    farm_tree_id INT NOT NULL AUTO_INCREMENT,
    farm_id INT NOT NULL,
    tree_type VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    area_size DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    planting_year YEAR NOT NULL,
    PRIMARY KEY (farm_tree_id),
    KEY idx_trees_farm_id (farm_id),
    CONSTRAINT fk_trees_farm
        FOREIGN KEY (farm_id) REFERENCES farms (farm_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
