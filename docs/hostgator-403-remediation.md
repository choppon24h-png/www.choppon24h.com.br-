# Recuperação do domínio na HostGator

Em 27 de agosto de 2026, `https://choppon24h.com.br/` e `https://www.choppon24h.com.br/` retornavam **HTTP 403** pela HostGator.

## Causa identificada

A raiz pública do domínio continha o código-fonte do projeto fullstack, incluindo diretórios como `client`, `server` e `drizzle`, mas não possuía um `index.html` no nível raiz. A hospedagem compartilhada Apache não executa essa aplicação Node.js e bloqueou a listagem do diretório sem documento inicial.

## Ação de recuperação executada

- O `index.html` preservado em `/choppon24h.com.br/old/index.html` foi copiado para `/choppon24h.com.br/index.html`.
- A pasta de recursos preservada em `/choppon24h.com.br/old/assets` foi copiada para `/choppon24h.com.br/assets`.
- As duas variações públicas do domínio voltaram a responder HTTP 200 e a página passou a carregar com os recursos visuais.
- Foi criada a pasta privada `/chopp-on-redesign-private` na conta de hospedagem para preservar o código-fonte fora da raiz pública.
- Os diretórios `client` e `server` do novo projeto foram movidos para a pasta privada, removendo a interface e a lógica de aplicação da área pública.
- Os diretórios `old`, `drizzle`, `patches` e `shared` também foram movidos para a pasta privada. A verificação pública confirmou resposta 200 para a Home e 404 para `/old/`, `/client/`, `/server/`, `/drizzle/`, `/patches/` e `/shared/`.
- Arquivos de configuração remanescentes na raiz pública, como `package.json`, `pnpm-lock.yaml`, `vite.config.ts`, `tsconfig.json`, `todo.md` e `components.json`, ainda responderam 200 e devem ser bloqueados por regra de servidor ou movidos antes da próxima publicação.

## Próxima ação recomendada

Concluir a remoção dos diretórios auxiliares e arquivos de configuração do novo projeto da raiz pública, mantendo na raiz apenas arquivos estáticos compatíveis com Apache. Para publicar o redesign fullstack com formulário seguro e banco de dados, usar a hospedagem gerenciada do projeto ou um ambiente Node.js compatível antes de apontar o domínio.
