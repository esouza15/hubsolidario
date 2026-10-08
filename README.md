# HubSolidário – Plataforma de Gestão de Doações

> **Iniciativa:** Esthefison Souza (Projeto Integrador - 2026)
> 
> **Instituição Beneficiada:** Instituto Musical e Artístico Sol do Pantanal
> 
> **Localização:** Campo Grande / MS
> 
> **Apoio Acadêmico:** Projeto Integrador – Curso Superior de Tecnologia da Informação (UFMS Digital) AGEAD
> 
> **Repositório Github:** Acesse em https://github.com/esouza15/hubsolidario

## Sobre o Projeto

O **HubSolidário** é uma aplicação web concebida para centralizar, organizar e rastrear o fluxo de arrecadação, triagem e controle de inventário de doações físicas (vestuários, agasalhos e alimentos não perecíveis).

A solução substitui os controles analógicos e registros manuais historicamente mantidos no **Instituto Musical e Artístico Sol do Pantanal**, eliminando a assimetria de informações que provocava o acúmulo de itens de baixa procura e o desabastecimento de mantimentos prioritários destinados às famílias em situação de vulnerabilidade e insegurança alimentar.

## Tech Stack & Arquitetura

O sistema adota o padrão monolítico **Model-View-Controller (MVC)**, focado em eficiência operacional, segurança transacional e navegação fluida em dispositivos móveis (*Mobile-First*):

- **Linguagem & Framework:** PHP 8.2+ / Laravel 12 (Gerenciamento de rotas, ORM Eloquent, transações seguras)
  
- **Banco de Dados:** MySQL (Garantia de integridade referencial e conformidade ACID)
  
- **Front-end & Views:** Blade Engine + Tailwind CSS (Componentes responsivos e leves)
  
- **Experiência do Usuário:** PWA (Progressive Web App) para acesso otimizado em smartphones
  

## Funcionalidades Principais (Escopo do Sistema)

- **Controle de Entidades e Doadores:** Cadastro de doadores, consulta de histórico e gestão de instituições parceiras.
  
- **Gestão de Intenções de Doação:** Registro das ofertas de alimentos e roupas com controle de quantidade e acompanhamento do status de entrega.
  
- **Módulo de Triagem Visual:** Interface de entrada rápida de itens com seletores táticos padronizados por categoria, subcategoria, tamanho e data de validade.
  
- **Match Solidário Direto:** Cruzamento dinâmico entre as ofertas cadastradas e as demandas urgentes de alimentos e agasalhos registradas.
  
- **Controle de Inventário e Demandas:** Atualização e acompanhamento em tempo real do estoque físico de mantimentos.
  

## Guia de Uso e Instalação (Usuário Final / Voluntário)

O HubSolidário é uma aplicação web leve e responsiva, acessível diretamente via navegador ou instalável como um aplicativo Web no smartphone (PWA).

### 1. Acesso via Navegador

1. Acesse o endereço web da aplicação em:
  
  👉 https://hubsolidario.remotoagencia.com.br/
  
2. Efetue login com as suas credenciais de doador, voluntário ou instituição parceira clicando no botão **"Entrar / Cadastrar-se"** no canto superior direito.
  
3. Escolha seu perfil:
  

- **Doador:** Para quem quer apoiar causas.
  
- **Agente de Triagem:** Para voluntários da instituição.
  

#### Para testar a aplicação, use os seguintes perfis pré-cadastrados:

- **gestortest**, para testar o painel administrativo:
  
  - gestortest@email.com.br no campo E-mail registrado
  - 12345678 no campo Sua Senha.
  - OBS: este tem acesso a todos os ambientes.

- **agentetri**, para testar o ambiente de triagem:
  
  - agentetri@email.com.br
  - 12345678 no campo Sua Senha.
- **aristoteles**, para testar o ambiente do doador:
  
  - aristoteles@email.com.br
  - 12345678 no campo Sua Senha.

### 2. Adicionar à Tela Inicial (Instalação PWA no Smartphone)

Para utilizar o sistema nos mutirões de triagem física sem a barra de navegação do browser:

- **Android (Google Chrome):**
  

1. Abra o link do sistema no Chrome.
  
2. Toque no menu de três pontos (`⋮`) no canto superior direito.
  
3. Selecione **"Adicionar à tela inicial"** ou **"Instalar aplicativo"**.
  

- **iOS / iPhone (Safari):**
  

1. Abra o link no Safari.
  
2. Toque no botão de **Compartilhar** (ícone do quadrado com a seta para cima).
  
3. Role as opções e selecione **"Adicionar à Tela de Início"**.
  

## Licença

Este projeto está licenciado sob a [Licença](./LICENCE.md).

Consulte o arquivo [LICENCE.md](./LICENCE.md) para mais detalhes.