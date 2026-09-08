# 📋 Resumo Técnico - Sistema de Caixa ERP Dist

## 🎯 Visão Geral

Sistema de gerenciamento de vendas (PDV) desenvolvido com **HTML estático**, **PHP procedural** e **MySQL**, otimizado para hospedagem no HostGator.

---

## 📊 Arquitetura

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENTE (Browser)                        │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              caixa.html (HTML + CSS + JS)            │  │
│  │  • Interface responsiva                              │  │
│  │  • Busca AJAX em tempo real                          │  │
│  │  • Gerenciamento de itens                            │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            ↕ AJAX/JSON
┌─────────────────────────────────────────────────────────────┐
│                    SERVIDOR (PHP)                           │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  config.php (Configuração e Funções Auxiliares)     │  │
│  │  • Conexão com banco de dados                        │  │
│  │  • Funções de sanitização                           │  │
│  │  • Formatação de dados                              │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  api_buscar_produtos.php (API de Busca)             │  │
│  │  • Busca em múltiplos campos                         │  │
│  │  • Retorna JSON com resultados                       │  │
│  └──────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  api_salvar_venda.php (API de Vendas)               │  │
│  │  • Salva venda com transação                         │  │
│  │  • Insere itens da venda                            │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            ↕ SQL
┌─────────────────────────────────────────────────────────────┐
│                 BANCO DE DADOS (MySQL)                      │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  • grupos (categorias de produtos)                   │  │
│  │  • produtos (catálogo)                              │  │
│  │  • vendas (cabeçalho de vendas)                      │  │
│  │  • itens_venda (itens de cada venda)                │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 📁 Estrutura de Arquivos

| Arquivo | Tipo | Descrição |
|---------|------|-----------|
| `caixa.html` | HTML/CSS/JS | Interface principal do sistema |
| `config.php` | PHP | Configuração e funções auxiliares |
| `api_buscar_produtos.php` | PHP | API para busca AJAX |
| `api_salvar_venda.php` | PHP | API para salvar vendas |
| `teste_conexao.php` | PHP | Página de teste de conexão |
| `database_setup.sql` | SQL | Script de criação do banco |
| `README.md` | Markdown | Documentação completa |
| `INSTALACAO.md` | Markdown | Guia de instalação passo a passo |

---

## 🗄️ Banco de Dados

### Tabela: `grupos`

```sql
CREATE TABLE grupos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL UNIQUE,
  descricao TEXT,
  ativo TINYINT(1) DEFAULT 1,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Campos:**
- `id`: Identificador único
- `nome`: Nome da categoria
- `descricao`: Descrição opcional
- `ativo`: Flag de ativação (1 = ativo, 0 = inativo)
- `data_criacao`: Data de criação automática

### Tabela: `produtos`

```sql
CREATE TABLE produtos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(150) NOT NULL,
  codigo VARCHAR(50) UNIQUE,
  codigo_barras VARCHAR(50) UNIQUE,
  grupo_id INT NOT NULL,
  preco DECIMAL(10, 2) NOT NULL,
  estoque INT DEFAULT 0,
  ativo TINYINT(1) DEFAULT 1,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);
```

**Campos:**
- `id`: Identificador único
- `nome`: Nome do produto
- `codigo`: Código interno (único)
- `codigo_barras`: Código de barras (único)
- `grupo_id`: Referência para a tabela `grupos`
- `preco`: Preço unitário (2 casas decimais)
- `estoque`: Quantidade em estoque
- `ativo`: Flag de ativação
- `data_criacao`: Data de criação automática

### Tabela: `vendas`

```sql
CREATE TABLE vendas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero_venda INT UNIQUE AUTO_INCREMENT,
  data_venda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  valor_total DECIMAL(10, 2) DEFAULT 0,
  quantidade_itens INT DEFAULT 0,
  status VARCHAR(20) DEFAULT 'pendente',
  observacoes TEXT
);
```

**Campos:**
- `id`: Identificador único
- `numero_venda`: Número sequencial da venda
- `data_venda`: Data e hora da venda
- `valor_total`: Total da venda (com desconto)
- `quantidade_itens`: Quantidade total de itens
- `status`: Status da venda (pendente, finalizada, cancelada)
- `observacoes`: Observações da venda

### Tabela: `itens_venda`

```sql
CREATE TABLE itens_venda (
  id INT PRIMARY KEY AUTO_INCREMENT,
  venda_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL,
  preco_unitario DECIMAL(10, 2) NOT NULL,
  subtotal DECIMAL(10, 2) NOT NULL,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (venda_id) REFERENCES vendas(id),
  FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
```

**Campos:**
- `id`: Identificador único
- `venda_id`: Referência para a tabela `vendas`
- `produto_id`: Referência para a tabela `produtos`
- `quantidade`: Quantidade do item
- `preco_unitario`: Preço unitário no momento da venda
- `subtotal`: Quantidade × Preço unitário
- `data_criacao`: Data de criação automática

---

## 🔄 Fluxo de Dados

### Busca de Produtos (AJAX)

```
1. Usuário digita no campo de busca
   ↓
2. JavaScript captura evento 'input'
   ↓
3. Valida se tem pelo menos 2 caracteres
   ↓
4. Envia requisição POST para api_buscar_produtos.php
   ↓
5. PHP executa query com LIKE em 4 campos
   ↓
6. PHP retorna JSON com resultados
   ↓
7. JavaScript exibe resultados em tempo real
   ↓
8. Usuário clica em um produto
   ↓
9. Produto é selecionado e campos são preenchidos
```

### Lançamento de Produto

```
1. Usuário clica em "Lançar Produto"
   ↓
2. JavaScript valida quantidade e preço
   ↓
3. Valida estoque disponível
   ↓
4. Cria objeto com dados do item
   ↓
5. Adiciona item ao array 'itens'
   ↓
6. Atualiza tabela de itens
   ↓
7. Atualiza totais (subtotal, desconto, total)
   ↓
8. Limpa formulário de busca
```

### Finalização de Venda

```
1. Usuário clica em "Finalizar Venda"
   ↓
2. JavaScript valida se há itens
   ↓
3. Coleta todos os itens e desconto
   ↓
4. Envia JSON para api_salvar_venda.php
   ↓
5. PHP inicia transação
   ↓
6. Insere registro em 'vendas'
   ↓
7. Insere itens em 'itens_venda'
   ↓
8. Confirma transação (COMMIT)
   ↓
9. Retorna JSON com ID da venda
   ↓
10. JavaScript exibe mensagem de sucesso
   ↓
11. Limpa formulário após 2 segundos
```

---

## 🔐 Segurança

### Implementações de Segurança

1. **Prepared Statements**: Previne SQL injection
   ```php
   $stmt = $conexao->prepare($query);
   $stmt->bind_param('ssss', $termo, $termo, $termo, $termo);
   ```

2. **Sanitização de Entrada**: Remove caracteres especiais
   ```php
   $entrada = sanitizar($_POST['busca']);
   ```

3. **Transações**: Garante consistência dos dados
   ```php
   $conexao->begin_transaction();
   // ... operações ...
   $conexao->commit();
   ```

4. **Validação no Servidor**: Não confia apenas no cliente
   ```php
   if (empty($dados) || empty($dados['itens'])) {
       retornarJSON(false, 'Nenhum item para salvar');
   }
   ```

5. **Tratamento de Erros**: Não expõe detalhes internos
   ```php
   try {
       // ... código ...
   } catch (Exception $e) {
       retornarJSON(false, 'Erro ao salvar venda');
   }
   ```

### Recomendações Adicionais

- [ ] Implementar autenticação de usuários
- [ ] Usar HTTPS em produção
- [ ] Implementar rate limiting
- [ ] Fazer backups regulares
- [ ] Monitorar logs de acesso
- [ ] Usar firewall de aplicação (WAF)

---

## 🎨 Interface

### Componentes Principais

1. **Header**: Logo e título do sistema
2. **Painel de Busca**: Campo de busca com resultados em dropdown
3. **Painel de Resumo**: Exibe totais da venda
4. **Tabela de Itens**: Lista de produtos adicionados
5. **Alertas**: Mensagens de sucesso, erro e aviso

### Responsividade

- **Desktop**: Layout em 2 colunas
- **Tablet**: Layout em 1 coluna
- **Mobile**: Layout em 1 coluna com botões em coluna

### Cores

- **Primária**: `#667eea` (roxo)
- **Secundária**: `#764ba2` (roxo escuro)
- **Sucesso**: `#66bb6a` (verde)
- **Erro**: `#ef5350` (vermelho)
- **Aviso**: `#ff9800` (laranja)

---

## 📊 Performance

### Otimizações Implementadas

1. **Índices no Banco**: Campos `codigo` e `codigo_barras` são únicos
2. **LIMIT na Busca**: Retorna máximo 10 resultados
3. **Charset UTF-8**: Suporta caracteres especiais
4. **Compressão**: Arquivos CSS/JS inline para reduzir requisições

### Métricas Esperadas

- **Tempo de Busca**: < 100ms
- **Tempo de Lançamento**: < 50ms
- **Tempo de Finalização**: < 500ms
- **Tamanho da Página**: ~150KB (sem cache)

---

## 🔧 Tecnologias Utilizadas

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| PHP | 7.4+ | Backend |
| MySQL | 5.7+ | Banco de dados |
| HTML5 | - | Estrutura |
| CSS3 | - | Estilos |
| JavaScript | ES6+ | Interatividade |
| AJAX | - | Requisições assíncronas |

---

## 📈 Escalabilidade

### Possíveis Melhorias Futuras

1. **Autenticação**: Sistema de login de usuários
2. **Relatórios**: Gráficos de vendas por período
3. **Estoque**: Controle de entrada e saída
4. **Clientes**: Cadastro de clientes e histórico
5. **Cupom Fiscal**: Integração com NFC-e
6. **Múltiplas Lojas**: Suporte a várias filiais
7. **API REST**: Integração com sistemas externos
8. **Cache**: Redis para melhor performance
9. **Logs**: Auditoria de todas as operações
10. **Dashboard**: Painel administrativo

---

## 📞 Suporte

### Documentação

- **README.md**: Documentação completa
- **INSTALACAO.md**: Guia passo a passo
- **RESUMO_TECNICO.md**: Este arquivo

### Contato

- HostGator: https://www.hostgator.com.br/
- PHP: https://www.php.net/
- MySQL: https://dev.mysql.com/

---

## 📝 Histórico de Versões

| Versão | Data | Alterações |
|--------|------|-----------|
| 1.0.0 | 26/12/2025 | Versão inicial |

---

## ✅ Checklist de Desenvolvimento

- [x] Criar estrutura do banco de dados
- [x] Implementar API de busca
- [x] Implementar API de vendas
- [x] Criar interface HTML
- [x] Implementar JavaScript/AJAX
- [x] Adicionar validações
- [x] Implementar transações
- [x] Criar página de testes
- [x] Documentar código
- [x] Criar guia de instalação

---

**Desenvolvido em:** 26 de dezembro de 2025
**Versão:** 1.0.0
**Status:** Pronto para produção
