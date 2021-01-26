# netpeak-test

CREATE table products (
id INT primary key auto_increment,
name varchar(255) null,
img_path varchar(255) null,
add_name varchar(255) null,
price integer null,
created_at timestamp default NOW(),
updated_at timestamp default NOW()
);


CREATE table comments (
id INT primary key auto_increment,
product_id INT,
rating INT null,
add_name varchar(255) null,
text varchar(255) null,
created_at timestamp default NOW(),
updated_at timestamp default NOW(),
FOREIGN KEY (product_id)  REFERENCES products (id) ON DELETE CASCADE
);