CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    estadio VARCHAR(50) NOT NULL
);

-- Crear tabla partidos
CREATE TABLE partidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idVisitante INT NOT NULL,
    estadio VARCHAR(50) NOT NULL,
    resultado ENUM('1','X','2'),
    jornada INT(11),
    idLocal INT NOT NULL,
    FOREIGN KEY (idVisitante) REFERENCES equipos(id),
    FOREIGN KEY (idLocal) REFERENCES equipos(id)
);