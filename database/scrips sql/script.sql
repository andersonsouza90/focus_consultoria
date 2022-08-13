create database focus_servicos;

create table users(
	id_usuario int NOT NULL AUTO_INCREMENT,
    nome varchar(255) NOT NULL,
    cnpj varchar(18),
    password varchar(255),
    PRIMARY KEY (id_usuario)
);

insert into users (nome, cnpj, password)
values ('teste', '123', '$2y$10$1BYZJtLTT0RcB5IlLeoXeO4dq3uuFSDX2c9A1OqegVkxmjeSlviYK'); ## password = 123