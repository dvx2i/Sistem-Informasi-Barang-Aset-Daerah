# Codeigniter-Breadcrumb-Helper

## Esse helper para Codeigniter cria Breadcrumb de forma dinâmica à partir de segmentos URI.

### Modo de uso:

Baixe o arquivo `breadcrumb_helper.php` em `application/helpers`.

Inclua o helper nas configurações de autoload da applicação: `$autoload['helpers'] = array('breadcrumb', '...', '...')`.

Você deve informar o Breadcrumb inicial e seu link de destino. Exemplo:

`<?php echo create_breadcrumb('home', base_url()); ?>`