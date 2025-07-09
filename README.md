# web-devquiz
Sistema de gerenciamento de quizzes voltado para turmas do curso de Análise e Desenvolvimento de Sistemas (ADS). Construído utilizando a linguagem PHP com o padrão de arquitetura MVC (Model-View-Controller).

## Estrutura de pastas
```plaintext
Nexus-PHP-MVC  
├── migrations  
├── index.php 
├── .htaccess 
├── app/  
│   ├── controller/  
│   │   └── Controller.php  
│   ├── model/ 
│   │   │── Database.php
│   │   └── portal/
│   │       └── Controller.php   
│   ├── views/
│   │   │── view.php
│   │   └── commons/
│   │       └── default.php  
│   ├── utils/  
│   │   └── helpers.php  
└── docs/  
    └── README.md  
```

## Modelamento de banco de dados
![Modelamento de banco de dados](/docs/media/databaseModel.png)
- A maior parte das relações do projeto se dão por chaves estrangeiras em outras tabelas

## Rodar a aplicação (running)
1. Pré-requisitos
Certifique-se de ter o **XAMPP** instalado em sua máquina.
2. Clonando o projeto:
Navegue até a pasta `lampp/htdocs` e execute os seguintes comandos no terminal:
```bash
git init
git remote add origin https://github.com/jonathaneamorim/web-devquiz.git 
git pull origin main
```
3. Importando o banco de dados:
- Acesse o **phpMyAdmin** com seu usuário e senha do MySQL local.
- Crie um banco de dados (caso necessário).
- Vá até a aba SQL, clique em Importar e selecione o arquivo `dump.sql` localizado em `/migrations`.

4. Configurando a conexão com o banco:
Edite o arquivo `/app/model/Database.php` e atualize as credenciais de conexão (host, nome do banco, usuário e senha) conforme sua configuração local.

5. Iniciando a aplicação
Após essas etapas, inicie o Apache e MySQL no XAMPP e acesse o projeto pelo navegador através de http://localhost/.

## Checklist da avaliação
Para visualizar os critérios avaliativos feitas pelo grupo acesse o [link](/docs/criteriosAvaliativos/README.md)

## Melhorias
Para visualizar as futuras melhorias encontradas pelo grupo acesso o [link](/docs/melhorias/README.md)

## Fontes consultadas para a construção
Para visualizar as fontes utilizadas para pesquisa acesse o [link](/docs/fontes/README.md)

## Documentações utilizadas no projetos
Para acessar os links das documentações acesse o [link](/docs/documentacoes/README.md)