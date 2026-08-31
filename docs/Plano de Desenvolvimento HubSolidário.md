# Plano de Desenvolvimento HubSolidário

A implementação da aplicação web HubSolidário será conduzida por um fluxo de Integração e Entrega Contínuas (CI/CD) alinhado à arquitetura MVC em PHP/Laravel e banco de dados MySQL. O plano de desenvolvimento abaixo detalha o ciclo de vida do código desde a máquina local até o servidor de produção:

## 1. Instalação da infraestrutura local & Repositório Git/Github

O ambiente de desenvolvimento é padronizado e isolado através de contêineres Docker, orquestrados pelo Docker Compose, para executar o PHP (8.2+), o servidor web e o banco de dados MySQL. Simultaneamente, o repositório é criado no GitHub utilizando o Git para assegurar o controle de versão e o rastreamento seguro das alterações do código-fonte.

## 2. Código desenvolvido

Nesta fase, a codificação da arquitetura monolítica Model-View-Controller (MVC) é realizada. Desenvolvem-se os modelos de persistência (Doador, Instituição, Doação, ItemDoação), os controladores de negócio (ex: gestão de intenções, *match* de doações e triagem rápida) e as interfaces responsivas *Mobile-First* utilizando o motor de templates Blade e a biblioteca Tailwind CSS carregada via CDN.

## 3. Testado localmente

A aplicação é executada e verificada nos contêineres locais. Os testes garantem o funcionamento correto dos seletores visuais táteis para dispositivos móveis, a comunicação sem erros entre a interface e o banco de dados, e a integridade atômica (ACID) das transações no MySQL ao registrar entradas e saídas no inventário de doações. Testes automatizados de integração (Pest/PHPUnit).

## 4. git add

Com a validação local concluída, as modificações realizadas nos arquivos (como a criação de novas *migrations*, componentes Blade ou *controllers*) são preparadas para versionamento, sendo movidas para a *staging area* do repositório local.

## 5. git commit

As alterações preparadas são consolidadas em pacotes lógicos no histórico do Git. Cada *commit* deve receber uma mensagem semântica e objetiva, documentando o que foi construído (ex: configuração do módulo de *match* ou aplicação de componentes táteis para a triagem).

## 6. git push origin main

O código consolidado é enviado do ambiente local para o repositório remoto no GitHub. O envio direciona-se à ramificação principal (`main`), que atua como a versão de referência do sistema HubSolidário.

## 7. GitHub Actions

A chegada do novo código na ramificação `main` aciona automaticamente um *pipeline* configurado no GitHub Actions. Este ambiente de integração executa rotinas automatizadas, como a validação de sintaxe, e prepara as credenciais e chaves para o processo de implantação.

## 8. SSH

O *workflow* do GitHub Actions estabelece uma conexão criptografada e segura via protocolo SSH com o servidor de produção, permitindo a execução remota de comandos no ambiente de hospedagem que atenderá o Instituto Sol do Pantanal e as entidades parceiras.

## 9. deploy.sh

Uma vez autenticado, o *pipeline* aciona o script `deploy.sh` no servidor. Este script automatiza tarefas repetitivas, ativando o modo de manutenção do Laravel, atualizando dependências do Composer, e limpando os caches de rotas e configurações para evitar conflitos com a nova versão.

## 10. Git Sync

Dentro da execução do script de *deploy*, o servidor realiza a sincronização com o GitHub (através de um comando como `git pull origin main`), baixando fisicamente os arquivos mais recentes do código-fonte para o ambiente de produção.

## 11. Migrations

Com os novos arquivos baixados, o script executa o comando de migração do banco de dados (ex: `php artisan migrate --force`). Isso traduz as definições estruturais do código para o MySQL de produção, criando ou alterando tabelas essenciais, chaves primárias e relacionamentos sem comprometer os dados assistenciais já armazenados.

## 12. Produção atualizada

O modo de manutenção é desativado e o ciclo é concluído. A nova versão do HubSolidário fica imediatamente disponível na nuvem para os doadores realizarem intenções de entrega e para os voluntários operarem o sistema de triagem ágil durante os mutirões em Campo Grande/MS.