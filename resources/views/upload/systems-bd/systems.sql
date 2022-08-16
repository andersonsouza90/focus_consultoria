CREATE TABLE A001_empresa (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  empresa VARCHAR(200) NULL,
  fantasia VARCHAR(200) NULL,
  cpfcnpj VARCHAR(14) NULL,
  inscricaoestadual VARCHAR(14) NULL,
  inscricaomunicipal VARCHAR(14) NULL,
  datacadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dataalteracao TIMESTAMP NULL,
  dataultimoacesso TIMESTAMP NULL,
  ativo INT(1) DEFAULT 1 COMMENT 'ATIVO: 1 => ATIVO, 2 => INATIVO',
  PRIMARY KEY(id)
);

CREATE TABLE A001_usuario (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  login VARCHAR(50) NULL,
  senha VARCHAR(50) NULL,
  datacadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dataalteracao TIMESTAMP NULL,
  dataultimoacesso TIMESTAMP NULL,
   ativo INT(1) DEFAULT 1 COMMENT 'ATIVO: 1 => ATIVO, 2 => INATIVO',
  PRIMARY KEY(id)
);

CREATE TABLE A001_usuario_has_empresa (
  usuario_id INTEGER UNSIGNED NOT NULL,
  empresa_id INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(usuario_id, empresa_id),
  INDEX usuario_has_empresa_FKIndex1(usuario_id),
  INDEX usuario_has_empresa_FKIndex2(empresa_id)
);


