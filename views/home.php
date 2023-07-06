<?php include './views/components/header.php'; ?>

<div class="container">
    <div id="mensagemView">
        <?php if (isset($_SESSION['authMessage']) || isset($_SESSION['welcomeMessage'])) { ?>
            <div class='alert alert-success'>
                <?= $_SESSION['welcomeMessage']; ?>
                <?= $_SESSION['authMessage']; ?>
            </div>
        <?php
            unset($_SESSION['welcomeMessage']);
            unset($_SESSION['authMessage']);
            unset($_SESSION['messageType']);
        }
        ?>
    </div>
    <div class="main-text">
        <section>
            <div class="first-text text">
                
                <h2 class='bg-dark text-light'>Secure Login System, Task and Profile Management</h2>
                <p>
                    A idéia inicial desse projeto era só criar um sistema de login. A coisa está começando a sair um pouco de controle, já que estou me divertindo desenvolvendo ele. Provavelmente vou parar esse projeto em breve.
                </p>
                <p>
                    Mas desde que comecei a programar, eu tinha a ideia de que um sistema de login era uma boa forma de testar e praticar seu conhecimento e habilidade. Certamente envolveriam camadas e camadas de segurança, lógicas e ferramentas complexas de serem utilizadas, coisas difíceis... E eu tava certo, envolve isso tudo aí mesmo.
                </p>
                <p>
                    Existe aquele velho ditado de que "não se deve inventar a roda todas as vezes", e então nas comunidades nós contamos com inúmeras ferramentas, frameworks ou bibliotecas que cuidam dos sistemas de autenticação de usuários, não sendo necessário ficar fazendo tudo toda vez.
                </p>
                <p>
                    Mas este aqui foi um desafio que me propus de programar tudo "na mão", sem o uso de nenhum framework ou biblioteca. Tive a ideia a partir de uma lógica que antigamente podia ser acessada por esse link aqui:
                    <a href="http://jaspan.com/improved_persistent_login_cookie_best_practice">Secure Login System - Jaspan</a>
                </p>
                <p>
                    Mas não sei porquê, hoje em dia não está dando para acessar. Então eu achei esse rapaz aqui que explica direitinho e implementa o passo a passo:
                    <a href="https://youtu.be/sRuRRdJCB8E">Secure Login Form Authentication System</a>
                </p>
                <p>
                    Eu implementei toda a lógica, que vou explicar aqui, e então apliquei tudo em uma arquitetura MVC (Model View Controller).
                </p>
                <p>
                    O arquivo index.php faz o direcionamento das rotas a partir de um array associativo de rotas no arquivo routes.php. A rota acionada chama o seu respectivo controller, que chama o método handler, proveniente da interface ClassHandlerInterface, que realiza a requisição correspondente.
                </p>
                <p>
                    Como eu havia dito, a aplicação cresceu um pouco mais do que eu esperava, e ainda vou gastar algum tempinho me divertindo com isso aqui. Como vão perceber,tem alguma coisa ou outra que não funciona ainda. Depois quero criar algo mais complexo e eficiente, mas provavelmente vou utilizar algum framework. O Front eu gostaria de fazer em React, mas aqui está completamente feito em HTML, Bootstrap e Javascript vanilla.
                </p>
                <p>
                    Sem mais delongas, aqui está como a autenticação funciona. São dois métodos principais:
                </p>
            </div>
        </section>
        <section class='section-text second-text'>
            <div class="text">
                <h4>
                    Método VerifySessionState:
                </h4>
                <p>Verifica se o usuário está logado e retorna um booleano.</p>
                <ol>
                    <li>Verifica se estão definidos os dados de sessão do usuário, que incluem um id de usuário, um token de sessão e um número de série da sessão.</li>
                    <li>Busca se existe um registro de uma sessão com os mesmos dados no banco de dados.</li>
                    <li>Compara os dados de sessão do banco de dados com os fornecidos pelo navegador.</li>
                    <li>Se os dados de sessão forem iguais, retorna true.</li>
                    <li>Caso contrário, cria uma nova sessão no navegador com os dados disponíveis e retorna true.</li>
                    <li>Em todos os outros casos, retorna false, indicando que o usuário não está logado.</li>
                </ol>
            </div>
            <div class="image">
                <img src="../assets/images/login-system-sessionState.png" alt="">
            </div>
        </section>
        <section class='section-text third-text'>
        <div class="text">
                <h4>
                    Método authenticate:
                </h4>
                <p>Verifica os dados de login fornecidos pelo usuário e redireciona o usuário para a página principal em caso positivo. Lança uma exceção caso haja algum problema.</p>
                <ol>
                    <li>Verifica se estão definidos os dados de sessão do usuário, que incluem um id de usuário, um token de sessão e um número de série da sessão.</li>
                    <li>Busca se existe um registro de uma sessão com os mesmos dados no banco de dados.</li>
                    <li>Compara os dados de sessão do banco de dados com os fornecidos pelo navegador.</li>
                    <li>Se os dados de sessão forem iguais, redireciona o usuário para a página de perfil.</li>
                    <li>Caso contrário, cria uma nova sessão no navegador com os dados disponíveis e retorna true.</li>
                    <li>Em todos os outros casos retorna false, indicando que o usuário não está logado.</li>
                </ol>
            </div>
            <div class="image">
                <img src="../assets/images/login-system-auth.png" alt="">
            </div>
        </section>
        <section class="section-text final-text">
            <div class="instalation">
                <ol>
                    <li>Ter o PHP instalado na máquina e a extensão PDO_Mysql habilitada.</li>
                    <li>Rodar o arquivo de migration do banco de dados e iniciar o servidor de DB na porta 3006.</li>
                    <li>Iniciar um servidor php com o comando 'php -S localhost:8080'</li>
                    <li>Alterar as informações de conexão com o banco no arquivo DatabaseConnection na pasta Infrastructure.</li>
                </ol>
            </div>
            <div class="conclusion">
    <p>Quaisquer dúvidas ou sugestões, não deixe de entrar em contato! Ficarei feliz em receber seu feedback. Se você tiver alguma contribuição para o projeto, sinta-se à vontade para abrir um Pull Request no <a href="https://github.com/albuquerque-lucas/login_system">GitHub</a> ou criar um fork. Você também pode entrar em contato comigo por <a href="mailto:seuemail@example.com" class="fa fa-envelope"> e-mail</a>.</p>
    <p>É isso! Espero que seja útil, obrigado!</p>
</div>
            </div>
        </section>
    </div>
    
</div>

<?php include './views/components/footer.php'; ?>
