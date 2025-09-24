-----

# API CRUD em PHP

Este projeto é uma **API RESTful** simples para gerenciamento de usuários, construída em **PHP puro** seguindo o padrão **MVC**.

## Funcionalidades

  * **CRUD** de usuários (**C**reate, **R**ead, **U**pdate, **D**elete)
  * Respostas em **JSON**
  * Estrutura modular (**Controllers**, **Models**, **Views**)
  * Conexão com **MySQL** via **PDO**

## Estrutura de Pastas

```
/app
├── /Controllers            # Lógica dos endpoints (UserController)
├── /Core                   # Núcleo do sistema (Router)
├── /Models                 # Acesso ao banco de dados (UserModel)
└── /Views                  # Renderização das respostas (JsonView)
/config                     # Configuração do banco de dados
/public                     # Ponto de entrada da aplicação (index.php, .htaccess)
```

## Instalação

1.  **Clone o repositório:**

    ```sh
    git clone https://github.com/seu-usuario/php-api.git
    ```

2.  **Configure o banco de dados:**

      * Crie um banco MySQL chamado `crud_api`.
      * Crie a tabela `users` com o seguinte comando SQL:
        ```sql
        CREATE TABLE users (
          id INT AUTO_INCREMENT PRIMARY KEY,
          username VARCHAR(50) NOT NULL,
          email VARCHAR(320) NOT NULL UNIQUE,
          pass_hash VARCHAR(255) NOT NULL,
          phone VARCHAR(16)
        );
        ```
      * Ajuste as credenciais em `Database.php` conforme necessário.

3.  **Configure o Apache:**

      * Aponte o `DocumentRoot` para a pasta `/public`.
      * Certifique-se de que o módulo `mod_rewrite` está habilitado.

## Uso

A API responde a requisições HTTP nas seguintes rotas:

  * `GET /users` — Lista todos os usuários
  * `GET /users/{id}` — Detalhes de um usuário
  * `POST /users` — Cria um novo usuário
  * `PUT /users/{id}` — Atualiza um usuário
  * `DELETE /users/{id}` — Remove um usuário

### Exemplo de requisição para criar um usuário

```sh
curl -X POST http://localhost/users \
  -H "Content-Type: application/json" \
  -d '{"username":"joao","email":"joao@email.com","pass":"senha123","phone":"11999999999"}'
```

## Licença

**MIT**. Veja o arquivo `LICENSE` para mais detalhes.
