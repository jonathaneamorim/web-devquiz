CREATE TABLE `devquiz`.usuario (
    id CHAR(36) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN DEFAULT FALSE
);

ALTER TABLE `devquiz`.usuario
MODIFY id CHAR(36);

DROP TABLE IF EXISTS usuario ON CASCADE;

DROP TABLE IF EXISTS quiz;
DROP TABLE IF EXISTS usuario;


CREATE TABLE `devquiz`.quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    criadoPor CHAR(36) NOT NULL,
    criadoEm DATETIME DEFAULT CURRENT_TIMESTAMP,
    imagemAssociada LONGTEXT,
    FOREIGN KEY (criadoPor) REFERENCES usuario(id) ON DELETE CASCADE
);

-- Tabela de perguntas
CREATE TABLE `devquiz`.pergunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    quiz_id INT NOT NULL,
    resposta_certa_id INT,
    FOREIGN KEY (quiz_id) REFERENCES quiz(id) ON DELETE CASCADE
);

-- Tabela de respostas
CREATE TABLE `devquiz`.resposta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    pergunta_id INT NOT NULL,
    FOREIGN KEY (pergunta_id) REFERENCES pergunta(id) ON DELETE CASCADE
);

-- Define a resposta correta após as respostas existirem
ALTER TABLE `devquiz`.pergunta
ADD CONSTRAINT fk_resposta_certa
FOREIGN KEY (resposta_certa_id) REFERENCES resposta(id) ON DELETE SET NULL;

-- Tabela de pontuação do usuário (PointBoard)
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


INSERT INTO `devquiz`.usuario (nome, email, senha, isAdmin)
VALUES
('João Silva', 'joao@example.com', 'senha123', FALSE),
('Maria Oliveira', 'maria@example.com', 'admin456', TRUE),
('Carlos Souza', 'carlos@example.com', 'teste789', FALSE);

select * from quiz;


INSERT INTO `devquiz`.quiz (titulo, descricao, criadoPor, imagemAssociada)
VALUES 
(
    'Lógica de Programação Básica',
    'Teste seus conhecimentos sobre estruturas condicionais, variáveis e operadores lógicos.',
    'bcf08c61-53c1-11f0-b48f-a614f33315ed',
    'https://example.com/imagens/quiz-logica.jpg'
),
(
    'Banco de Dados Relacional',
    'Avaliação sobre modelagem de dados, normalização e comandos SQL.',
    'bcf08c61-53c1-11f0-b48f-a614f33315ed',
    'https://example.com/imagens/quiz-bd.jpg'
),
(
    'Desenvolvimento Web com PHP',
    'Quiz com perguntas sobre sintaxe PHP, sessões, e integração com banco de dados.',
    'bcf08c61-53c1-11f0-b48f-a614f33315ed',
    'https://example.com/imagens/quiz-php.jpg'
);



select * from `devquiz`.usuario;

select * from quiz;

update devquiz.usuario set isAdmin = 1 where id = 'bcf08c61-53c1-11f0-b48f-a614f33315ed';



