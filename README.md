# Site Chopp ON - Atualização com Formulário de Franquia

## Arquivos Atualizados

### Novos Arquivos
- `franquia-form.html` - Formulário completo de cadastro de franqueados
- `process-franchise.php` - Script PHP para processar o formulário
- `franchise_data.json` - Arquivo JSON para armazenar os cadastros
- `admin/painel-franquias.html` - Painel administrativo para visualizar cadastros
- `admin/get-franchise-data.php` - API para retornar dados ao painel
- `.htaccess` - Configurações de segurança e proteção de arquivos

### Arquivos Modificados
- `index.html` - Atualizado com:
  - Botão "Preencher Formulário" na seção de franquia
  - Instagram alterado para @choppon24h.oficial em todas as ocorrências

## Instalação via FTP

### 1. Fazer Upload dos Arquivos
Faça upload de todos os arquivos para o diretório raiz do seu site no HostGator via FTP:

```
/public_html/
├── index.html (atualizado)
├── franquia-form.html (novo)
├── process-franchise.php (novo)
├── franchise_data.json (novo)
├── .htaccess (novo)
├── admin/
│   ├── painel-franquias.html (novo)
│   └── get-franchise-data.php (novo)
├── assets/
│   └── img/
└── site/
```

### 2. Configurar Permissões
Após o upload, configure as permissões dos arquivos via FTP ou cPanel:

- `franchise_data.json` - Permissão 666 (leitura e escrita)
- `process-franchise.php` - Permissão 644
- `admin/get-franchise-data.php` - Permissão 644
- Diretório `admin/` - Permissão 755

### 3. Configurar E-mail
Edite o arquivo `process-franchise.php` e altere a linha:

```php
$email_to = 'contato@choppon24h.com.br';
```

Para o e-mail que deve receber as notificações de novos cadastros.

### 4. Testar o Formulário
1. Acesse: `https://www.choppon24h.com.br/franquia-form.html`
2. Preencha o formulário de teste
3. Verifique se o e-mail foi recebido
4. Acesse o painel: `https://www.choppon24h.com.br/admin/painel-franquias.html`

## Funcionalidades

### Formulário de Franquia
O formulário coleta as seguintes informações:

**Dados Pessoais:**
- Nome completo
- E-mail
- Telefone/WhatsApp
- Idade
- CPF
- Cidade e Estado onde mora

**Perfil Profissional:**
- Resumo do perfil
- Experiências anteriores
- Formação acadêmica

**Experiência em Negócios:**
- Motivo para investir em bebidas
- Experiência no mercado de bebidas
- Experiência com franquias
- Empresas que possui (nome e CNPJ)

**Interesse na Franquia:**
- Cidade e estado de interesse
- Bairro/região
- Tipo de local (shopping, supermercado, etc.)
- Se já possui ponto comercial

**Capacidade de Investimento:**
- Faixa de capital disponível
- Prazo para implantação
- Dedicação ao negócio

**Informações Adicionais:**
- Como conheceu a Chopp ON
- Expectativas com a franquia
- Observações

### Painel Administrativo
Acesse: `https://www.choppon24h.com.br/admin/painel-franquias.html`

**Características:**
- Sem necessidade de senha (acesso direto)
- Estatísticas em tempo real
- Filtros por estado, capital e nome
- Ordenação por data ou nome
- Visualização completa de todos os dados
- Atualização automática a cada 30 segundos

### Segurança
- Arquivo JSON protegido via .htaccess
- Validação de dados no frontend
- Aceite de LGPD obrigatório
- Headers de segurança configurados

## Suporte

Para dúvidas ou problemas:
1. Verifique se o PHP está habilitado no servidor
2. Confirme que as permissões estão corretas
3. Verifique os logs de erro do servidor
4. Teste o envio de e-mail via PHP no HostGator

## Atualizações Realizadas

✅ Formulário completo de franquia criado
✅ Instagram alterado para @choppon24h.oficial
✅ Botão "Preencher Formulário" adicionado na seção de franquia
✅ Painel administrativo sem senha criado
✅ Sistema de armazenamento em JSON implementado
✅ Envio de e-mail configurado
✅ Proteção de arquivos sensíveis via .htaccess
