create database focus_servicos;
create table users(
	id_usuario int NOT NULL AUTO_INCREMENT,
    razao_social varchar(255) NOT NULL,
    fantasia varchar(255) NOT NULL,
    cnpj varchar(18),
    endereco varchar(255) NOT NULL,
    telefone varchar(18) NOT NULL,
    email varchar(255) NOT NULL,    
    password varchar(255),
    created_at timestamp default now(),
    updated_at timestamp,
    PRIMARY KEY (id_usuario)
);

insert into users (razao_social, fantasia, cnpj, endereco, telefone, email, password)
values ('razao social', 'Anderson', '123', 'Endereço completo', '(11) 99191-9191', 'teste@gmail.com', '$2y$10$1BYZJtLTT0RcB5IlLeoXeO4dq3uuFSDX2c9A1OqegVkxmjeSlviYK'); ## password = 123