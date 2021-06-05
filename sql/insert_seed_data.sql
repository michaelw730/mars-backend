DELETE FROM item;

INSERT INTO item (description, weight, category_id)
VALUES ('Food', 12, '2');

INSERT INTO item (description, weight, category_id) 
VALUES ('Clothes', 11, '4');

INSERT INTO item (description, weight, category_id)
VALUES ('Oxygen', 9, '5');

INSERT INTO item (description, weight, category_id)
VALUES ('Tools', 8, '5');

DELETE FROM category;

INSERT INTO category (name, priority)
VALUES ('High', 1);

INSERT INTO category (name, priority)
VALUES ('Medium-High', 2);

INSERT INTO category (name, priority)
VALUES ('Medium', 3);

INSERT INTO category (name, priority)
VALUES ('Low-Medium', 4);

INSERT INTO category (name, priority)
VALUES ('Low', 5);