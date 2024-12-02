
CREATE TABLE  IF NOT EXISTS categories (
    id VARCHAR(36) PRIMARY KEY, -- Using VARCHAR(36) for UUID or similar string IDs
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    parent_id VARCHAR(36) DEFAULT NULL, -- Allows NULL for optional parent-child relationships
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_parent_category FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE CASCADE

);