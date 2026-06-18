# Sistema de Gerenciamento - Terapia Capilar

README organizado em formato inspirado no padrao ISO/IEC/IEEE 29148:2018, com foco em identificacao do produto, escopo, requisitos, ambiente, instalacao e operacao.

## 1. Identificacao do Projeto

- Nome do sistema: Sistema de Gerenciamento - Terapia Capilar
- Tipo: aplicacao web para uso interno
- Dominio: gestao de clientes, produtos, servicos e historico de atendimentos
- Usuaria principal: terapeuta capilar/cabeleireira responsavel pelo salao

## 2. Objetivo

Este projeto foi desenvolvido para substituir controles manuais feitos em cadernos e agendas fisicas por um sistema web simples, centralizado e de facil consulta.

O sistema apoia a organizacao de:

- clientes
- produtos
- servicos
- historico simples de atendimentos

## 3. Visao Geral do Produto

O sistema e dividido em tres modulos principais:

- `Clientes`: cadastro, edicao, exclusao, visualizacao e historico de atendimentos
- `Produtos`: cadastro, edicao, exclusao, visualizacao e consulta de estoque basico
- `Servicos`: cadastro, edicao, exclusao, visualizacao e controle de valor base

Tambem existem componentes compartilhados para:

- conexao com banco PostgreSQL
- cabecalho, rodape e menu lateral
- identidade visual centralizada em CSS

## 4. Publico-Alvo e Contexto de Uso

O sistema foi pensado para uso individual da profissional responsavel pelos atendimentos. O foco e permitir consulta rapida dos dados e registro manual da evolucao das clientes durante os servicos realizados.

## 5. Escopo

### Incluido nesta versao

- cadastro de clientes
- cadastro de produtos
- cadastro de servicos
- pesquisa de clientes
- pesquisa de produtos por nome ou marca
- edicao e exclusao de registros
- observacoes por cliente
- historico simples de atendimentos vinculado a cliente
- vinculo opcional do historico com um servico

### Fora do escopo nesta versao

- controle financeiro
- agenda online
- integracao com WhatsApp
- relatorios avancados
- controle avancado de estoque
- sistema multiusuario em producao
- envio automatico de mensagens

## 6. Funcionalidades Implementadas

- tela inicial com navegacao entre modulos
- CRUD completo de clientes
- CRUD completo de produtos
- CRUD completo de servicos
- registro de historico por cliente
- validacao de dados no servidor
- listagens com acoes de visualizar, editar e excluir
- layout compartilhado entre as telas
- favicon e identidade visual proprios

## 7. Requisitos de Ambiente

Para executar o projeto localmente, recomenda-se:

- PHP 8.0 ou superior
- PostgreSQL instalado e acessivel
- `psql` e `pg_dump` disponiveis no terminal
- navegador moderno

Observacao:

- o projeto usa `str_contains`, `PDO` e `strict_types`, entao versoes antigas do PHP nao sao recomendadas

## 8. Instalacao e Configuracao

### Passo 1. Baixar o projeto

```bash
git clone https://github.com/igbelt-sys/SistemaCadastro-salao.git
cd SistemaCadastro-salao
```

### Passo 2. Criar o banco

```powershell
$env:PGPASSWORD='<sua_senha_do_postgres>'
createdb -h 127.0.0.1 -p 5432 -U postgres sistemasalao
```

Se o banco `sistemasalao` ja existir, pule este passo.

### Passo 3. Importar o banco

Se quiser apenas a estrutura:

```powershell
$env:PGPASSWORD='<sua_senha_do_postgres>'
psql -h 127.0.0.1 -p 5432 -U postgres -d sistemasalao -f .\sql\banco.sql
```

Se quiser estrutura + dados de exemplo:

```powershell
$env:PGPASSWORD='<sua_senha_do_postgres>'
psql -h 127.0.0.1 -p 5432 -U postgres -d sistemasalao -f .\sql\sistemasalao_dump.sql
```

### Passo 4. Conferir a conexao

O projeto usa as configuracoes de [config/conexao.php](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/config/conexao.php:1):

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

Se precisar, a senha tambem pode ser passada por variavel de ambiente:

- `DB_PASS`

### Passo 5. Rodar o projeto

```powershell
php -S 127.0.0.1:8000
```

Abra no navegador:

```text
http://127.0.0.1:8000
```

### Aviso

Nao publique a senha real do banco no `README` nem no repositorio.

## 9. Banco de Dados

### Arquivos disponiveis

- [sql/banco.sql](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/sql/banco.sql:1): estrutura base do banco
- [sql/sistemasalao_dump.sql](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/sql/sistemasalao_dump.sql:1): dump completo com estrutura e dados atuais
- [assets/Documentação/DER -SistemaCadastro.pdf](</c:/Users/2DevSESI/Documents/SistemaCadastro-salao/assets/Documentação/DER -SistemaCadastro.pdf>): diagrama da estrutura do banco

### Entidades principais

- `usuarios`
- `clientes`
- `produtos`
- `servicos`
- `historico_clientes`

### Regras de modelagem ja implementadas

- chave primaria em todas as entidades principais
- vinculo de historico com cliente por chave estrangeira
- vinculo opcional de historico com servico
- exclusao em cascata do historico quando a cliente e removida
- remocao do vinculo com servico quando o servico e excluido
- indice unico para telefone normalizado de clientes
- gatilhos para atualizacao automatica de `atualizado_em` no dump atual

## 10. Estrutura do Projeto

```text
SistemaCadastro-salao/
|-- Clientes/
|-- Produtos/
|-- Servicos/
|-- assets/
|   |-- css/
|   |-- imagens/
|   `-- Documentacao/
|-- config/
|-- includes/
|-- sql/
|-- index.php
`-- README.md
```

### Organizacao por pasta

- `Clientes/`: telas e funcoes do modulo de clientes
- `Produtos/`: telas e funcoes do modulo de produtos
- `Servicos/`: telas e funcoes do modulo de servicos
- `config/`: conexao com banco e configuracao base
- `includes/`: layout compartilhado entre paginas
- `assets/`: CSS, imagens, logo, favicon e documentacao
- `sql/`: scripts de banco e dump

## 11. Requisitos Funcionais

- RF01: o sistema deve permitir cadastrar clientes
- RF02: o sistema deve permitir listar os clientes cadastrados
- RF03: o sistema deve permitir editar os dados de um cliente
- RF04: o sistema deve permitir excluir clientes
- RF05: o sistema deve permitir pesquisar clientes pelo nome
- RF06: o sistema deve permitir adicionar observacoes no cadastro do cliente
- RF07: o sistema deve permitir registrar historico simples de sessoes no perfil do cliente
- RF08: o sistema deve permitir cadastrar produtos
- RF09: o sistema deve permitir listar, editar e excluir produtos
- RF10: o sistema deve permitir cadastrar servicos
- RF11: o sistema deve permitir listar, editar e excluir servicos

## 12. Requisitos Nao Funcionais

- RNF01: o sistema deve possuir interface simples e intuitiva
- RNF02: o sistema deve ser desenvolvido para ambiente web
- RNF03: o sistema deve armazenar os dados em banco de dados relacional
- RNF04: o sistema deve apresentar tempo de resposta adequado nas consultas e cadastros
- RNF05: o sistema deve possuir organizacao visual limpa e de facil navegacao
- RNF06: o sistema deve garantir integridade das informacoes cadastradas
- RNF07: o sistema deve ser desenvolvido com foco em facilidade de manutencao
- RNF08: o sistema deve permitir utilizacao em computadores com acesso a internet
- RNF09: o sistema deve possuir compatibilidade com navegadores modernos

## 13. Regras de Negocio

- RN01: cada cliente deve possuir pelo menos um nome cadastrado
- RN02: clientes podem possuir observacoes e informacoes sobre caracteristicas capilares
- RN03: o historico de sessoes e cadastrado manualmente pela profissional
- RN04: cada registro de historico deve estar vinculado a uma cliente
- RN05: produtos devem possuir nome para serem cadastrados
- RN06: servicos devem possuir nome e valor base cadastrados
- RN07: o sistema deve permitir edicao e exclusao de registros cadastrados
- RN08: o sistema deve armazenar as informacoes de clientes, produtos e servicos de forma organizada
- RN09: o historico pode ser vinculado opcionalmente a um servico

## 14. Situacao Atual do Projeto

Atualmente, o sistema ja entrega a navegacao principal e os tres modulos centrais com operacoes de cadastro e manutencao.

Pontos importantes sobre o estado atual:

- existe tabela `usuarios` no banco, mas o fluxo completo de autenticacao ainda nao esta implementado na interface
- o dump atual contem campos e gatilhos mais recentes do que o script inicial `sql/banco.sql`
- a documentacao visual do banco ja esta disponivel em PDF

## 15. Prototipo e Referencias

- Figma: [Prototipo de Media](https://www.figma.com/design/i3cWGxCxi4FK2zsetiAnjg/prototipo-sistema-salao?node-id=2-2&p=f&t=PC5uKsXVbdoyd6ko-0)
- Referencia do padrao: [ISO/IEC/IEEE 29148:2018](https://www.iso.org/standard/72089.html)

## 16. Observacao sobre o Padrao

Este README nao substitui a norma oficial nem declara conformidade formal com ela. A organizacao do conteudo foi inspirada na estrutura de documentacao de requisitos e produto descrita pelo padrao ISO/IEC/IEEE 29148:2018, adaptada para a realidade academica e pratica deste projeto.
