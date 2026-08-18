# 🚀 GUIA RÁPIDO DE INSTALAÇÃO - SITE CHOPP ON

## 📦 Arquivos Incluídos

Este pacote contém todos os arquivos atualizados do site Chopp ON com as seguintes melhorias:

### ✅ Atualizações Realizadas

1. **Instagram atualizado** para @choppon24h.oficial em todo o site
2. **Formulário completo de franquia** com perguntas detalhadas
3. **Botão "Preencher Formulário"** na seção "Seja um Franqueado"
4. **Painel administrativo** sem senha para visualizar cadastros
5. **Sistema de armazenamento** em JSON com envio de e-mail

---

## 📋 PASSO A PASSO - INSTALAÇÃO VIA FTP

### 1️⃣ FAZER BACKUP DO SITE ATUAL
Antes de qualquer coisa, faça backup dos arquivos atuais do seu site!

### 2️⃣ CONECTAR VIA FTP
Use um cliente FTP (FileZilla, WinSCP, etc.) e conecte ao seu HostGator:
- **Host:** ftp.choppon24h.com.br (ou o fornecido pelo HostGator)
- **Usuário:** seu_usuario_ftp
- **Senha:** sua_senha_ftp
- **Porta:** 21

### 3️⃣ NAVEGAR ATÉ O DIRETÓRIO PUBLIC_HTML
No servidor, navegue até: `/public_html/`

### 4️⃣ FAZER UPLOAD DOS ARQUIVOS

**Opção A - Upload do ZIP (Recomendado):**
1. Faça upload do arquivo `choppon_site_atualizado.zip`
2. No cPanel do HostGator, vá em "Gerenciador de Arquivos"
3. Navegue até `/public_html/`
4. Clique com botão direito no arquivo ZIP
5. Selecione "Extrair"
6. Mova os arquivos da pasta `choppon_updated` para `/public_html/`

**Opção B - Upload Direto:**
1. Extraia o ZIP no seu computador
2. Entre na pasta `choppon_updated`
3. Selecione TODOS os arquivos e pastas
4. Arraste para `/public_html/` no FTP
5. Confirme a substituição dos arquivos existentes

### 5️⃣ CONFIGURAR PERMISSÕES

Via FTP ou cPanel, configure as permissões:

| Arquivo/Pasta | Permissão | Como fazer no FileZilla |
|---------------|-----------|-------------------------|
| `franchise_data.json` | 666 | Botão direito → Permissões → 666 |
| `process-franchise.php` | 644 | Botão direito → Permissões → 644 |
| `admin/` (pasta) | 755 | Botão direito → Permissões → 755 |
| `admin/get-franchise-data.php` | 644 | Botão direito → Permissões → 644 |

### 6️⃣ CONFIGURAR E-MAIL DE DESTINO

1. Abra o arquivo `process-franchise.php` em um editor de texto
2. Na linha 5, altere:
   ```php
   $email_to = 'contato@choppon24h.com.br';
   ```
   Para o e-mail que deve receber os cadastros
3. Salve e faça upload novamente

### 7️⃣ TESTAR O SITE

✅ **Teste 1 - Site Principal:**
- Acesse: https://www.choppon24h.com.br
- Verifique se o Instagram mostra @choppon24h.oficial
- Clique em "Seja Franqueado" e veja se aparece o botão "Preencher Formulário"

✅ **Teste 2 - Formulário:**
- Acesse: https://www.choppon24h.com.br/franquia-form.html
- Preencha um cadastro de teste
- Verifique se recebeu o e-mail

✅ **Teste 3 - Painel Admin:**
- Acesse: https://www.choppon24h.com.br/admin/painel-franquias.html
- Verifique se o cadastro de teste aparece

---

## 🔐 ACESSOS

### Painel Administrativo
- **URL:** https://www.choppon24h.com.br/admin/painel-franquias.html
- **Senha:** Não requer senha (acesso direto)
- **Recomendação:** Guarde este link em local seguro

### Formulário de Franquia
- **URL:** https://www.choppon24h.com.br/franquia-form.html
- **Acesso:** Público (qualquer pessoa pode preencher)

---

## 📊 ESTRUTURA DE ARQUIVOS

```
public_html/
├── index.html                    ← Página principal (ATUALIZADO)
├── franquia-form.html           ← Formulário de franquia (NOVO)
├── process-franchise.php        ← Processa formulário (NOVO)
├── franchise_data.json          ← Armazena cadastros (NOVO)
├── .htaccess                    ← Segurança (NOVO)
├── README.md                    ← Documentação técnica (NOVO)
├── admin/
│   ├── painel-franquias.html   ← Painel admin (NOVO)
│   └── get-franchise-data.php  ← API dados (NOVO)
├── assets/
│   └── img/                     ← Imagens (existentes)
└── site/                        ← Outros arquivos (existentes)
```

---

## ❓ SOLUÇÃO DE PROBLEMAS

### Problema: Formulário não envia
**Solução:**
1. Verifique se o PHP está habilitado no servidor
2. Confirme as permissões do arquivo `franchise_data.json` (666)
3. Verifique se o arquivo `process-franchise.php` tem permissão 644

### Problema: E-mail não chega
**Solução:**
1. Verifique a pasta de SPAM
2. Confirme se o e-mail em `process-franchise.php` está correto
3. Entre em contato com o suporte do HostGator para verificar função `mail()` do PHP

### Problema: Painel admin não mostra dados
**Solução:**
1. Verifique se há cadastros em `franchise_data.json`
2. Confirme que `admin/get-franchise-data.php` tem permissão 644
3. Abra o console do navegador (F12) e veja se há erros

### Problema: Erro 500 ao acessar páginas
**Solução:**
1. Verifique se o arquivo `.htaccess` foi enviado corretamente
2. Renomeie temporariamente `.htaccess` para `.htaccess.bak` e teste
3. Verifique os logs de erro no cPanel

---

## 📞 SUPORTE TÉCNICO

Se precisar de ajuda:
1. **HostGator:** Suporte técnico para questões de servidor
2. **Logs de Erro:** Acesse via cPanel → Logs de Erro
3. **Teste PHP:** Crie um arquivo `test.php` com `<?php phpinfo(); ?>` para verificar configurações

---

## 🎯 PRÓXIMOS PASSOS

Após a instalação:
1. ✅ Teste todos os links do site
2. ✅ Faça um cadastro de teste no formulário
3. ✅ Acesse o painel admin e verifique os dados
4. ✅ Configure alertas de e-mail
5. ✅ Divulgue o novo formulário nas redes sociais

---

## 📝 NOTAS IMPORTANTES

- O arquivo `franchise_data.json` armazena TODOS os cadastros
- Faça backup regular deste arquivo
- O painel admin atualiza automaticamente a cada 30 segundos
- Todos os dados são protegidos via .htaccess
- O formulário valida os dados antes de enviar

---

**Desenvolvido para Chopp ON**
Data: Dezembro 2024
