# 🚀 Guia do Instalador Automático

## O Que é o Instalador?

O **installer.php** é um arquivo que automatiza completamente a instalação do Sistema de Caixa ERP Dist. Ele verifica pré-requisitos, cria o banco de dados, tabelas e insere dados de exemplo, tudo com alguns cliques!

---

## ✨ Vantagens do Instalador

- ✅ **Automatizado**: Não precisa de phpMyAdmin ou linha de comando
- ✅ **Seguro**: Valida todas as entradas e trata erros
- ✅ **Rápido**: Completa a instalação em minutos
- ✅ **Intuitivo**: Interface visual passo a passo
- ✅ **Confiável**: Cria arquivo config.php automaticamente
- ✅ **Flexível**: Permite customizar credenciais do banco

---

## 📋 Pré-requisitos

Antes de usar o instalador, certifique-se de ter:

1. **PHP 7.4 ou superior** instalado no servidor
2. **Extensão MySQLi** habilitada
3. **Acesso ao MySQL** com credenciais válidas
4. **Permissão de escrita** no diretório do site

---

## 🎯 Como Usar o Instalador

### Passo 1: Fazer Upload do Arquivo

1. Conecte via FTP/SFTP ao seu servidor HostGator
2. Navegue até a pasta `public_html`
3. Faça upload do arquivo **`installer.php`**

### Passo 2: Acessar o Instalador

1. Abra seu navegador
2. Acesse: `https://seu-dominio.com/installer.php`
3. Você verá a interface do instalador

### Passo 3: Seguir as Etapas

O instalador tem **7 etapas**:

#### **Etapa 1: Verificar Requisitos**
- Verifica se seu servidor tem PHP, MySQLi e JSON
- Verifica permissões de escrita
- Se tudo estiver verde ✓, clique em "Próximo"

#### **Etapa 2: Configurar Banco de Dados**
- Digite o **Host** (geralmente `localhost`)
- Digite o **Usuário** (ex: `inlaud99_admin`)
- Digite a **Senha** (ex: `Admin259087@`)
- Digite o **Nome do Banco** (ex: `inlaud99_erpdist`)
- Clique em "Testar e Continuar"

**Dica:** Se não souber suas credenciais, verifique no cPanel → MySQL Databases

#### **Etapa 3: Criar Banco de Dados**
- O instalador cria o banco automaticamente
- Clique em "Criar Banco →"

#### **Etapa 4: Criar Tabelas**
- O instalador cria as 4 tabelas necessárias:
  - `grupos`
  - `produtos`
  - `vendas`
  - `itens_venda`
- Clique em "Criar Tabelas →"

#### **Etapa 5: Inserir Dados de Exemplo**
- O instalador insere 9 produtos de exemplo
- Clique em "Inserir Dados →"

#### **Etapa 6: Testar Conexão**
- O instalador testa leitura e escrita no banco
- Clique em "Testar Conexão →"

#### **Etapa 7: Finalizar Instalação**
- O instalador cria o arquivo `config.php`
- Clique em "Finalizar Instalação"
- Se tudo estiver verde, você verá uma mensagem de sucesso!

### Passo 4: Acessar o Sistema

1. Após a instalação, clique em "Acessar Sistema →"
2. Ou acesse: `https://seu-dominio.com/caixa.html`
3. O sistema está pronto para usar!

---

## 🔍 Entendendo a Interface

### Barra de Progresso
Mostra visualmente o progresso da instalação (1 a 7 etapas).

### Mensagens de Status

| Cor | Significado |
|-----|-------------|
| 🟢 Verde | Sucesso - operação realizada com êxito |
| 🔴 Vermelho | Erro - algo deu errado |
| 🟠 Laranja | Aviso - informação importante |

### Botões

| Botão | Ação |
|-------|------|
| "Próximo →" | Avança para a próxima etapa |
| "Testar e Continuar →" | Valida dados e continua |
| "Criar Banco →" | Cria o banco de dados |
| "Criar Tabelas →" | Cria as tabelas |
| "Inserir Dados →" | Insere dados de exemplo |
| "Testar Conexão →" | Testa a conexão |
| "Finalizar Instalação" | Conclui a instalação |

---

## ⚠️ Solução de Problemas

### "Requisitos não atendidos"

**Problema:** Um ou mais requisitos falharam na Etapa 1

**Solução:**
- Verifique se PHP 7.4+ está instalado
- Verifique se MySQLi está habilitado
- Entre em contato com o suporte do HostGator

### "Erro ao conectar ao banco"

**Problema:** As credenciais estão incorretas

**Solução:**
1. Verifique as credenciais no cPanel
2. Certifique-se de que o usuário tem permissão no banco
3. Tente novamente com as credenciais corretas

### "Erro ao criar banco de dados"

**Problema:** O banco já existe ou há permissão insuficiente

**Solução:**
1. Se o banco já existe, isso é normal - continue
2. Se há erro de permissão, entre em contato com o suporte

### "Erro ao criar tabelas"

**Problema:** As tabelas não foram criadas

**Solução:**
1. Verifique se o banco está selecionado
2. Verifique se há espaço em disco
3. Tente novamente

### "Erro ao criar config.php"

**Problema:** O arquivo não foi criado

**Solução:**
1. Verifique as permissões do diretório (deve ser 755)
2. Tente criar manualmente copiando o arquivo fornecido
3. Entre em contato com o suporte

---

## 🔐 Segurança

### Após a Instalação

⚠️ **IMPORTANTE:** Altere a senha do banco de dados!

1. Acesse o cPanel
2. Vá para "MySQL Databases"
3. Procure pelo usuário `inlaud99_admin`
4. Clique em "Change Password"
5. Digite uma nova senha forte
6. Edite o arquivo `config.php` com a nova senha

### Remover o Instalador

Após a instalação bem-sucedida:

1. Acesse o File Manager do cPanel
2. Procure pelo arquivo `installer.php`
3. Clique com botão direito e selecione "Delete"
4. Confirme a exclusão

**Por que remover?** Para evitar que alguém acesse o instalador novamente e altere suas configurações.

---

## 📊 O Que o Instalador Faz

### Arquivo config.php

O instalador cria automaticamente o arquivo `config.php` com:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'inlaud99_admin');
define('DB_PASS', 'Admin259087@');
define('DB_NAME', 'inlaud99_erpdist');
define('DB_CHARSET', 'utf8mb4');
```

### Banco de Dados

O instalador cria:

- **Banco:** `inlaud99_erpdist`
- **Charset:** UTF-8 (suporta acentos)
- **Collation:** utf8mb4_unicode_ci

### Tabelas

| Tabela | Registros | Descrição |
|--------|-----------|-----------|
| `grupos` | 4 | Categorias de produtos |
| `produtos` | 9 | Produtos de exemplo |
| `vendas` | 0 | Vendas (vazio inicialmente) |
| `itens_venda` | 0 | Itens de vendas (vazio inicialmente) |

### Dados de Exemplo

O instalador insere:

**Grupos:**
- Eletrônicos
- Alimentos
- Vestuário
- Higiene

**Produtos:**
- 3 produtos eletrônicos
- 2 produtos alimentícios
- 2 produtos de vestuário
- 2 produtos de higiene

---

## 🎓 Próximos Passos

### Após a Instalação

1. **Remover o instalador** (por segurança)
2. **Alterar a senha do banco** (importante!)
3. **Testar o sistema** (fazer uma venda de teste)
4. **Fazer backup** (importante!)

### Adicionar Mais Produtos

1. Acesse `https://seu-dominio.com/caixa.html`
2. Use o sistema normalmente
3. Os produtos serão salvos no banco

### Visualizar Dados

1. Acesse o phpMyAdmin no cPanel
2. Selecione o banco `inlaud99_erpdist`
3. Visualize as tabelas e dados

---

## 📞 Suporte

### Se Tiver Problemas

1. **Verifique os logs** do servidor (cPanel → Error Logs)
2. **Consulte a documentação** (README.md)
3. **Entre em contato** com o suporte do HostGator

### Documentação Relacionada

- **README.md** - Documentação completa
- **INSTALACAO.md** - Guia de instalação manual
- **RESUMO_TECNICO.md** - Detalhes técnicos

---

## ✅ Checklist de Instalação

- [ ] Fazer upload de `installer.php`
- [ ] Acessar `https://seu-dominio.com/installer.php`
- [ ] Completar Etapa 1 (Verificar Requisitos)
- [ ] Completar Etapa 2 (Configurar Banco)
- [ ] Completar Etapa 3 (Criar Banco)
- [ ] Completar Etapa 4 (Criar Tabelas)
- [ ] Completar Etapa 5 (Inserir Dados)
- [ ] Completar Etapa 6 (Testar Conexão)
- [ ] Completar Etapa 7 (Finalizar)
- [ ] Acessar o sistema em `caixa.html`
- [ ] Remover `installer.php` (segurança)
- [ ] Alterar senha do banco (segurança)

---

## 🎉 Pronto!

Você completou a instalação! Agora você pode:

- ✅ Buscar produtos em tempo real
- ✅ Lançar produtos na venda
- ✅ Editar quantidade e preço
- ✅ Finalizar vendas
- ✅ Visualizar histórico no banco de dados

**Aproveite o sistema!**

---

**Versão:** 1.0.0
**Data:** 26 de dezembro de 2025
**Status:** Pronto para uso
