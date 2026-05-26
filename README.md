# Sistema de Gerenciamento - Terapia Capilar

## Sobre o Projeto

O projeto consiste no desenvolvimento de um sistema simples de gerenciamento para uma terapeuta capilar/cabeleireira, com foco na organização de clientes, produtos e serviços de forma prática e fácil.

O sistema será utilizado apenas pela própria profissional, substituindo anotações feitas manualmente em cadernos e agendas físicas.

---

## Problema

Atualmente, o controle de clientes, serviços e produtos é realizado manualmente, dificultando a organização das informações, o acompanhamento das clientes e o acesso rápido aos dados necessários no dia a dia.

Além disso, não existe um controle centralizado para armazenar observações importantes sobre cada cliente e os serviços realizados.

---

## Objetivo Geral

Desenvolver um sistema simples e funcional para auxiliar na organização e gerenciamento de clientes, produtos e serviços de uma terapeuta capilar/cabeleireira.

---

## Objetivos Específicos

- Cadastrar clientes
- Cadastrar produtos
- Cadastrar serviços
- Facilitar a consulta de informações
- Permitir observações sobre clientes
- Registrar histórico simples de atendimentos
- Melhorar a organização da profissional

---

## Escopo do Sistema

O sistema terá:

- Cadastro de clientes
- Cadastro de produtos
- Cadastro de serviços
- Pesquisa de clientes
- Edição e exclusão de registros
- Observações sobre clientes
- Histórico simples de sessões cadastrado manualmente

O sistema não terá nesta primeira versão:

- Controle financeiro
- Agenda online
- Integração com WhatsApp
- Relatórios avançados
- Controle avançado de estoque
- Sistema multiusuário
- Mensagens automáticas

---

## Funcionalidades

- Login simples
- Cadastro de clientes
- Cadastro de produtos
- Cadastro de serviços
- Listagem de clientes
- Pesquisa de clientes
- Registro de observações
- Histórico simples de atendimentos

---

## Público-Alvo

O sistema será utilizado individualmente por uma terapeuta capilar/cabeleireira para auxiliar na organização de informações relacionadas aos atendimentos e clientes.

---

## Requisitos Funcionais

- RF01: O sistema deve permitir cadastrar clientes.
- RF02: O sistema deve permitir listar os clientes cadastrados.
- RF03: O sistema deve permitir editar os dados de um cliente.
- RF04: O sistema deve permitir excluir clientes.
- RF05: O sistema deve permitir pesquisar clientes pelo nome.
- RF06: O sistema deve permitir adicionar observações no cadastro do cliente.
- RF07: O sistema deve permitir registrar histórico simples de sessões no perfil do cliente.
- RF08: O sistema deve permitir cadastrar produtos.
- RF09: O sistema deve permitir listar, editar e excluir produtos.
- RF10: O sistema deve permitir cadastrar serviços.
- RF11: O sistema deve permitir listar, editar e excluir serviços.

--- 


## Requisitos Não Funcionais

- RNF01: O sistema deve possuir interface simples e intuitiva.
- RNF02: O sistema deve ser desenvolvido para ambiente web.
- RNF03: O sistema deve permitir acesso apenas mediante autenticação.
- RNF04: O sistema deve armazenar os dados em banco de dados relacional.
- RNF05: O sistema deve apresentar tempo de resposta adequado nas consultas e cadastros.
- RNF06: O sistema deve possuir organização visual limpa e de fácil navegação.
- RNF07: O sistema deve garantir integridade das informações cadastradas.
- RNF08: O sistema deve ser desenvolvido com foco em facilidade de manutenção.
- RNF09: O sistema deve permitir utilização em computadores com acesso à internet.
- RNF10: O sistema deve possuir compatibilidade com navegadores modernos.

## Regras de Negócio

- RN01: Cada cliente deve possuir pelo menos um nome cadastrado.
- RN02: Clientes podem possuir observações e informações sobre características capilares.
- RN03: O histórico de sessões deverá ser cadastrado manualmente pela profissional.
- RN04: Cada registro de histórico deve estar vinculado a um cliente.
- RN05: Produtos devem possuir nome para serem cadastrados.
- RN06: Serviços devem possuir nome e valor base cadastrados.
- RN07: Somente usuários autenticados poderão acessar o sistema.
- RN08: O sistema deverá permitir edição e exclusão de registros cadastrados.
- RN09: O sistema deverá armazenar as informações de clientes, produtos e serviços de forma organizada.
- RN10: O sistema será utilizado apenas pela profissional responsável pelos atendimentos.

## Estrutura do banco de dados
### Entidades do sistema 
---
#### Usuários
    - id
    - nome
    - email 
    - senha

#### Clientes
    - id
    - nome 
    - observacoes
    - historico
    - criacao_at (campo que guarda data de criação)

#### Produtos
    - id
    - nome
    - descrição 
    - marca
    - quantidade
#### Historico_clientes
    - cliente_id
    - servico_id
    - data_historico
    - observacao
---
