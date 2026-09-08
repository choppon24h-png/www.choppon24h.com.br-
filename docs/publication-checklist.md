# Checklist de publicação — Chopp ON

## Segurança e dados

- Rotacionar toda credencial anteriormente exposta e removê-la do histórico público antes da migração.
- Manter instaladores, painéis, APIs internas, arquivos JSON, documentação operacional e backups fora do diretório público.
- Confirmar que apenas a rota administrativa autenticada consulta leads e que o banco não é acessível pelo navegador.

## Domínio e redirecionamentos

- Definir `https://www.choppon24h.com.br` como URL canônica na variável `CANONICAL_ORIGIN`.
- Configurar 301 de HTTP para HTTPS e de `choppon24h.com.br` para `www.choppon24h.com.br` no provedor de hospedagem.
- Configurar 301 de `/index.html`, `/indexold.html` e `/chopp/` para as novas rotas escolhidas: `/` ou `/delivery` conforme a intenção original.
- Apontar o domínio atual para a implantação somente após validar o preview e o certificado HTTPS.

## SEO e mídia

- Conferir `robots.txt`, `sitemap.xml`, canonicals, títulos e previews Open Graph no domínio final.
- Verificar a propriedade no Search Console e enviar o sitemap final.
- Inserir os IDs reais de GA4, Google Ads e Meta Pixel apenas após a definição de consentimento e política de privacidade.
- Validar eventos de WhatsApp, início de formulário e sucesso de cadastro em modo de teste antes de ativar campanhas.
