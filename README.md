<img src="https://img.shields.io/badge/PHP-8-blue" />&nbsp;<img src="https://img.shields.io/badge/Laravel-10-red" />&nbsp;<img src="https://img.shields.io/badge/Bootstrap-5-blueviolet" />&nbsp;[![Actions Status](https://github.com/SEAD-UFES/sgc/workflows/Integration-Tests/badge.svg)](https://github.com/SEAD-UFES/sgc/actions)

# SGC

![United States](https://raw.githubusercontent.com/stevenrskelton/flag-icon/master/png/16/country-4x3/us.png "United States") - English
## Sead/Ufes Employee Management System Repository

The SGC (initialism for 'Sistema de Gestão de Colaboradores') aims to enhance work in the activities of the Academic Secretariat, Grant Assistant, Course Coordinators and the Virtual Learning Environments Team.

The final product must provide:
- [x] Management of employees documents and filled out forms, both in digital format;
- [ ] Optimized destination of the funding received for courses;
- [ ] Organized allocation of employees in the Virtual Learning Environment.

## Quick start

### 0. Requirements
To run this software you will need to install Docker. See https://docs.docker.com/engine/install/ for more information about installation on you operational system.<br />
Note: Depending on your Linux OS distro, maybe you'll need to prefix docker commands with `sudo`.

### 1. Create the network
$ `docker network create --driver bridge sgc-net`

### 2. Run the database
$ `docker run -e MYSQL_ROOT_PASSWORD=qwerty -e MYSQL_DATABASE=sgc -e MYSQL_USER=sgc -e MYSQL_PASSWORD=password -p 3306:3306 --name=mysql --hostname=mysql --restart unless-stopped --network sgc-net -d mysql:latest`

### 3. Run the application
$ `docker run -p 80:8080 --name=sgc --hostname=sgc --restart unless-stopped --network sgc-net -d seadufes/sgc:latest`

### 4. Access SGC container shell
$ `docker exec -it sgc ash`

### 5. Disable the system https enforce to run without a certificate
$`./disableHttpsRequirement.sh`

### 6. Build the database (assuming that your DB wasn't built before)
Optional:  If you want fake data on database to test the system, run $`./enableFakeData.sh`

$`php artisan migrate --seed`

### 7. Access the system
Browse http://localhost and login with `admin@ufes.br`, password `changeme`<br />
Suggestion: On a production system, register a new administrator account and disable admin[]()@ufes.br

And that's it! ✨
<hr \>

![Brazil](https://raw.githubusercontent.com/stevenrskelton/flag-icon/master/png/16/country-4x3/br.png "Brazil") - Português
## Repositório do Sistema de Gestão de Colaboradores da Sead/Ufes

O SGC tem o objetivo de potencializar o trabalho nas atividades da Secretaria Acadêmica, Assistente de Concessão, Coordenadores de Cursos e Equipe de Ambientes Virtuais de Aprendizagem.

O produto final deve proporcionar:
- [x] Gerenciamento dos documentos dos colaboradores e dos formulários preenchidos, ambos em formato digital;
- [ ] Destinação otimizada do fomento recebido para os cursos;
- [ ] Alocação organizada dos colaboradores no Ambiente Virtual de Aprendizagem.

## Início Rápido

### 0. Requisitos
Para executar este software, será necessário instalar o Docker. Consulte https://docs.docker.com/engine/install/ para obter mais informações sobre a instalação no seu sistema operacional.<br />
Observação: Dependendo da distribuição do seu sistema operacional Linux, talvez seja necessário prefixar os comandos do Docker com `sudo`.

### 1. Criar a rede
$ `docker network create --driver bridge sgc-net`

### 2. Iniciar o banco de dados
$ `docker run -e MYSQL_ROOT_PASSWORD=qwerty -e MYSQL_DATABASE=sgc -e MYSQL_USER=sgc -e MYSQL_PASSWORD=password -p 3306:3306 --name=mysql --hostname=mysql --restart unless-stopped --network sgc-net -d mysql:latest`

### 3. Iniciar a aplicação
$ `docker run -p 80:8080 --name=sgc --hostname=sgc --restart unless-stopped --network sgc-net -d seadufes/sgc:latest`

### 4. Acessar o shell do contêiner SGC
$ `docker exec -it sgc ash`

### 5. Desabilitar a aplicação estrita de HTTPS para executar sem um certificado
$ `./disableHttpsRequirement.sh`

### 6. Construir o banco de dados (assumindo que o seu banco de dados não foi construído anteriormente)
Opcional: Se desejar dados fictícios no banco de dados para testar o sistema, execute $ `./enableFakeData.sh`

$ `php artisan migrate --seed`

### 7. Acessar o sistema
Acesse http://localhost e faça login com `admin@ufes.br`, senha `changeme`<br />
Sugestão: Em um sistema de produção, registre uma nova conta de administrador e desative admin[]()@ufes.br

E é isso! ✨
<hr \>

## Image 💿

Registry: https://hub.docker.com/r/seadufes/sgc
