# 📑 Documentação Técnica e Decisões de Projeto:

## Tecnologias e Ferramentas Utilizadas:
O projeto foi construído sobre o ecossistema Laravel 13. Para garantir que a aplicação funcione em qualquer ambiente sem conflitos de versão, utilizou-se o Laravel Sail, uma interface de linha de comando para o Docker. Isso permite que o servidor web, o banco de dados e as dependências fiquem isolados em containers.

Como banco de dados, usou-se pelo PostgreSQL, no frontend, o Tailwind CSS foi a ferramenta chave para a construção de uma interface limpa e responsiva, permitindo o desenvolvimento rápido de componentes visuais complexos, como os cards de resumo e a tabela dinâmica. Para a integração com o Spotify, utilizou-se o Laravel Socialite, que padroniza o fluxo de autenticação OAuth2, garantindo a proteção dos dados e tokens dos usuários

## Arquitetura e Estrutura de Código:
A arquitetura do projeto foi desenhada seguindo o Action Pattern e o Service Layer, visando desacoplar a lógica de negócio dos controladores.

Controllers enxutos: O SpotifyController não processa dados. Ele atua como um maestro, recebendo o retorno do Spotify e delegando as tarefas para as classes especialistas (Actions).

Actions Especialistas: Foram criadas duas classes, são elas GetSpotifyUserTracksAction e ImportSpotifyTracksAction. A primeira é responsável exclusivamente pela comunicação HTTP com a API do Spotify, tratando tokens e requisições. A segunda foca na regra de negócio de persistência, garantindo que as músicas sejam salvas corretamente no banco de dados.

Persistência Inteligente: No momento da importação, o código utiliza o método updateOrCreate. Isso evita que o histórico do usuário fique com registros duplicados, pois o sistema verifica se o spotify_id já existe antes de criar uma nova linha, mantendo a integridade dos dados mesmo com múltiplas sincronizações.

## Diferenciais e Experiência do Usuário:
Filtros de Busca Dinâmicos: Implementou-se uma busca por nome da música ou artista diretamente na query do banco de dados, utilizando filtros insensíveis a maiúsculas e minúsculas para facilitar a navegação.

Paginação de Dados: Para evitar lentidão em contas com histórico extenso, os dados são paginados (10 por página), reduzindo a carga no servidor e melhorando a leitura

Interface de Análise de Dados: Antes da tabela principal, o usuário visualiza cards com estatísticas em tempo real, como o total de músicas sincronizadas e o artista mais ouvido

## Testes Automatizados:
Para garantir que a aplicação continue funcionando após novas atualizações, o projeto inclui uma suite de testes que valida desde a segurança das rotas até a precisão dos filtros de busca.Foram mantidos os 25 testes nativos da Base Laravel Breeze e foram desenvolvidos 3 testes personalizados
Para executar todos os testes, utilize o comando:
./vendor/bin/sail artisan test



# 📑 Documentação do Mecanismo de Integração com a API do Spotify:

## Arquivos Responsáveis pela Integração:
### 1. app/Actions/GetSpotifyUserTracksAction.php:
Este é o "mensageiro" da aplicação. Sua função exclusiva é realizar a requisição HTTP para o endpoint de músicas recentes do Spotify.
- O que ele faz: Ele recebe o Token de acesso do usuário, configura o cabeçalho de autorização e consome o endpoint v1/me/player/recently-played?limit=50.
- Tratamento de Dados: Ele não salva nada no banco; sua tarefa é apenas garantir que o JSON retornado pelo Spotify seja transformado em um array PHP limpo e pronto para ser processado.

### 2. app/Actions/ImportSpotifyTracksAction.php:
Este é o "tradutor e arquivista". Ele recebe os dados brutos vindos da Action anterior e os prepara para o banco de dados.
- O que ele faz: Percorre cada música retornada, extrai o nome da faixa, o nome do artista principal e o timestamp de quando a música foi ouvida.
- Lógica de Persistência: É aqui que reside a inteligência do updateOrCreate, que utiliza o spotify_id para decidir se deve criar um novo registro ou apenas atualizar um existente, evitando duplicidade.

### 3. app/Http/Controllers/SpotifyController.php:
Este atua como o "coordenador" do fluxo OAuth2.
- O que ele faz: Utiliza o Laravel Socialite para gerenciar o redirecionamento para o login do Spotify e o recebimento do Token de acesso no método callback. Ele instancia as duas Actions mencionadas acima e organiza a ordem de execução: primeiro busca os dados, depois ordena a importação e, por fim, redireciona o usuário para o Dashboard. 