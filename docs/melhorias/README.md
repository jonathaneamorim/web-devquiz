# Melhorias econtradas e sugeridas por membros do grupo
Futuras melhorias para serem inseridas no sistema

### Criar método para retorno de dados na controller
- Criar um método para retornar o http_response_code e o codigo
    - Esse método vai receber o codigo e o que vai retornar e vai aplicar as duas e dar um exit no final. Aproveitando que ele ja vai estar ali e colocar um log junto. Fazer validações caso ele seja 200, 201, 400 ou 500

### Inserir método para pegar os quizzes criados pelo administrador
```php
 public function getlistquizByUser() {
     try {
         $quizzes = $this->quiz->getQuizzesByUser(get_session()->id);
         if($quizzes) {
             header('Content-Type: application/json');
             echo json_encode($quizzes);
         }
     } catch(Exception $e) {
         error_log('Erro ao capturar quizzes desse administrador! ' . $e);
     }
 }
```

### Ajustar nomeclatura de funções e rotas
`public function getQuestions($quizId)`

### Melhorar feedback para o usuário
- Criar toasts de feedback pro usuário
- Aparece e some depois de x segundos como uma mensagem

### Inserir loading antes de conseguir trazer informações
No método Ajax
```javascript
beforeSend: function (xhr) {
    console.log('Loading more posts...')
    button.text('Loading');
}
```

### Confirmação de exclusão
Adicionar verificação para deletar quiz (tem certeza que deseja deletar quiz?)

### Clean code
- Se tiver muita rota, usar if-else deixará o sistema poluído. 
- switch-case com regex pode ajudar mas como ja tem o preg_match pontualmente, tá aceitável por enquanto.
- Criar uma função no helpers que trata o regex da URI e chamar ele na rota
- Evitar lógica complexa direto no controller
- Em sistemas grandes, parte da lógica (ex: validação da nova senha) pode ser extraída para um Service (UserService::validateNewPassword()) ou método no Model

### Melhoria das exceções do sistema
- Centralizar mensagens de erro em uma camada de exceções
- Para facilitar manutenção e internacionalização, algumas empresas centralizam mensagens de erro em um arquivo (como messages.php ou constantes).