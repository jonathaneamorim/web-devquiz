# web-devquiz
Sistema de gerenciamento de quizzes voltado para turmas do curso de Análise e Desenvolvimento de Sistemas (ADS). Construído utilizando a linguagem PHP com o padrão de arquitetura MVC (Model-View-Controller).

## Estrutura
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

## Rodar a aplicação (running)




## Melhorias
Evitar lógica complexa direto no controller
Em sistemas grandes, parte da lógica (ex: validação da nova senha) pode ser extraída para:

Um Service (UserService::validateNewPassword())

Ou método no Model

Centralizar mensagens de erro
Para facilitar manutenção e internacionalização, algumas empresas centralizam mensagens de erro em um arquivo (como messages.php ou constantes).
