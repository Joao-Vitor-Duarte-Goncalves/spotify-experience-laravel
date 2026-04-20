## Requisitos para Execução:
Para que a aplicação funcione corretamente, certifique-se de cumprir os requisitos abaixo:
* Docker Desktop instalado e em execução.
* WSL2 (caso esteja utilizando Windows).
* Composer instalado localmente


## Como executar o projeto:

### 1. Clonar o Repositório:
```bash
git clone [URL_DO_REPOSITORIO]
cd [NOME_DA_PASTA]
```

### 2. Configurar Variáveis de Ambiente:
 Copie o template de exemplo:
```Bash
cp .env.example .env
```

- É obrigatório criar um aplicativo no (https://developer.spotify.com/dashboard) para obter as chaves de acesso.

- Abra o ficheiro .env e configure as suas credenciais do "Spotify Developer Dashboard":

```bash
SPOTIFY_CLIENT_ID=seu_client_id
SPOTIFY_CLIENT_SECRET=seu_client_secret
SPOTIFY_REDIRECT_URI=http://127.0.0.1:8000/callback
```

### 3. Instala as dependências do PHP:
```Bash
composer install
```

### 4. Iniciar Containers (Docker):
```Bash
./vendor/bin/sail up -d
```

### 5. Instalar Dependências e Configurar a Chave

Execute os comandos na ordem abaixo:

Gera a chave única de criptografia da aplicação
```bash
./vendor/bin/sail artisan key:generate
```

Cria as tabelas na base de dados
```bahs
./vendor/bin/sail artisan migrate
```

Instala e compila os assets do frontend (Tailwind/Vite)
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### 6. Aceder à Aplicação

- Abra o navegador em: http://localhost:8000# desafio-spotify-laravel
