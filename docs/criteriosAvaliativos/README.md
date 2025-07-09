# Critérios avaliativos explicados
1. Os dados do sistema devem conter uma lista de usuários administradores e uma lista de usuários que respondem o quiz; ambos possuem login (que deve ser um email válido) e senha;
    - No nosso projeto esses dois tipos de usuários são os mesmos, porém o que verifica se um é administrador ou não é a flag `isAdmin` do banco de dados. Essa flag é um campo Booleano que se for 0 (false) o usuário não é administrador e se for 1 (true) ele é administrador. 
    - O login dos dois é feito pelo mesmo lugar.
    - O administrador é definido no banco de dados, porém futuramente é possível modificar o formato e criar um login separado.
    - Ao realizar login, o PHP seta uma nova sessão que informa se o usuário é administrador ou não. Conforme algumas pesquisas, é seguro passar essa flag para a sessão tendo em vista que quem possui essas informações é somente o servidor.

    ![Imagem da flag isAdmin](/docs/media/flagIsAdmin.png)

2. Os usuários administradores podem adicionar, modificar ou deletar perguntas do quiz no banco de dados.
    - Atualmente essa verificação ocorre na view que possui informações sobre a sessão, renderizando certas funções JS na view caso o usuário seja administrador.
    - Também existem diversos bloqueios de rotas que impedem que usuários não-administradores acessem telas onde só administradores possuem acesso. (ex: editQuiz, addNewQuiz).
    
    ![Bloqueio de rota](/docs/media/bloqueioRota.png)

3. Os usuários não-administradores podem se cadastrar, modificar ou deletar seus cadastros, mas não podem acessar os cadastros de outros usuários e nem adicionar ou deletar perguntas;
    - Cada usuário possui acesso apenas aos seus dados.
    - Existe uma tela de perfil que contém seus dados (nome e email) e sua pontuação em quizes realizados.
    - Nesta tela o usuário pode editar seus dados pessoais e também pode editar sua senha.
    - O cadastro de usuários é realizado por meio de um UUID, o que significa que mesmo que ele possua acesso a esses Ids não será possível localizar os dados de outros usuários. Type CHAR(36).
    - Também existe o bloqueio de rotas, o que impede que o usuário com aquele ID (que não é administrador) acessar rotas de administração

     ![Ids](/docs/media/ids.png)


4. Cada usuário terá também uma pontuação associada, correspondendo à porcentagem de respostas corretas em relação ao número de perguntas respondidas na última vez que o usuário resolveu o quiz;
    - Essa pontuação é salva em uma tabela de pontuação que junta os dados do usuário, quiz, quantidade de perguntas, quantidade de acertos, porcentagem de acertos(campo calculado de acordo com o campos anteriores) e data atual em que o quiz foi realizado.
    - Essas informações ficam disponiveis no perfil do usuário

    ![tabela pontuação](/docs/media/tabelaPontuacao.png)

5. O site deve apresentar as perguntas do quiz para os usuários assim que eles efetuarem login, em ordem aleatória;
    - Essa parte executamos de uma forma um pouco diferente e melhor, pois o usuário pode escolher qual quiz deseja realizar naquele momento. Isso também dá a liberdade de acessar outras telas do sistema como o seu perfil.
    - As perguntas são aleatorizadas quando exibidas, assim como as opções.

    ![Lobby](/docs/media/lobby.png)

6. Cada pergunta deve ser de múltipla escolha, com 4 opções, sendo somente uma opção correta;
    - Atualmente o sistema atende esse requisito, permitindo ao administrador cadastrar apenas 4 alternativas para uma questão.

    ![questão](/docs/media/question.png)

7. O site deve realizar cadastro e login de usuário, funções administrativas, apresentação das perguntas, etc, sem atualizar a página inteira, mas apenas as "div's" necessárias, utilizando-do, para isso, métodos AJAX;
    - Essa parte já é realizada, porém como há trocas de URLs e redirecionamentos em algumas partes não é possivel fazer em uma única tela. Talvez com uma flexibilização melhor dos requisitos o sistema possa ser escalável.

    ![Ajax](/docs/media/ajax.png)

8. As linguagens/plataformas utilizadas pelo sistema devem ser: HTML, CSS, PHP, MySQL, JQuery e AJAX.
    - Todas essas são aplicadas pelo sistema.