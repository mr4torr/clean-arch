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


# Structure

```
./bin: files that facilitate the project, access the Docker container, restart services, etc...
./config: Hyperf framework configuration files
./doc
  ./rest: endpoint documentation files
./migrations:
./Auth: authentication component migration files
./src:
  ./Core: Here are the files that are most closely related to the framework
    ./Application
    ./Domain
    ./Infrastructure
  ./Module: where the project resources are located
    ./Auth: Authentication and authorization component
      ./Application
      ./Domain
      ./Infrastructure
      ./Presentation
    ./User: User component, basic data such as address, etc.
      ./Application
      ./Domain
      ./Infrastructure
      ./Presentation
  ./Shared: Shared resources
    ./Context: Application context
    ./Exception: Application exceptions
    ./Http: Requests and responses
    ./Support: Generic classes
./test: test files (developing...)
```

# Authentication

Authentication is done using the jwt token, the token must be sent in the `Authorization` header with the value `Bearer <token>`.

## Authentication token validation

The application uses Nginx API Gateway, so no middleware is needed to validate the token.

Nginx makes a call to `/auth/authenticate` where the token is validated, this endpoint is stored in the cache for 1 minute to avoid overloading multiple requests.

# Endpoint documentation

The available endpoints are mapped in the `*.http` files located in `./doc/rest`