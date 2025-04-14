# 📦 Projeto - Sistema de Solicitação de Viagens

## 🚀 Setup Inicial

### 👤 Usuário Admin (Seeder)
O usuário admin é criado automaticamente através do seeder localizado em:

database/seeders/AdminUserSeeder.php

markdown
Copiar
Editar

**Credenciais padrão:**

- **Usuário:** `admin`  
- **Senha:** `admin123`

Para rodar as migrations **com os seeders**, utilize o comando:

```bash
php artisan migrate:fresh --seed
🔧 Funcionalidades Implementadas
❌ Cancelamento de Solicitações
Foram adicionadas duas opções para o cancelamento de viagens:

Solicitação já aprovada

Exibe o texto: "Solicitação já aprovada"

Solicitação em andamento (ainda não aprovada)

Exibe apenas a confirmação: "Tem certeza?"

🔐 Restrições por Usuário
O usuário que solicitou a viagem não pode alterar o status da mesma, mesmo que tenha permissão de admin.
Essa regra garante integridade e evita conflitos de interesse.

🌐 Internacionalização
📁 Arquivo de Idioma
Foi criado o arquivo de idioma pt_BR para tradução e personalização das mensagens automáticas do Laravel.

Localização do arquivo:

bash
Copiar
Editar
resources/lang/pt_BR/
🖥️ Melhorias na Interface do Usuário
🗂️ Ordenação de Solicitações
Na visualização do usuário, as solicitações alteradas recentemente são exibidas no topo da lista, facilitando o acompanhamento.

🎨 Cores no Status da Viagem
Para melhorar a visualização, foram adicionadas cores aos status das viagens:

✅ Aprovado: Verde

❌ Cancelado: Vermelho

⏳ Solicitado: Sem cor (neutro)

🔔 Notificação Flutuante
Após qualquer alteração no status da viagem, o sistema exibe uma div flutuante informando o usuário sobre a atualização da sua solicitação.
