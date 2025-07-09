# Fontes de pesquisa

## PHP
### Funções úteis
- Verificar senha hashada - [link](https://www.php.net/manual/en/function.password-verify.php)
- Validar regex - [link](https://www.php.net/manual/en/function.preg-match.php)
- Get data put method - [link](https://www.sitepoint.com/community/t/put-method/41476/4)
- json_decode(file_get_contents("php://input")) - [link](https://www.php.net/manual/en/function.file-get-contents.php)
- `$this->db->lastInsertId()` - [link](https://www.php.net/manual/en/function.password-verify.php)
    - Retorna o ultimo id adicionado, nesse caso o valor adicionado.
- LowerCase - [link](https://www.php.net/manual/pt_BR/function.strtolower.php)
- Transformar o dado em uma string para o html - [link](https://www.php.net/manual/en/function.htmlspecialchars.php)
- Remover caractere do fim de uma string - [link](https://www.php.net/manual/pt_BR/function.rtrim.php)

### Best Practice (Boas práticas)
- O return false em exceções na model evitará ambiguidade no caso de retornar null - [link](https://www.reddit.com/r/PHPhelp/comments/p1by7y/best_practice_for_returning_a_false_value_from_a/)
- Exibir erros em página - [link](https://pt.stackoverflow.com/questions/106562/por-que-usar-error-reporting-com-display-errors-e-display-startup-errors)

## Javascript
### Funções úteis
- Códigos de resposta - [link](https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Reference/Status) 
- find() - [link](https://api.jquery.com/find/)
- Evento acionar um input - [link](https://www.w3schools.com/jsref/event_onfocusin.asp)
- Método de Element que percorre o elemento e seus pais (indo em direção a raiz) até encontrar uma correspondencia- [link](https://developer.mozilla.org/en-US/docs/Web/API/Element/closest)
    - `const form = $(this).closest('.pergunta-bloco');`
- Spread - Espalhar itens de um array ou objeto (combinar, copiar, passar valores individualmente) - [link](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Reference/Operators/Spread_syntax)
- Serialize() - [link](https://api.jquery.com/serialize/)
- Close bootstrap modal - [link](https://stackoverflow.com/questions/16493280/close-bootstrap-modal) 
- Clear fields of a form - [link](https://stackoverflow.com/questions/6653556/jquery-javascript-function-to-clear-all-the-fields-of-a-form)
- Capturar atributo - [link](https://www.w3schools.com/tags/att_data-.asp)
- JQXHR - [link](https://stackoverflow.com/questions/7638847/understanding-jquerys-jqxhr)
- JQXHR Object - [link](https://www.sitepoint.com/jqxhr-object/)
- Array.isArray() - [link](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/isArray)
- JQuery .append() - [link](https://stackoverflow.com/questions/9114565/jquery-appending-a-div-to-body-the-body-is-the-object )

### Best Practices
- Embaralhador de array - [link](https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array)
- Tipos de corpos de requisição application/x-www-form-urlencoded | multipart/form-data - [link 1](https://pt.stackoverflow.com/questions/103157/qual-%C3%A9-a-diferen%C3%A7a-entre-x-www-form-urlencoded-e-form-data)
    - Tipos de corpos de requisição application/x-www-form-urlencoded | multipart/form-data - [link](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/POST)
    - application/x-www-form-urlencoded = campo1=valor1&campo2=valor2
    - multipart/form-data = Enviar arquivos + dados
- XHR - Error statment
    - xhr: objeto XMLHttpRequest
    - textStatus: string com o tipo do erro ("timeout", "error", "abort", etc)
    - errorThrown: mensagem do erro (string ou null) - Exceção tratada
- XHR - success statment
    - data: resposta do servidor
    - textStatus: string com o status ("success" ou "error")
    - xhr: objeto XMLHttpRequest com status, headers, etc.