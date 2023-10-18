CREATE TABLE mensagens(
    id int auto_increment primary key,
    mensagem text,
    usuario_id INT
);

CREATE TABLE usuarios(
    id int auto_increment primary key,
    nome varchar(255),
    usuario varchar(255),
    senha_usuario varchar(255)
);