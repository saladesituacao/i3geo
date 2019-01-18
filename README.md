# i3GEO/SES-DF

Os mapas interativos utilizados no portal da sala de situação da SES/DF são criados com o software livre i3Geo, versão 8.

O código original não foi alterado, permitindo a atualização sem interferência nos mapas da sala de situação.

Guia completo sobre instalação e administração do software i3Geo, veja em: https://softwarepublico.gov.br/gitlab/i3geo/i3geo/wikis/home

Sobre segurança: https://softwarepublico.gov.br/gitlab/i3geo/i3geo/wikis/notas-sobre-seguranca

## Instalação

O ambiente operacional para uso do i3GEO é construído com Docker (https://hub.docker.com/r/i3geo/software-base/) e o código do software fica disponível no repositório Git.

## Para instalar em ambiente Linux

Faça o clone do código existente no repositório Git em uma pasta em seu sistema de arquivos

<pre>
cd ~
mkdir i3geo
cd i3geo
git clone http://git.saude.df.gov.br/gitbucket/git/SUPLANS/i3geo.git .
docker run -it -p 80:8080 -v $PWD:/var/www/i3geo --rm i3geo/software-base
</pre>

Em seguida, acesse um navegador de sua preferência e insira o seguinte endereço:

`http://localhost/i3geo`

Dicas sobre Docker: https://www.digitalocean.com/community/tutorials/como-instalar-e-utilizar-o-docker-primeiros-passos-pt

## Banco de dados

A pasta `i3geo/ses/databaseteste` contém os arquivos Postgres `dump` que permitem criar um banco de dados local para testes.

> Os comandos abaixo podem ser executados com o software Pgadmin.

Em seu database são necessárias as seguintes ROLES:

Crie os seguintes databases:

<pre>
CREATE DATABASE dbsalasituacao
  WITH ENCODING = 'UTF8'
       TABLESPACE = pg_default
       CONNECTION LIMIT = -1;

</pre>

<pre>
CREATE DATABASE stage
  WITH ENCODING = 'UTF8'
       TABLESPACE = pg_default
       CONNECTION LIMIT = -1;
</pre>

No database dbauxiliares, crie o esquema stage:

<pre>
CREATE SCHEMA dbauxiliares;
</pre>

Adicione as funcionalidades do Postgis no database stage:

<pre>
create extension postgis
</pre>

No database dbsalasituacao, crie o esquema geo:

<pre>
CREATE SCHEMA geo;
</pre>

## Configuração de acesso ao banco de dados

O comando `docker run` utiliza uma série de variáveis de ambiente que indicam os parâmetros de conexão com o banco de dados.

<dl>
  <dt>HOST</dt>
  <dd>Endereço IP do servidor de banco de dados</dd>

  <dt>PORT</dt>

  <dt>DBSTAGE</dt>
  <dd>Database que contém os dados utilizados nas camadas e demais aplicativos</dd>

  <dt>DBSALA</dt>
  <dd>Database onde ficam as tabelas administrativas do i3Geo</dd>

  <dt>USERSALA</dt>
  <dd>Usuário com direito de escrita nas tabelas administrativas</dd>

  <dt>PASSWORDSALA</dt>
  <dd>Senha do usuário USERSALA</dd>

  <dt>USERSTICK</dt>
  <dd>Usuário com direito apenas de leitura para DBSTAGE</dd>

  <dt>PASSWORDSTICK</dt>
  <dd>Senha do usuário USERSTICK</dd>

  <dt>USERAPP (opcional)</dt>
  <dd>Usuário com direitos de escrita em DBSTAGE</dd>

  <dt>PASSWORDAPP (opcional)</dt>
  <dd>Senha do usuário USERAPP</dd>
</dl>
