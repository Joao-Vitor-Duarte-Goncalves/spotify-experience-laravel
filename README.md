🎧 Desafio Spotify Laravel
📌 Requisitos para Execução

Para que a aplicação funcione corretamente, certifique-se de cumprir os requisitos abaixo:

Docker Desktop instalado e em execução
WSL2 (caso esteja utilizando Windows)
Composer instalado localmente
🚀 Como executar o projeto
1. Clonar o repositório
git clone [URL_DO_REPOSITORIO]
cd [NOME_DA_PASTA]
2. Configurar variáveis de ambiente

Copie o template de exemplo:

cp .env.example .env

Crie um aplicativo no Spotify Developer Dashboard:
https://developer.spotify.com/dashboard

Depois, abra o arquivo .env e configure suas credenciais:

SPOTIFY_CLIENT_ID=seu_client_id
SPOTIFY_CLIENT_SECRET=seu_client_secret
SPOTIFY_REDIRECT_URI=http://127.0.0.1:8000/callback
3. Instalar dependências do PHP
composer install
4. Iniciar containers (Docker)
./vendor/bin/sail up -d
5. Instalar dependências e configurar aplicação

Execute os comandos abaixo na ordem:

# Gerar chave da aplicação
./vendor/bin/sail artisan key:generate

# Criar tabelas no banco de dados
./vendor/bin/sail artisan migrate

# Instalar dependências do frontend
./vendor/bin/sail npm install

# Rodar o frontend
./vendor/bin/sail npm run dev
6. Acessar a aplicação

Abra no navegador:

http://localhost:8000