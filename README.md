# 📦 Projeto - Sistema de Solicitação de Viagens

## 🚀 Setup Inicial

### 🛠️ Configuração do Banco de Dados

Edite o arquivo `.env` na raiz do projeto e configure sua conexão com o banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_moodle45
DB_USERNAME=root
DB_PASSWORD=rootpassword

### 👤 Usuário Admin (Seeder)
O usuário admin é criado automaticamente através do seeder localizado em:

🚀 Setup Inicial
👤 Usuário Admin (Seeder)
O usuário admin é criado automaticamente através do seeder localizado em:

database/seeders/AdminUserSeeder.php
Credenciais padrão:
Usuário: admin
Senha: admin123

Para rodar as migrations com os seeders: php artisan migrate:fresh --seed
🔧 Funcionalidades Implementadas
❌ Cancelamento de Solicitações
Foram adicionadas duas opções para o cancelamento de viagens:

Solicitação já aprovada

Exibe o texto: "Solicitação já aprovada"

Solicitação em andamento (ainda não aprovada)

Exibe apenas a confirmação: "Tem certeza?"

🔐 Restrições por Usuário
O usuário que solicitou a viagem não pode alterar o status da mesma, mesmo que tenha permissão de admin.
Isso evita alterações indevidas pelo próprio solicitante.

🌐 Internacionalização
📁 Arquivo de Idioma
Criado o arquivo de tradução pt_BR para personalização de mensagens automáticas do Laravel.

Local do arquivo:

resources/lang/pt_BR/
🖥️ Melhorias na Interface do Usuário
🗂️ Ordenação de Solicitações
Na visualização do usuário, as solicitações alteradas recentemente são exibidas no topo da lista.

🎨 Cores no Status da Viagem
Aprovado: Verde

Cancelado: Vermelho

Solicitado: Sem cor (neutro)

🔔 Notificação Flutuante
Após a alteração do status de uma viagem, uma div flutuante de aviso é exibida para o usuário com a informação da mudança.