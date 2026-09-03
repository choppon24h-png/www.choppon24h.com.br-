# Prompt de publicação e manutenção — Chopp ON

Copie a instrução abaixo para o agente que terá acesso à HostGator/cPanel. Ela foi preparada para evitar que `choppon24h.com.br` volte a servir a pasta legada `chopp/` em vez do novo redesign.

```text
Atue como administrador técnico de cPanel/HostGator. Publique o pacote estático do novo site Chopp ON no domínio choppon24h.com.br, sem alterar ou apagar o backup do site antigo antes da validação.

Contexto: o domínio já serviu a página legada da pasta /chopp/ e também retornou erro 403 quando o arquivo inicial foi colocado em uma pasta diferente da raiz de documentos ativa. O novo pacote é estático e contém index.html, assets/, franquia/, delivery/, aplicativo/, franqueado/, robots.txt, sitemap.xml e .htaccess. Não é permitido publicar diretórios de desenvolvimento como client/, server/, drizzle/, shared/, docs/, package.json, pnpm-lock.yaml ou vite.config.ts na área pública.

1. Em cPanel > Domains, localize choppon24h.com.br e registre o Document Root EXATO. Não assuma o caminho; use a pasta indicada pelo cPanel.
2. Crie um backup datado do conteúdo atual desse Document Root em uma pasta fora da raiz pública, por exemplo: ~/backup-choppon-AAAAMMDD-HHMM/.
3. Faça upload do ZIP chopp-on-hostgator-static.zip e extraia-o primeiro em uma pasta temporária fora da área pública.
4. Mova APENAS o conteúdo interno gerado do pacote para o Document Root real: index.html, assets/, franquia/, delivery/, aplicativo/, franqueado/, robots.txt, sitemap.xml, README-HOSTGATOR.txt e .htaccess.
5. O arquivo index.html precisa ficar diretamente no Document Root — nunca dentro de /chopp/, /monitor-chopp-on/ ou outra subpasta.
6. Confirme permissões: diretórios 0755; arquivos HTML, CSS, JS, XML, TXT e imagens 0644. O proprietário deve ser o usuário da hospedagem.
7. Mantenha o .htaccess do pacote. Ele desativa listagem de diretórios, redireciona /chopp/ para /delivery/ e impede exposição de caminhos de desenvolvimento.
8. Verifique e remova qualquer regra antiga que redirecione / para /chopp/ ou force um DirectoryIndex inexistente. Não deixe dois arquivos .htaccess concorrentes na mesma raiz.
9. Teste, em aba anônima e com curl/inspeção HTTP, obrigatoriamente:
   - https://choppon24h.com.br/ => 200
   - https://www.choppon24h.com.br/ => 200 ou 301 para a versão canônica
   - https://choppon24h.com.br/index.html => 301 para /
   - https://choppon24h.com.br/delivery/ => 200
   - https://choppon24h.com.br/chopp/ => 301 para /delivery/
   - https://choppon24h.com.br/franquia/ => 200
   - https://choppon24h.com.br/aplicativo/ => 200
   - https://choppon24h.com.br/client/ => 403 ou 404
   - https://choppon24h.com.br/package.json => 403 ou 404
10. Só após todos os testes retornarem corretamente, mantenha o backup antigo por 7 dias e informe: Document Root real, arquivos publicados, regras .htaccess ativas, permissões aplicadas e status HTTP das URLs de validação.

Limitação conhecida: a HostGator compartilhada serve esta distribuição estática. O formulário de franquias encaminha o cliente para o WhatsApp; o armazenamento seguro de leads, a área administrativa e integrações de backend exigem posteriormente uma hospedagem Node/SSR ou a publicação no ambiente gerenciado do projeto.
```
