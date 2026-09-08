# 📖 Guia de Instalação - Sistema de Caixa ERP Dist

Siga este guia passo a passo para instalar o sistema no HostGator.

## ⚠️ Pré-requisitos

- Conta ativa no HostGator
- Acesso ao cPanel
- Conhecimento básico de FTP/SFTP
- Navegador web moderno

---

## 🔧 PASSO 1: Preparar o Banco de Dados

### 1.1 Acessar o phpMyAdmin

1. Faça login no cPanel do HostGator
2. Procure por **"phpMyAdmin"** (geralmente em "Bancos de Dados")
3. Clique para abrir

### 1.2 Selecionar o Banco de Dados

1. No painel esquerdo, clique em **`inlaud99_erpdist`**
2. Você verá a lista de tabelas (provavelmente vazia)

### 1.3 Importar o Script SQL

1. Clique na aba **"Importar"** (no topo)
2. Clique em **"Escolher arquivo"**
3. Selecione o arquivo **`database_setup.sql`** do seu computador
4. Clique em **"Executar"** (botão azul no final da página)

**Resultado esperado:**
```
✓ Importação bem-sucedida
```

Se receber erro, verifique:
- Se o arquivo está correto
- Se o banco de dados existe
- Se o usuário tem permissões

### 1.4 Verificar as Tabelas

1. Atualize a página (F5)
2. No painel esquerdo, você deve ver:
   - `grupos`
   - `produtos`
   - `vendas`
   - `itens_venda`

Se não aparecerem, volte ao passo 1.3.

---

## 📤 PASSO 2: Upload dos Arquivos

### 2.1 Conectar via FTP/SFTP

**Opção A: Usando cPanel File Manager (mais fácil)**

1. No cPanel, procure por **"Gerenciador de Arquivos"** ou **"File Manager"**
2. Clique para abrir
3. Navegue até a pasta **`public_html`**

**Opção B: Usando FTP/SFTP (mais profissional)**

1. Abra seu cliente FTP (FileZilla, WinSCP, etc.)
2. Conecte com:
   - **Host:** seu-dominio.com
   - **Usuário:** seu_usuario_ftp
   - **Senha:** sua_senha_ftp
   - **Porta:** 21 (FTP) ou 22 (SFTP)
3. Navegue até `public_html`

### 2.2 Fazer Upload dos Arquivos

Faça upload dos seguintes arquivos para a pasta `public_html`:

```
✓ caixa.html
✓ config.php
✓ api_buscar_produtos.php
✓ api_salvar_venda.php
✓ teste_conexao.php
```

**Importante:** Não faça upload de:
- `database_setup.sql` (já foi importado)
- `README.md` (opcional, apenas para referência)
- `INSTALACAO.md` (este arquivo)

### 2.3 Verificar Permissões

Os arquivos PHP devem ter permissão de execução:

1. Clique com botão direito em cada arquivo `.php`
2. Selecione **"Permissões"** ou **"Change Permissions"**
3. Defina para **644** ou **755**

---

## 🔐 PASSO 3: Alterar Credenciais (IMPORTANTE!)

⚠️ **SEGURANÇA CRÍTICA:** As credenciais padrão devem ser alteradas imediatamente!

### 3.1 Alterar Senha do Banco de Dados

1. No cPanel, procure por **"MySQL Databases"** ou **"Bancos de Dados MySQL"**
2. Procure por `inlaud99_admin` na seção "Usuários"
3. Clique em **"Alterar Senha"** ou **"Change Password"**
4. Digite uma nova senha forte (ex: `Abc123!@#XyZ789`)
5. Clique em **"Alterar Senha"**

### 3.2 Atualizar o Arquivo config.php

1. Abra o arquivo `config.php` via File Manager ou FTP
2. Localize as linhas:
   ```php
   define('DB_USER', 'inlaud99_admin');
   define('DB_PASS', 'Admin259087@');
   ```
3. Altere a senha:
   ```php
   define('DB_PASS', 'sua_nova_senha_aqui');
   ```
4. Salve o arquivo

### 3.3 Fazer Upload do Arquivo Atualizado

1. Faça upload do `config.php` atualizado para `public_html`
2. Sobrescreva o arquivo anterior

---

## ✅ PASSO 4: Testar a Instalação

### 4.1 Acessar a Página de Teste

1. Abra seu navegador
2. Acesse: `https://seu-dominio.com/teste_conexao.php`
3. Você verá uma página com vários testes

### 4.2 Verificar os Resultados

**Se tudo estiver verde (✓):**
- Parabéns! A instalação foi bem-sucedida
- Você pode prosseguir para o passo 5

**Se houver erros (✗):**
- Leia a mensagem de erro
- Verifique a seção "Solução de Problemas" abaixo

**Se houver avisos (⚠):**
- Geralmente não é crítico
- Você pode usar o sistema mesmo assim

---

## 🚀 PASSO 5: Acessar o Sistema

### 5.1 Abrir o Sistema de Caixa

1. Abra seu navegador
2. Acesse: `https://seu-dominio.com/caixa.html`
3. Você verá a interface do sistema

### 5.2 Testar Funcionalidades

1. **Buscar um produto:**
   - Digite "notebook" no campo de busca
   - Aguarde os resultados aparecerem
   - Clique em um produto

2. **Lançar um produto:**
   - Verifique a quantidade e preço
   - Clique em "➕ Lançar Produto"
   - O produto deve aparecer na tabela

3. **Finalizar uma venda:**
   - Adicione alguns produtos
   - Clique em "✓ Finalizar Venda"
   - Confirme a ação
   - Você deve ver uma mensagem de sucesso

---

## 🐛 Solução de Problemas

### Problema: "Erro ao conectar ao banco de dados"

**Causa:** Credenciais incorretas ou banco não existe

**Solução:**
1. Verifique se `config.php` tem as credenciais corretas
2. Confirme no cPanel que o banco `inlaud99_erpdist` existe
3. Confirme que o usuário `inlaud99_admin` existe
4. Tente acessar o phpMyAdmin com essas credenciais

### Problema: "Nenhum produto encontrado"

**Causa:** Banco de dados vazio

**Solução:**
1. Acesse o phpMyAdmin
2. Verifique se a tabela `produtos` tem dados
3. Se estiver vazia, reimporte o arquivo `database_setup.sql`

### Problema: "Erro 404 - Página não encontrada"

**Causa:** Arquivos não foram enviados ou estão no local errado

**Solução:**
1. Verifique se os arquivos estão em `public_html`
2. Verifique o caminho correto
3. Tente acessar diretamente: `https://seu-dominio.com/teste_conexao.php`

### Problema: "Erro 500 - Internal Server Error"

**Causa:** Erro no PHP ou permissões incorretas

**Solução:**
1. Verifique as permissões dos arquivos (devem ser 644 ou 755)
2. Verifique os logs de erro do PHP no cPanel
3. Tente acessar `teste_conexao.php` para mais detalhes

### Problema: "Erro ao salvar venda"

**Causa:** Banco de dados não está acessível ou transação falhou

**Solução:**
1. Verifique a conexão com o banco de dados
2. Verifique se há espaço em disco disponível
3. Verifique os logs de erro do MySQL

### Problema: "A busca não funciona"

**Causa:** AJAX não está funcionando ou arquivo `api_buscar_produtos.php` tem erro

**Solução:**
1. Abra o console do navegador (F12)
2. Procure por mensagens de erro
3. Verifique se `api_buscar_produtos.php` está no local correto
4. Tente acessar diretamente: `https://seu-dominio.com/api_buscar_produtos.php`

---

## 📱 Testar em Diferentes Dispositivos

Após a instalação, teste o sistema em:

- ✅ Desktop (Chrome, Firefox, Safari, Edge)
- ✅ Tablet (iPad, Android)
- ✅ Mobile (iPhone, Android)

O sistema é responsivo e deve funcionar em todos os dispositivos.

---

## 🔒 Checklist de Segurança

Após a instalação, verifique:

- [ ] Senha do banco de dados foi alterada
- [ ] Arquivo `config.php` foi atualizado com a nova senha
- [ ] HTTPS está habilitado (procure pelo cadeado no navegador)
- [ ] Arquivo `teste_conexao.php` pode ser removido (opcional)
- [ ] Backups do banco de dados estão configurados

---

## 📞 Próximos Passos

### Adicionar Mais Produtos

1. Acesse o phpMyAdmin
2. Vá para a tabela `produtos`
3. Clique em "Inserir" ou "Insert"
4. Preencha os dados do novo produto
5. Clique em "Executar" ou "Go"

### Adicionar Novos Grupos

1. Acesse o phpMyAdmin
2. Vá para a tabela `grupos`
3. Clique em "Inserir" ou "Insert"
4. Preencha o nome do grupo
5. Clique em "Executar" ou "Go"

### Visualizar Vendas

1. Acesse o phpMyAdmin
2. Vá para a tabela `vendas`
3. Clique em "Procurar" ou "Browse"
4. Você verá todas as vendas realizadas

---

## 📚 Documentação Adicional

Para mais informações, consulte:

- **README.md** - Documentação completa do sistema
- **Documentação do HostGator** - https://www.hostgator.com.br/
- **Documentação do PHP** - https://www.php.net/
- **Documentação do MySQL** - https://dev.mysql.com/

---

## ✨ Parabéns!

Você completou a instalação do Sistema de Caixa ERP Dist!

Se tiver dúvidas ou problemas, consulte a seção "Solução de Problemas" acima ou entre em contato com o suporte do HostGator.

**Data de Instalação:** 26 de dezembro de 2025
**Versão:** 1.0.0
