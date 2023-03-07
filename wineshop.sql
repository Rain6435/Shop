create database wineshop;


create table wineshop.client (
    ID int not null auto_increment, 
    FirstName varchar(50),
    LastName varchar(50), 
    Age int, 
    EMail text, 
    AdressNum text, 
    AdressName text, 
    AdressZIP text, 
    PhoneNum text, 
    Sex varchar(10), 
    Password text, 
    primary key(ID)
    );
create table wineshop.products(
    ID int not null auto_increment,
    Domain text,
    Type varchar(50),
    Origin text,
    Price double,
    Image text,
    Quantity int,
    primary key(ID)
);
create table wineshop.cart (
    ID int not null auto_increment,
    UserID int, 
    ProductID int, 
    Quantity int, 
    State varchar(10), 
    primary key(ID),
    foreign key(UserID) references client(ID),
    foreign key(ProductID) references products(ID)
    );
create table wineshop.orders(
    ID int not null auto_increment,
    UserID int,
    Total double,
    CartIDs text,
    primary key(ID),
    foreign key(UserID) references client(ID)
);