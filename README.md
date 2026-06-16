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
- `Servicos`: cadastro, edição, exclusão, visualização e controle de valor base

Também existem componentes compartilhados para:

- conexão com banco PostgreSQL
- cabeçalho, rodapé e menu lateral
- identidade visual centralizada em CSS

## 4. Público-Alvo e Contexto de Uso

O sistema foi pensado para uso individual da profissional responsável pelos atendimentos. O foco é permitir consulta rápida dos dados e registro manual da evolução das clientes durante os serviços realizados.

## 5. Escopo

### Incluído nesta versão

- cadastro de clientes
- cadastro de produtos
- cadastro de serviços
- pesquisa de clientes
- pesquisa de produtos por nome ou marca
- edição e exclusão de registros
- observações por cliente
- histórico simples de atendimentos vinculado à cliente
- vínculo opcional do histórico com um serviço

### Fora do escopo nesta versão

- controle financeiro
- agenda online
- integração com WhatsApp
- relatórios avançados
- controle avançado de estoque
- sistema multiusuário em produção
- envio automático de mensagens

## 6. Funcionalidades Implementadas

- tela inicial com navegação entre módulos
- CRUD completo de clientes
- CRUD completo de produtos
- CRUD completo de serviços
- registro de histórico por cliente
- validação de dados no servidor
- listagens com ações de visualizar, editar e excluir
- layout compartilhado entre as telas
- favicon e identidade visual próprios

## 7. Requisitos de Ambiente

Para executar o projeto localmente, recomenda-se:

- PHP 8.0 ou superior
- PostgreSQL instalado e acessível
- `psql` e `pg_dump` disponíveis no terminal
- navegador moderno

Observação:

- o projeto usa `str_contains`, `PDO` e `strict_types`, então versões antigas do PHP não são recomendadas

## 8. Instalação e Configuração

### 8.1. Clonar o repositório

```bash
git clone https://github.com/igbelt-sys/SistemaCadastro-salao.git
cd SistemaCadastro-salao
```

### 8.2. Criar o banco de dados

Exemplo com PostgreSQL:

```powershell
$env:PGPASSWORD='postgres'
createdb -h 127.0.0.1 -p 5432 -U postgres sistemasalao
```

Se o comando `createdb` não estiver disponível, o banco pode ser criado manualmente no pgAdmin ou no próprio PostgreSQL.

### 8.3. Importar a estrutura ou o dump

Opção 1. Importar apenas a estrutura inicial:

```powershell
$env:PGPASSWORD='postgres'
psql -h 127.0.0.1 -p 5432 -U postgres -d sistemasalao -f .\sql\banco.sql
```

Opção 2. Importar a estrutura e os dados atuais do projeto:

```powershell
$env:PGPASSWORD='postgres'
psql -h 127.0.0.1 -p 5432 -U postgres -d sistemasalao -f .\sql\sistemasalao_dump.sql
```

### 8.4. Configurar a conexão

As configurações padrão estão em [config/conexao.php](/c:/Users/2DevSESI/Documents/SistemaCadastro-salao/config/conexao.php:1):

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

O projeto também aceita sobrescrever a senha por variável de ambiente:

- `DB_PASS`

### 8.5. Executar o projeto

Uma forma simples de rodar localmente é usar o servidor embutido do PHP:

```powershell
php -S 127.0.0.1:8000
```

Depois, abra no navegador:

```text
http://127.0.0.1:8000
```

## 9. Banco de Dados

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

## 10. Estrutura do Projeto

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

## 11. Requisitos Funcionais

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

## 12. Requisitos Não Funcionais

- RNF01: o sistema deve possuir interface simples e intuitiva
- RNF02: o sistema deve ser desenvolvido para ambiente web
- RNF03: o sistema deve armazenar os dados em banco de dados relacional
- RNF04: o sistema deve apresentar tempo de resposta adequado nas consultas e cadastros
- RNF05: o sistema deve possuir organização visual limpa e de fácil navegação
- RNF06: o sistema deve garantir integridade das informações cadastradas
- RNF07: o sistema deve ser desenvolvido com foco em facilidade de manutenção
- RNF08: o sistema deve permitir utilização em computadores com acesso à internet
- RNF09: o sistema deve possuir compatibilidade com navegadores modernos

## 13. Regras de Negócio

- RN01: cada cliente deve possuir pelo menos um nome cadastrado
- RN02: clientes podem possuir observações e informações sobre características capilares
- RN03: o histórico de sessões é cadastrado manualmente pela profissional
- RN04: cada registro de histórico deve estar vinculado a uma cliente
- RN05: produtos devem possuir nome para serem cadastrados
- RN06: serviços devem possuir nome e valor base cadastrados
- RN07: o sistema deve permitir edição e exclusão de registros cadastrados
- RN08: o sistema deve armazenar as informações de clientes, produtos e serviços de forma organizada
- RN09: o histórico pode ser vinculado opcionalmente a um serviço

## 14. Situação Atual do Projeto

Atualmente, o sistema já entrega a navegação principal e os três módulos centrais com operações de cadastro e manutenção.

Pontos importantes sobre o estado atual:

- existe tabela `usuarios` no banco, mas o fluxo completo de autenticação ainda não está implementado na interface
- o dump atual contém campos e gatilhos mais recentes do que o script inicial `sql/banco.sql`
- a documentação visual do banco já está disponível em PDF

## 15. Protótipo e Referências

- Figma: [Protótipo de Média](https://www.figma.com/design/i3cWGxCxi4FK2zsetiAnjg/prototipo-sistema-salao?node-id=2-2&p=f&t=PC5uKsXVbdoyd6ko-0)
- Referência do padrão: [ISO/IEC/IEEE 29148:2018](https://www.iso.org/standard/72089.html)

## 16. Observação sobre o Padrão

Este README não substitui a norma oficial nem declara conformidade formal com ela. A organização do conteúdo foi inspirada na estrutura de documentação de requisitos e produto descrita pelo padrão ISO/IEC/IEEE 29148:2018, adaptada para a realidade acadêmica e prática deste projeto.
