# Site Chopp ON — Monitor CHOPP ON

O conteúdo deste repositorio foi reconstruido a partir do ZIP fornecido para manter o site institucional existente e acrescentar uma area publica de suporte para tablets.

## Estrutura do Monitor

A pasta `monitor CHOPP ON/` guarda o `APK01.apk`, o manifesto `version.json`, o `.htaccess` e a documentacao. A URL amigavel `monitor-chopp-on/` apresenta a tela responsiva de suporte e aponta o download para o APK armazenado na pasta oficial.

O rodape do `index.html` recebeu o botao `Suporte`, que abre a tela `monitor-chopp-on/`. O menu principal tambem possui o acesso `Monitor CHOPP ON`.

## Seguranca

O arquivo original `caixa/config.php` continha uma credencial de banco e nao foi publicado. Em seu lugar existe `caixa/config.example.php`; no servidor, crie o `config.php` localmente e mantenha-o fora do Git. Tambem foram excluidos arquivos de runtime e metadados de FTP.

## Instalacao no servidor

Envie a arvore do repositorio para o document root do site, preservando a pasta com o espaco no nome. Confirme que `monitor CHOPP ON/APK01.apk` foi enviado em modo binario e que o Apache permite `application/vnd.android.package-archive`. A URL amigavel e `https://www.choppon24h.com.br/monitor-chopp-on/`; a URL direta da pasta oficial e `https://www.choppon24h.com.br/monitor%20CHOPP%20ON/`.

O checksum SHA-256 do APK01 desta versao e `c2e10bda227cdae0f3df4b54c6e33e62d0b6fe45a90a1094a87e4ba463476c60`.
