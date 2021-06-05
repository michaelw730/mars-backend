DROP TABLE IF EXISTS item;
CREATE TABLE item (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    description varchar(100),
    weight INTEGER,
    category_id INTEGER
);

DROP TABLE IF EXISTS category;
CREATE TABLE category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name varchar(100),
    priority INTEGER
);
