CREATE DATABASE farmacia;
USE farmacia;

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL
);


CREATE TABLE medicamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicamento VARCHAR(100) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    quantidade INT NOT NULL,
    categoria ENUM('Analgésico', 'Antibiótico', 'Anti-inflamatório', 'Outro') NOT NULL,
    data_validade DATE NOT NULL
);

select *from medicamentos;
select *  from administradores