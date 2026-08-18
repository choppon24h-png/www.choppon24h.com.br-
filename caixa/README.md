# Sistema de Caixa - ERP Dist

Sistema completo de gerenciamento de vendas com busca em tempo real, desenvolvido em HTML estático, PHP procedural e MySQL.

## 📋 Características

- **Busca em Tempo Real (AJAX)**: Busque produtos por nome, código, código de barras ou grupo
- **Lançamento de Produtos**: Interface intuitiva para adicionar produtos à venda
- **Tabela Dinâmica**: Edite quantidade, preço e remova itens facilmente
- **Cálculo Automático**: Subtotal, desconto e total são calculados automaticamente
- **Finalização de Venda**: Salve vendas completas no banco de dados
- **Design Responsivo**: Funciona em desktop, tablet e mobile
- **PHP Procedural**: Código simples e direto, sem frameworks complexos

## 🗄️ Estrutura do Banco de Dados

### Tabelas

1. **grupos**: Categorias de produtos
2. **produtos**: Catálogo de produtos
3. **vendas**: Registro de vendas
4. **itens_venda**: Itens de cada venda

## 📁 Arquivos do Projeto

```
/
├── caixa.html                  # Página principal do sistema
├── config.php                  # Configurações e funções auxiliares
├── api_buscar_produtos.php     # API para busca AJAX
├── api_salvar_venda.php        # API para salvar vendas
├── database_setup.sql          # Script para criar banco de dados
└── README.md                   # Este arquivo
```

## 🚀 Instalação

### Pré-requisitos

- HostGator com suporte a PHP e MySQL
- Acesso ao painel de controle (cPanel)
- Conhecimento básico de FTP/SFTP

### Passo 1: Criar o Banco de Dados

1. Acesse o cPanel do HostGator
2. Vá para "phpMyAdmin"
3. Selecione o banco `inlaud99_erpdist`
4. Clique em "Importar"
5. Selecione o arquivo `database_setup.sql`
6. Clique em "Executar"

**Ou execute manualmente via SQL:**

```sql
-- Copie e cole o conteúdo de database_setup.sql no phpMyAdmin
```

### Passo 2: Upload dos Arquivos

1. Conecte via FTP/SFTP ao seu servidor HostGator
2. Navegue até a pasta pública (geralmente `public_html`)
3. Faça upload dos seguintes arquivos:
   - `caixa.html`
   - `config.php`
   - `api_buscar_produtos.php`
   - `api_salvar_venda.php`

### Passo 3: Verificar Permissões

Certifique-se de que os arquivos PHP têm permissão de execução (geralmente 644 ou 755).

## 🔧 Configuração

### Alterar Credenciais do Banco de Dados

⚠️ **IMPORTANTE**: As credenciais padrão devem ser alteradas por segurança.

1. Abra `config.php`
2. Altere as constantes:
   ```php
   define('DB_HOST', 'seu_host');
   define('DB_USER', 'seu_usuario');
   define('DB_PASS', 'sua_senha');
   define('DB_NAME', 'seu_banco');
   ```
3. Salve e faça upload novamente

### Alterar Timezone

No arquivo `config.php`, procure por:
```php
define('TIMEZONE', 'America/Sao_Paulo');
```

Altere conforme necessário.

## 📖 Como Usar

### 1. Acessar o Sistema

Abra seu navegador e acesse:
```
https://seu-dominio.com/caixa.html
```

### 2. Buscar um Produto

1. Digite no campo "Buscar por Nome, Código, Código de Barras ou Grupo"
2. Aguarde os resultados aparecerem (busca em tempo real)
3. Clique no produto desejado

### 3. Lançar o Produto

1. Ajuste a quantidade se necessário
2. Verifique o preço unitário
3. Clique em "➕ Lançar Produto"

### 4. Gerenciar Itens

Na tabela de itens, você pode:
- **Editar quantidade**: Clique no campo de quantidade
- **Editar preço**: Clique no campo de preço unitário
- **Remover item**: Clique no botão "🗑️ Remover"

### 5. Finalizar Venda

1. Adicione desconto se necessário (opcional)
2. Adicione observações (opcional)
3. Clique em "✓ Finalizar Venda"
4. Confirme a ação

## 🔐 Segurança

### Recomendações Importantes

1. **Altere as credenciais padrão** imediatamente após a instalação
2. **Use HTTPS** em produção
3. **Implemente autenticação** para controlar acesso
4. **Faça backups regulares** do banco de dados
5. **Valide todas as entradas** no servidor (já implementado)
6. **Use prepared statements** para prevenir SQL injection (já implementado)

## 🐛 Solução de Problemas

### Erro: "Erro ao conectar ao banco de dados"

- Verifique se as credenciais em `config.php` estão corretas
- Confirme se o banco de dados existe
- Verifique se o usuário tem permissões no banco

### Erro: "Nenhum produto encontrado"

- Verifique se há produtos cadastrados no banco
- Confirme se os produtos estão com `ativo = 1`
- Tente buscar por um termo diferente

### Erro: "Erro ao salvar venda"

- Verifique os logs do servidor
- Confirme se o banco de dados está acessível
- Verifique se há espaço em disco disponível

## 📊 Exemplos de Dados

O banco de dados vem com alguns produtos de exemplo:

| Código | Nome | Grupo | Preço |
|--------|------|-------|-------|
| PROD001 | Notebook Dell | Eletrônicos | R$ 2.500,00 |
| PROD002 | Mouse Logitech | Eletrônicos | R$ 85,00 |
| PROD003 | Teclado Mecânico | Eletrônicos | R$ 350,00 |
| PROD004 | Arroz Integral 5kg | Alimentos | R$ 25,00 |
| PROD005 | Feijão Carioca 1kg | Alimentos | R$ 8,50 |
| PROD006 | Camiseta Básica | Vestuário | R$ 45,00 |
| PROD007 | Calça Jeans | Vestuário | R$ 120,00 |
| PROD008 | Sabonete Neutro | Higiene | R$ 5,00 |
| PROD009 | Detergente Neutro | Higiene | R$ 3,50 |

## 🔄 Fluxo de Dados

```
1. Usuário digita na busca
   ↓
2. JavaScript envia requisição AJAX
   ↓
3. api_buscar_produtos.php consulta o banco
   ↓
4. Resultados retornam em JSON
   ↓
5. JavaScript exibe os resultados
   ↓
6. Usuário seleciona um produto
   ↓
7. Produto é adicionado à tabela
   ↓
8. Usuário clica "Finalizar Venda"
   ↓
9. JavaScript envia todos os itens via JSON
   ↓
10. api_salvar_venda.php salva no banco (transação)
   ↓
11. Confirmação retorna ao usuário
```

## 📱 Compatibilidade

- ✅ Chrome/Chromium
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ✅ Navegadores mobile

## 🎨 Personalização

### Alterar Cores

No arquivo `caixa.html`, procure pela seção `<style>` e altere as cores:

```css
/* Cor primária (roxo) */
#667eea

/* Cor secundária (roxo escuro) */
#764ba2

/* Cor de sucesso (verde) */
#66bb6a
```

### Alterar Logo/Título

No arquivo `caixa.html`, altere:

```html
<h1>🛒 Sistema de Caixa</h1>
<p>Gerenciamento de vendas - ERP Dist</p>
```

## 📞 Suporte

Para problemas ou dúvidas:

1. Verifique os logs do servidor (cPanel → Erro do PHP)
2. Consulte a documentação do HostGator
3. Verifique o console do navegador (F12)

## 📝 Changelog

### v1.0.0 (2025-12-26)

- ✅ Sistema de busca AJAX
- ✅ Lançamento de produtos
- ✅ Tabela dinâmica de itens
- ✅ Cálculo automático de totais
- ✅ Finalização de vendas
- ✅ Design responsivo
- ✅ Validação de dados
- ✅ Transações no banco de dados

## 📄 Licença

Este projeto é fornecido como está, sem garantias.

---

**Desenvolvido para ERP Dist**
**Data: 26 de dezembro de 2025**
