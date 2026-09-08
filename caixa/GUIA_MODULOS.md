# 📚 Guia dos Módulos - Sistema de Caixa ERP Dist

## 🎯 Visão Geral

O Sistema de Caixa ERP Dist possui **3 módulos principais** integrados com menu de navegação:

| Módulo | Arquivo | Descrição |
|--------|---------|-----------|
| **Caixa** | `caixa.html` | Gerenciamento de vendas em tempo real |
| **Produtos** | `produtos.html` | Cadastro e gerenciamento de produtos |
| **Estoque** | `estoque.html` | Controle e monitoramento de estoque |

---

## 🛒 MÓDULO 1: CAIXA

### O Que É?

Tela principal para realizar vendas. Permite buscar produtos, adicionar à venda e finalizar.

### Funcionalidades

✅ **Busca em Tempo Real (AJAX)**
- Busque por nome, código, código de barras ou grupo
- Resultados aparecem conforme digita
- Clique no resultado para selecionar

✅ **Lançamento de Produtos**
- Ajuste quantidade e preço
- Validação automática de estoque
- Clique em "Lançar Produto" para adicionar

✅ **Tabela Dinâmica**
- Edite quantidade de cada item
- Edite preço unitário
- Remova itens com um clique

✅ **Cálculo Automático**
- Subtotal é calculado automaticamente
- Desconto pode ser adicionado
- Total é atualizado em tempo real

✅ **Finalização de Venda**
- Adicione observações (opcional)
- Clique em "Finalizar Venda"
- Venda é salva no banco com transação

### Como Usar

1. **Acessar**: `https://seu-dominio.com/caixa.html`
2. **Buscar**: Digite no campo de busca
3. **Selecionar**: Clique no produto desejado
4. **Ajustar**: Modifique quantidade e preço se necessário
5. **Lançar**: Clique em "Lançar Produto"
6. **Repetir**: Adicione mais produtos se desejar
7. **Finalizar**: Clique em "Finalizar Venda"

### APIs Utilizadas

- `api_buscar_produtos.php` - Busca em tempo real
- `api_salvar_venda.php` - Salva a venda no banco

---

## 📦 MÓDULO 2: PRODUTOS

### O Que É?

Sistema completo de cadastro e gerenciamento de produtos com CRUD (Criar, Ler, Atualizar, Deletar).

### Funcionalidades

✅ **Cadastro de Produtos**
- Nome, código, código de barras
- Grupo/categoria
- Preço e estoque inicial
- Status (ativo/inativo)

✅ **Busca e Filtros**
- Busque por nome ou código
- Filtre por grupo
- Resultados em tempo real

✅ **Edição de Produtos**
- Edite nome, preço e estoque
- Altere status (ativo/inativo)
- Modal intuitivo para edição

✅ **Deleção de Produtos**
- Delete produtos sem vendas associadas
- Confirmação antes de deletar
- Proteção contra exclusão acidental

✅ **Resumo de Produtos**
- Total de produtos cadastrados
- Quantidade de ativos/inativos
- Preço médio
- Estatísticas em tempo real

### Como Usar

#### Cadastrar Novo Produto

1. **Acessar**: `https://seu-dominio.com/produtos.html`
2. **Preencher Formulário**:
   - Nome: ex. "Notebook Dell"
   - Código: ex. "PROD001"
   - Código de Barras: ex. "1234567890123"
   - Grupo: selecione uma categoria
   - Preço: ex. "2500.00"
   - Estoque: ex. "10"
   - Ativo: marque se deseja ativar
3. **Salvar**: Clique em "Salvar"
4. **Confirmação**: Mensagem de sucesso aparecerá

#### Editar Produto

1. **Localizar**: Use a busca ou filtros
2. **Clicar**: Clique em "✏️ Editar" na linha do produto
3. **Modificar**: Altere os dados desejados
4. **Salvar**: Clique em "Salvar"

#### Deletar Produto

1. **Localizar**: Use a busca ou filtros
2. **Clicar**: Clique em "🗑️ Deletar" na linha do produto
3. **Confirmar**: Clique em "OK" na confirmação
4. **Pronto**: Produto será deletado

### Campos Obrigatórios

- ✓ Nome (mínimo 3 caracteres)
- ✓ Código (único)
- ✓ Código de Barras (único)
- ✓ Grupo (categoria)
- ✓ Preço (não pode ser negativo)
- ✓ Estoque (não pode ser negativo)

### APIs Utilizadas

- `api_produtos.php` - CRUD completo de produtos
- `api_produtos.php?acao=listar_grupos` - Lista grupos

---

## 📊 MÓDULO 3: ESTOQUE

### O Que É?

Sistema de monitoramento e controle de estoque com alertas de estoque baixo e crítico.

### Funcionalidades

✅ **Monitoramento de Estoque**
- Visualize quantidade de cada produto
- Valor total do estoque
- Status de estoque (crítico, baixo, normal, alto)

✅ **Alertas Automáticos**
- 🔴 Crítico: 0 unidades
- 🟠 Baixo: ≤ 10 unidades
- 🟢 Normal: 11-50 unidades
- 🔵 Alto: > 50 unidades

✅ **Ajuste de Estoque**
- Entrada: adicione quantidade
- Saída: remova quantidade
- Validação automática

✅ **Atualização Direta**
- Defina o estoque para um valor específico
- Útil para inventário

✅ **Filtros Avançados**
- Busque por nome ou código
- Filtre por grupo
- Filtre por status de estoque

✅ **Relatório de Estoque**
- Resumo por grupo
- Total de produtos e unidades
- Valor total do estoque
- Preço médio por grupo

### Como Usar

#### Visualizar Estoque

1. **Acessar**: `https://seu-dominio.com/estoque.html`
2. **Ver Resumo**: Na parte superior, você verá:
   - Produtos críticos (0 un.)
   - Estoque baixo (≤10 un.)
   - Estoque normal
   - Estoque alto (>50 un.)
   - Valor total do estoque

#### Ajustar Estoque (Entrada/Saída)

1. **Localizar**: Use a busca ou filtros
2. **Clicar**: Clique em "📦 Ajustar" na linha do produto
3. **Selecionar Tipo**:
   - Entrada (adicionar quantidade)
   - Saída (remover quantidade)
4. **Informar Quantidade**: Digite a quantidade
5. **Confirmar**: Clique em "Confirmar"

#### Atualizar Estoque (Valor Direto)

1. **Localizar**: Use a busca ou filtros
2. **Clicar**: Clique em "✏️ Atualizar" na linha do produto
3. **Novo Valor**: Digite o novo valor de estoque
4. **Confirmar**: Clique em "Confirmar"

#### Gerar Relatório

1. **Clicar**: Clique em "📊 Relatório"
2. **Ver Dados**: Relatório mostrará:
   - Total de grupos
   - Total de produtos
   - Total de unidades
   - Valor total do estoque
   - Dados por grupo

#### Usar Filtros

1. **Busca**: Digite nome ou código
2. **Grupo**: Selecione uma categoria
3. **Status**: Filtre por:
   - Crítico (0 un.)
   - Baixo (≤10 un.)
   - Alto (>50 un.)

### Status de Estoque

| Status | Cor | Quantidade | Ação Recomendada |
|--------|-----|-----------|------------------|
| Crítico | 🔴 Vermelho | 0 un. | Reabasteça imediatamente |
| Baixo | 🟠 Laranja | ≤ 10 un. | Considere reabastecer |
| Normal | 🟢 Verde | 11-50 un. | Estoque adequado |
| Alto | 🔵 Azul | > 50 un. | Estoque elevado |

### APIs Utilizadas

- `api_estoque.php` - Gerenciamento de estoque
- `api_estoque.php?acao=relatorio` - Relatório de estoque

---

## 🔄 Fluxo de Trabalho Típico

### Cenário 1: Vender um Produto

1. **Caixa** → Buscar produto
2. **Caixa** → Lançar produto
3. **Caixa** → Finalizar venda
4. **Estoque** → Verificar se estoque foi atualizado

### Cenário 2: Receber Mercadoria

1. **Produtos** → Verificar se produto existe
2. **Estoque** → Ajustar estoque (entrada)
3. **Estoque** → Verificar se status foi atualizado

### Cenário 3: Fazer Inventário

1. **Estoque** → Gerar relatório
2. **Estoque** → Comparar com valores esperados
3. **Estoque** → Atualizar valores se necessário

### Cenário 4: Gerenciar Catálogo

1. **Produtos** → Cadastrar novo produto
2. **Estoque** → Verificar novo produto
3. **Caixa** → Buscar e vender novo produto

---

## 📱 Responsividade

Todos os módulos são **100% responsivos**:

- ✅ Desktop (1920x1080+)
- ✅ Tablet (768x1024)
- ✅ Mobile (320x568)

### Dicas para Mobile

- Use a busca para localizar produtos rapidamente
- Os botões de ação são grandes e fáceis de clicar
- Tabelas são horizontalmente scrolláveis
- Modais se adaptam ao tamanho da tela

---

## 🔐 Segurança

### Validações Implementadas

✅ **No Cliente (JavaScript)**
- Validação de campos obrigatórios
- Validação de tipos de dados
- Confirmação antes de deletar

✅ **No Servidor (PHP)**
- Prepared statements (previne SQL injection)
- Sanitização de entrada
- Validação de tipos
- Verificação de permissões

### Proteções

- Código de barras único
- Código de produto único
- Não permite deletar produtos com vendas
- Não permite estoque negativo
- Transações no banco de dados

---

## 📊 Dados de Exemplo

O sistema vem com dados de exemplo:

### Grupos

1. Eletrônicos
2. Alimentos
3. Vestuário
4. Higiene

### Produtos

| Código | Nome | Grupo | Preço | Estoque |
|--------|------|-------|-------|---------|
| PROD001 | Notebook Dell | Eletrônicos | R$ 2.500,00 | 10 |
| PROD002 | Mouse Logitech | Eletrônicos | R$ 85,00 | 50 |
| PROD003 | Teclado Mecânico | Eletrônicos | R$ 350,00 | 25 |
| PROD004 | Arroz Integral 5kg | Alimentos | R$ 25,00 | 100 |
| PROD005 | Feijão Carioca 1kg | Alimentos | R$ 8,50 | 80 |
| PROD006 | Camiseta Básica | Vestuário | R$ 45,00 | 60 |
| PROD007 | Calça Jeans | Vestuário | R$ 120,00 | 40 |
| PROD008 | Sabonete Neutro | Higiene | R$ 5,00 | 200 |
| PROD009 | Detergente Neutro | Higiene | R$ 3,50 | 150 |

---

## ⚙️ Configuração

### Alterar Grupos

1. Acesse **phpMyAdmin** no cPanel
2. Vá para tabela `grupos`
3. Adicione, edite ou delete grupos

### Alterar Produtos Padrão

1. Acesse **phpMyAdmin** no cPanel
2. Vá para tabela `produtos`
3. Edite os produtos conforme necessário

### Alterar Preços

1. **Módulo Produtos** → Edite o produto
2. Altere o preço
3. Salve as alterações

---

## 🎓 Dicas Úteis

### Para Iniciantes

1. Comece pelo módulo **Produtos** para entender o cadastro
2. Depois vá para **Estoque** para monitorar
3. Por fim, use **Caixa** para fazer vendas

### Para Gerentes

1. Use **Estoque** regularmente para monitorar
2. Gere **Relatórios** para análise
3. Identifique produtos com estoque crítico

### Para Operadores

1. Use **Caixa** para vendas rápidas
2. Busque produtos pela busca AJAX
3. Finalize vendas com desconto se necessário

---

## 🆘 Solução de Problemas

### "Produto não aparece na busca"

- Verifique se o produto está ativo
- Tente buscar pelo código em vez do nome
- Verifique se digitou corretamente

### "Não consigo deletar um produto"

- Verifique se há vendas associadas
- Se houver vendas, não é possível deletar
- Desative o produto em vez de deletar

### "Estoque não está atualizando"

- Verifique se a venda foi finalizada
- Recarregue a página
- Verifique os logs de erro (F12)

### "Relatório não mostra dados"

- Verifique se há produtos cadastrados
- Verifique se os produtos estão ativos
- Tente gerar novamente

---

## 📞 Suporte

Para problemas ou dúvidas:

1. Consulte a documentação incluída
2. Verifique os logs do servidor
3. Entre em contato com o suporte do HostGator

---

**Versão:** 1.0.0
**Data:** 27 de dezembro de 2025
**Status:** Pronto para uso
