CREATE TABLE usuario (
    id CHAR(36) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN DEFAULT FALSE
);

CREATE TABLE quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    criadoPor CHAR(36) NOT NULL,
    criadoEm DATETIME DEFAULT CURRENT_TIMESTAMP,
    imagemAssociada LONGTEXT,
    FOREIGN KEY (criadoPor) REFERENCES usuario(id) ON DELETE CASCADE
);

CREATE TABLE pergunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    quiz_id INT NOT NULL,
    resposta_certa_id INT,
    FOREIGN KEY (quiz_id) REFERENCES quiz(id) ON DELETE CASCADE
);

CREATE TABLE resposta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    pergunta_id INT NOT NULL,
    FOREIGN KEY (pergunta_id) REFERENCES pergunta(id) ON DELETE CASCADE
);

ALTER TABLE pergunta
ADD CONSTRAINT fk_resposta_certa
FOREIGN KEY (resposta_certa_id) REFERENCES resposta(id) ON DELETE SET NULL;

CREATE TABLE tabelaPontuacao (
    usuarioId CHAR(36) NOT NULL,
    quizId INT NOT NULL,
    acertos INT NOT NULL,
    total INT NOT NULL,
    porcentagemAcertos DECIMAL(5,2) GENERATED ALWAYS AS (acertos / total * 100) STORED,
    ultimaVezRespondido DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuarioId) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (quizId) REFERENCES quiz(id) ON DELETE CASCADE
);