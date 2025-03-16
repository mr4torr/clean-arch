# Introduction

This is a skeleton application using the Hyperf framework. This application is meant to be used as a starting place for those looking to get their feet wet with Hyperf Framework.

# Getting started

Once installed, you can run the server immediately using the command below.

```bash

make dev
make setup
```

Or if in a Docker based environment you can use the `docker-compose.dev.yml` provided by the template:

```bash
make dev
```

This will start the cli-server on port `9501`, and bind it to all network interfaces. You can then visit the site at `http://localhost/` which will bring up Hyperf default home page.


# Estrutura

```
./bin: arquivos facilitadores do projetos, acessar container docker, reiniciar serviços e etc...
./config: arquivos de configuração do framework hyperf
./doc
  ./rest: arquivos de documentação dos endpoints
./migrations:
  ./Auth: arquivos de migrations do componente de autenticação
./src:
  ./Core: Aqui ficam os arquivos que tem mais ligação com o framework
    ./Application
    ./Domain
    ./Infrastructure
  ./Module: onde fica os recursos do projeto
    ./Auth: Componente de autenticação e autorização
      ./Application
      ./Domain
      ./Infrastructure
    ./User: Componente de usuário, dados básicos como endereço e etc
      ./Application
      ./Domain
      ./Infrastructure
  ./Shared: Recursos compartilhados
    ./Context: Contexto da aplicação
    ./Exception: Exceções da aplicação
    ./Http: Requisições e respostas
    ./Support: Classes genéricas
./test: arquivos de testes (desenvolvendo...)
```
