# Sistema de Gerenciamento - Terapia Capilar

README organizado em formato inspirado no padrão ISO/IEC/IEEE 29148:2018, com foco em identificação do produto, escopo, requisitos, ambiente, instalação e operação.

## 1. Identificação do Projeto

- Nome do sistema: Sistema de Gerenciamento - Terapia Capilar
- Tipo: aplicação web para uso interno
- Domínio: gestão de clientes, produtos, serviços e histórico de atendimentos
- Usuária principal: terapeuta capilar/cabeleireira responsável pelo salão

## 2. Objetivo

Este projeto foi desenvolvido para substituir controles manuais feitos em cadernos e agendas físicas por um sistema web simples, centralizado e de fácil consulta.

O sistema apoia a organização de:

- clientes
- produtos
- serviços
- histórico simples de atendimentos

## 3. Visão Geral do Produto

O sistema é dividido em três módulos principais:

- `Clientes`: cadastro, edição, exclusão, visualização e histórico de atendimentos
- `Produtos`: cadastro, edição, exclusão, visualização e consulta de estoque básico
- `Serviços`: cadastro, edição, exclusão, visualização e controle de valor base

Também existem componentes compartilhados para:

- conexão com banco PostgreSQL
- cabeçalho, rodapé e menu lateral
- identidade visual centralizada em CSS

## 4. Resumo Rápido

Se quiser entender o projeto bem rápido, ele funciona assim:

- a tela inicial leva para clientes, produtos e serviços
- no módulo de clientes é possível cadastrar, editar, excluir e ver histórico
- no módulo de produtos é possível controlar nome, marca, descrição e quantidade
- no módulo de serviços é possível cadastrar o nome, a descrição e o valor base
- o sistema salva tudo no PostgreSQL

## 5. Público-Alvo e Contexto de Uso

O sistema foi pensado para uso individual da profissional responsável pelos atendimentos. O foco é permitir consulta rápida dos dados e registro manual da evolução das clientes durante os serviços realizados.

## 6. Escopo

### Incluído nesta versão

- cadastro de clientes
- cadastro de produtos
- cadastro de serviços
- pesquisa de clientes
- pesquisa de produtos por nome ou marca
- edição e exclusão de registros
- observações por cliente
- histórico simples de atendimentos vinculado a cliente
- vínculo opcional do histórico com um serviço

### Fora do escopo nesta versão

- controle financeiro
- agenda online
- integração com WhatsApp
- relatórios avançados
- controle avançado de estoque
- sistema multiusuário em produção
- envio automático de mensagens

## 7. Funcionalidades Implementadas

- tela inicial com navegação entre módulos
- CRUD completo de clientes
- CRUD completo de produtos
- CRUD completo de serviços
- registro de histórico por cliente
- validação de dados no servidor
- listagens com ações de visualizar, editar e excluir
- layout compartilhado entre as telas
- favicon e identidade visual próprios

## 8. Requisitos de Ambiente

Para executar o projeto localmente, recomenda-se:

- PHP 8.0 ou superior
- PostgreSQL instalado e acessível
- `pgadmin` ou `psql`
- navegador moderno

Observação:

- o projeto usa `str_contains` e `PDO`, então versões antigas do PHP não são recomendadas

## 9. Instalação e Configuração

### Passo 1. Baixar o projeto

```bash
git clone https://github.com/igbelt-sys/SistemaCadastro-salao.git
cd SistemaCadastro-salao
```

### Passo 2. Banco de dados

Os arquivos do banco estão em:

- `sql/banco.sql`
- `sql/sistemasalao_dump.sql`

Importação mais simples pelo terminal:

```powershell
psql -U postgres -c "CREATE DATABASE sistemasalao"
psql -U postgres -d sistemasalao -f .\sql\sistemasalao_dump.sql
```

Se quiser importar só a estrutura do banco:

```powershell
psql -U postgres -d sistemasalao -f .\sql\banco.sql
```

Se o banco `sistemasalao` já existir, pode pular a primeira linha.

Se preferir fazer pelo `pgAdmin`:

1. crie o banco com o nome `sistemasalao`
2. abra o `query tool`
3. rode `sql/banco.sql` ou `sql/sistemasalao_dump.sql`

### Passo 3. Conferir a conexão

O projeto usa as configurações de [config/conexao.php](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/config/conexao.php:1):

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

Para rodar localmente sem dificuldade, deixe esses dados iguais aos do seu PostgreSQL.

Exemplo comum:

- host `127.0.0.1`
- porta `5432`
- banco `sistemasalao`
- usuário `postgres`
- senha `postgres`

Se no seu computador a senha do PostgreSQL for outra, troque somente o valor de `DB_PASS`.

### Passo 4. Rodar o projeto

```powershell
php -S localhost:8000
```

Abra no navegador:

```text
http://localhost:8000
```

### Passo 5. Testar o sistema

Depois que abrir no navegador, você pode testar assim:

1. entrar em `clientes` e cadastrar uma cliente
2. entrar em `produtos` e cadastrar um produto
3. entrar em `serviços` e cadastrar um serviço
4. voltar em `clientes` e adicionar um histórico

### Se der erro de conexão

Confira estes pontos:

- o PostgreSQL está ligado
- o banco `sistemasalao` foi criado
- o script SQL foi importado
- usuário e senha em `config/conexao.php` estão certos

## 10. Banco de Dados

### Arquivos disponíveis

- [sql/banco.sql](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/sql/banco.sql:1): estrutura base do banco
- [sql/sistemasalao_dump.sql](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/sql/sistemasalao_dump.sql:1): dump completo com estrutura e dados atuais
- [assets/Documentação/DER -SistemaCadastro.pdf](</c:/Users/2DevSESI/Documents/SistemaCadastro-salao/assets/Documentação/DER -SistemaCadastro.pdf>): diagrama da estrutura do banco

### Entidades principais

- `usuarios`
- `clientes`
- `produtos`
- `servicos`
- `historico_clientes`

### Regras de modelagem já implementadas

- chave primária em todas as entidades principais
- vínculo de histórico com cliente por chave estrangeira
- vínculo opcional de histórico com serviço
- exclusão em cascata do histórico quando a cliente é removida
- remoção do vínculo com serviço quando o serviço é excluído
- índice único para telefone normalizado de clientes
- gatilhos para atualização automática de `atualizado_em` no dump atual

## 11. Estrutura do Projeto

```text
SistemaCadastro-salao/
|-- Clientes/
|-- Produtos/
|-- Servicos/
|-- assets/
|   |-- css/
|   |-- imagens/
|   `-- Documentação/
|-- config/
|-- includes/
|-- sql/
|-- index.php
`-- README.md
```

### Organização por pasta

- `Clientes/`: telas e funções do módulo de clientes
- `Produtos/`: telas e funções do módulo de produtos
- `Servicos/`: telas e funções do módulo de serviços
- `config/`: conexão com banco e configuração base
- `includes/`: layout compartilhado entre páginas
- `assets/`: CSS, imagens, logo, favicon e documentação
- `sql/`: scripts de banco e dump

## 12. Requisitos Funcionais

- RF01: o sistema deve permitir cadastrar clientes
- RF02: o sistema deve permitir listar os clientes cadastrados
- RF03: o sistema deve permitir editar os dados de um cliente
- RF04: o sistema deve permitir excluir clientes
- RF05: o sistema deve permitir pesquisar clientes pelo nome
- RF06: o sistema deve permitir adicionar observações no cadastro do cliente
- RF07: o sistema deve permitir registrar histórico simples de sessões no perfil do cliente
- RF08: o sistema deve permitir cadastrar produtos
- RF09: o sistema deve permitir listar, editar e excluir produtos
- RF10: o sistema deve permitir cadastrar serviços
- RF11: o sistema deve permitir listar, editar e excluir serviços

## 13. Requisitos Não Funcionais

- RNF01: o sistema deve possuir interface simples e intuitiva
- RNF02: o sistema deve ser desenvolvido para ambiente web
- RNF03: o sistema deve armazenar os dados em banco de dados relacional
- RNF04: o sistema deve apresentar tempo de resposta adequado nas consultas e cadastros
- RNF05: o sistema deve possuir organização visual limpa e de fácil navegação
- RNF06: o sistema deve garantir integridade das informações cadastradas
- RNF07: o sistema deve ser desenvolvido com foco em facilidade de manutenção
- RNF08: o sistema deve permitir utilização em computadores com acesso à internet
- RNF09: o sistema deve possuir compatibilidade com navegadores modernos

## 14. Regras de Negócio

- RN01: cada cliente deve possuir pelo menos um nome cadastrado
- RN02: clientes podem possuir observações e informações sobre características capilares
- RN03: o histórico de sessões é cadastrado manualmente pela profissional
- RN04: cada registro de histórico deve estar vinculado a uma cliente
- RN05: produtos devem possuir nome para serem cadastrados
- RN06: serviços devem possuir nome e valor base cadastrados
- RN07: o sistema deve permitir edição e exclusão de registros cadastrados
- RN08: o sistema deve armazenar as informações de clientes, produtos e serviços de forma organizada
- RN09: o histórico pode ser vinculado opcionalmente a um serviço

## 15. Situação Atual do Projeto

Atualmente, o sistema já entrega a navegação principal e os três módulos centrais com operações de cadastro e manutenção.

Pontos importantes sobre o estado atual:

- existe tabela `usuarios` no banco, mas o fluxo completo de autenticação ainda não está implementado na interface
- o dump atual contém campos e gatilhos mais recentes do que o script inicial `sql/banco.sql`
- a documentação visual do banco já está disponível em PDF

## 16. Fluxo Básico de Uso

O uso mais comum do sistema segue esta ordem:

1. cadastrar a cliente
2. cadastrar os serviços usados no salão
3. cadastrar os produtos usados no atendimento
4. abrir a cliente cadastrada
5. adicionar o histórico do atendimento

## 17. Protótipo e Referências

- Figma: [Protótipo de Média](https://www.figma.com/design/i3cWGxCxi4FK2zsetiAnjg/prototipo-sistema-salao?node-id=2-2&p=f&t=PC5uKsXVbdoyd6ko-0)
- Referência do padrão: [ISO/IEC/IEEE 29148:2018](https://www.iso.org/standard/72089.html)

---