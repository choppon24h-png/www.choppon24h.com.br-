<?php
/**
 * INSTALADOR AUTOMÁTICO - Sistema de Caixa ERP Dist
 * 
 * Este arquivo automatiza toda a instalação do sistema
 * Acesse: https://seu-dominio.com/installer.php
 */

session_start();

// Definir timezone
date_default_timezone_set('America/Sao_Paulo');

// Classe do Instalador
class InstaladorSistemaCaixa {
    
    private $etapa = 1;
    private $erros = [];
    private $sucessos = [];
    private $avisos = [];
    private $conexao = null;
    
    // Configurações padrão
    private $config = [
        'db_host' => 'localhost',
        'db_user' => 'inlaud99_admin',
        'db_pass' => 'Admin259087@',
        'db_name' => 'inlaud99_erpdist',
        'db_charset' => 'utf8mb4'
    ];
    
    public function __construct() {
        // Obter etapa da sessão
        if (isset($_SESSION['etapa'])) {
            $this->etapa = $_SESSION['etapa'];
        }
        
        // Processar formulários
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processarFormulario();
        }
    }
    
    /**
     * Processar formulários enviados
     */
    private function processarFormulario() {
        $acao = isset($_POST['acao']) ? $_POST['acao'] : '';
        
        switch ($acao) {
            case 'verificar_requisitos':
                $this->etapa = 1;
                break;
                
            case 'configurar_banco':
                $this->etapa = 2;
                $this->salvarConfiguracao();
                break;
                
            case 'criar_banco':
                $this->etapa = 3;
                $this->criarBancoDados();
                break;
                
            case 'criar_tabelas':
                $this->etapa = 4;
                $this->criarTabelas();
                break;
                
            case 'inserir_dados':
                $this->etapa = 5;
                $this->inserirDadosExemplo();
                break;
                
            case 'testar_conexao':
                $this->etapa = 6;
                $this->testarConexao();
                break;
                
            case 'finalizar':
                $this->etapa = 7;
                $this->finalizarInstalacao();
                break;
        }
        
        $_SESSION['etapa'] = $this->etapa;
    }
    
    /**
     * Salvar configuração do banco de dados
     */
    private function salvarConfiguracao() {
        if (isset($_POST['db_host'])) {
            $this->config['db_host'] = sanitizar($_POST['db_host']);
        }
        if (isset($_POST['db_user'])) {
            $this->config['db_user'] = sanitizar($_POST['db_user']);
        }
        if (isset($_POST['db_pass'])) {
            $this->config['db_pass'] = $_POST['db_pass']; // Não sanitizar senha
        }
        if (isset($_POST['db_name'])) {
            $this->config['db_name'] = sanitizar($_POST['db_name']);
        }
        
        $_SESSION['config'] = $this->config;
        
        // Tentar conectar com as credenciais fornecidas
        $this->testarConexaoBanco();
    }
    
    /**
     * Testar conexão com banco de dados
     */
    private function testarConexaoBanco() {
        $conexao = @new mysqli(
            $this->config['db_host'],
            $this->config['db_user'],
            $this->config['db_pass']
        );
        
        if ($conexao->connect_error) {
            $this->erros[] = 'Erro ao conectar ao banco: ' . htmlspecialchars($conexao->connect_error);
            return false;
        }
        
        $this->sucessos[] = 'Conexão com banco de dados estabelecida com sucesso!';
        $this->conexao = $conexao;
        return true;
    }
    
    /**
     * Criar banco de dados
     */
    private function criarBancoDados() {
        if (!$this->testarConexaoBanco()) {
            return;
        }
        
        $db_name = $this->config['db_name'];
        $charset = $this->config['db_charset'];
        
        // Criar banco de dados
        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET $charset COLLATE ${charset}_unicode_ci";
        
        if ($this->conexao->query($sql)) {
            $this->sucessos[] = "Banco de dados '$db_name' criado com sucesso!";
        } else {
            $this->erros[] = 'Erro ao criar banco de dados: ' . htmlspecialchars($this->conexao->error);
            return;
        }
        
        // Selecionar banco de dados
        if (!$this->conexao->select_db($db_name)) {
            $this->erros[] = 'Erro ao selecionar banco de dados: ' . htmlspecialchars($this->conexao->error);
            return;
        }
        
        $this->sucessos[] = "Banco de dados selecionado com sucesso!";
    }
    
    /**
     * Criar tabelas do banco de dados
     */
    private function criarTabelas() {
        if (!$this->conectarAoBanco()) {
            return;
        }
        
        $tabelas = [
            'grupos' => "
                CREATE TABLE IF NOT EXISTS grupos (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    nome VARCHAR(100) NOT NULL UNIQUE,
                    descricao TEXT,
                    ativo TINYINT(1) DEFAULT 1,
                    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'produtos' => "
                CREATE TABLE IF NOT EXISTS produtos (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    nome VARCHAR(150) NOT NULL,
                    codigo VARCHAR(50) UNIQUE,
                    codigo_barras VARCHAR(50) UNIQUE,
                    grupo_id INT NOT NULL,
                    preco DECIMAL(10, 2) NOT NULL,
                    estoque INT DEFAULT 0,
                    ativo TINYINT(1) DEFAULT 1,
                    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE RESTRICT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'vendas' => "
                CREATE TABLE IF NOT EXISTS vendas (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    numero_venda INT UNIQUE AUTO_INCREMENT,
                    data_venda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    valor_total DECIMAL(10, 2) DEFAULT 0,
                    quantidade_itens INT DEFAULT 0,
                    status VARCHAR(20) DEFAULT 'pendente',
                    observacoes TEXT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'itens_venda' => "
                CREATE TABLE IF NOT EXISTS itens_venda (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    venda_id INT NOT NULL,
                    produto_id INT NOT NULL,
                    quantidade INT NOT NULL,
                    preco_unitario DECIMAL(10, 2) NOT NULL,
                    subtotal DECIMAL(10, 2) NOT NULL,
                    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (venda_id) REFERENCES vendas(id) ON DELETE CASCADE,
                    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            "
        ];
        
        foreach ($tabelas as $nome => $sql) {
            if ($this->conexao->query($sql)) {
                $this->sucessos[] = "Tabela '$nome' criada com sucesso!";
            } else {
                $this->erros[] = "Erro ao criar tabela '$nome': " . htmlspecialchars($this->conexao->error);
            }
        }
    }
    
    /**
     * Inserir dados de exemplo
     */
    private function inserirDadosExemplo() {
        if (!$this->conectarAoBanco()) {
            return;
        }
        
        // Verificar se já existem dados
        $resultado = $this->conexao->query("SELECT COUNT(*) as total FROM grupos");
        $linha = $resultado->fetch_assoc();
        
        if ($linha['total'] > 0) {
            $this->avisos[] = 'Dados de exemplo já existem no banco. Pulando inserção.';
            return;
        }
        
        // Inserir grupos
        $grupos = [
            ['Eletrônicos', 'Produtos eletrônicos em geral'],
            ['Alimentos', 'Alimentos e bebidas'],
            ['Vestuário', 'Roupas e acessórios'],
            ['Higiene', 'Produtos de higiene e limpeza']
        ];
        
        $grupo_ids = [];
        
        foreach ($grupos as $grupo) {
            $nome = $this->conexao->real_escape_string($grupo[0]);
            $descricao = $this->conexao->real_escape_string($grupo[1]);
            
            $sql = "INSERT INTO grupos (nome, descricao) VALUES ('$nome', '$descricao')";
            
            if ($this->conexao->query($sql)) {
                $grupo_ids[] = $this->conexao->insert_id;
                $this->sucessos[] = "Grupo '$nome' inserido com sucesso!";
            } else {
                $this->erros[] = "Erro ao inserir grupo: " . htmlspecialchars($this->conexao->error);
            }
        }
        
        // Inserir produtos
        $produtos = [
            ['Notebook Dell', 'PROD001', '1234567890123', 0, 2500.00, 10],
            ['Mouse Logitech', 'PROD002', '1234567890124', 0, 85.00, 50],
            ['Teclado Mecânico', 'PROD003', '1234567890125', 0, 350.00, 25],
            ['Arroz Integral 5kg', 'PROD004', '1234567890126', 1, 25.00, 100],
            ['Feijão Carioca 1kg', 'PROD005', '1234567890127', 1, 8.50, 80],
            ['Camiseta Básica', 'PROD006', '1234567890128', 2, 45.00, 60],
            ['Calça Jeans', 'PROD007', '1234567890129', 2, 120.00, 40],
            ['Sabonete Neutro', 'PROD008', '1234567890130', 3, 5.00, 200],
            ['Detergente Neutro', 'PROD009', '1234567890131', 3, 3.50, 150]
        ];
        
        foreach ($produtos as $produto) {
            $nome = $this->conexao->real_escape_string($produto[0]);
            $codigo = $this->conexao->real_escape_string($produto[1]);
            $codigo_barras = $this->conexao->real_escape_string($produto[2]);
            $grupo_id = $grupo_ids[$produto[3]];
            $preco = $produto[4];
            $estoque = $produto[5];
            
            $sql = "INSERT INTO produtos (nome, codigo, codigo_barras, grupo_id, preco, estoque) 
                    VALUES ('$nome', '$codigo', '$codigo_barras', $grupo_id, $preco, $estoque)";
            
            if ($this->conexao->query($sql)) {
                $this->sucessos[] = "Produto '$nome' inserido com sucesso!";
            } else {
                $this->erros[] = "Erro ao inserir produto: " . htmlspecialchars($this->conexao->error);
            }
        }
    }
    
    /**
     * Testar conexão final
     */
    private function testarConexao() {
        if (!$this->conectarAoBanco()) {
            return;
        }
        
        // Testar leitura
        $resultado = $this->conexao->query("SELECT COUNT(*) as total FROM produtos");
        if ($resultado) {
            $linha = $resultado->fetch_assoc();
            $this->sucessos[] = "Conexão com banco testada! Total de produtos: " . $linha['total'];
        } else {
            $this->erros[] = 'Erro ao testar leitura: ' . htmlspecialchars($this->conexao->error);
        }
        
        // Testar escrita
        $resultado = $this->conexao->query("INSERT INTO vendas (valor_total, quantidade_itens) VALUES (0, 0)");
        if ($resultado) {
            $venda_id = $this->conexao->insert_id;
            $this->conexao->query("DELETE FROM vendas WHERE id = $venda_id");
            $this->sucessos[] = "Permissões de leitura e escrita confirmadas!";
        } else {
            $this->erros[] = 'Erro ao testar escrita: ' . htmlspecialchars($this->conexao->error);
        }
    }
    
    /**
     * Finalizar instalação
     */
    private function finalizarInstalacao() {
        // Criar arquivo config.php
        $config_content = $this->gerarConfigPHP();
        
        if (file_put_contents('config.php', $config_content)) {
            $this->sucessos[] = 'Arquivo config.php criado com sucesso!';
        } else {
            $this->erros[] = 'Erro ao criar arquivo config.php. Verifique as permissões.';
        }
        
        // Criar arquivo .htaccess (opcional)
        $htaccess_content = $this->gerarHTAccess();
        if (file_put_contents('.htaccess', $htaccess_content)) {
            $this->sucessos[] = 'Arquivo .htaccess criado com sucesso!';
        }
        
        $this->sucessos[] = '✓ INSTALAÇÃO CONCLUÍDA COM SUCESSO!';
    }
    
    /**
     * Gerar conteúdo do config.php
     */
    private function gerarConfigPHP() {
        $host = $this->config['db_host'];
        $user = $this->config['db_user'];
        $pass = $this->config['db_pass'];
        $name = $this->config['db_name'];
        $charset = $this->config['db_charset'];
        
        return <<<'PHP'
<?php
/**
 * Arquivo de Configuração do Sistema de Caixa
 * Gerado automaticamente pelo instalador
 */

// Configurações do Banco de Dados
define('DB_HOST', '{{DB_HOST}}');
define('DB_USER', '{{DB_USER}}');
define('DB_PASS', '{{DB_PASS}}');
define('DB_NAME', '{{DB_NAME}}');
define('DB_CHARSET', '{{DB_CHARSET}}');

// Configurações da Aplicação
define('APP_NAME', 'Sistema de Caixa - ERP Dist');
define('APP_VERSION', '1.0.0');
define('TIMEZONE', 'America/Sao_Paulo');

// Definir timezone
date_default_timezone_set(TIMEZONE);

/**
 * Função para conectar ao banco de dados
 */
function conectarBD() {
    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conexao->connect_error) {
        die(json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao conectar ao banco de dados: ' . $conexao->connect_error
        ]));
    }
    
    $conexao->set_charset(DB_CHARSET);
    return $conexao;
}

/**
 * Função para fechar conexão
 */
function fecharBD($conexao) {
    if ($conexao) {
        $conexao->close();
    }
}

/**
 * Função para sanitizar entrada
 */
function sanitizar($entrada) {
    return htmlspecialchars(trim($entrada), ENT_QUOTES, 'UTF-8');
}

/**
 * Função para formatar moeda
 */
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Função para formatar data
 */
function formatarData($data) {
    $timestamp = strtotime($data);
    return date('d/m/Y H:i:s', $timestamp);
}

/**
 * Função para retornar JSON
 */
function retornarJSON($sucesso, $mensagem, $dados = null) {
    $resposta = [
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ];
    
    if ($dados !== null) {
        $resposta['dados'] = $dados;
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($resposta, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
PHP;
        
        // Substituir placeholders
        $config_content = str_replace('{{DB_HOST}}', $host, $config_content);
        $config_content = str_replace('{{DB_USER}}', $user, $config_content);
        $config_content = str_replace('{{DB_PASS}}', $pass, $config_content);
        $config_content = str_replace('{{DB_NAME}}', $name, $config_content);
        $config_content = str_replace('{{DB_CHARSET}}', $charset, $config_content);
        
        return $config_content;
    }
    
    /**
     * Gerar arquivo .htaccess
     */
    private function gerarHTAccess() {
        return <<<'HTACCESS'
# Ativar mod_rewrite
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirecionar installer.php após instalação
    # RewriteRule ^installer\.php$ - [L]
    # RewriteCond %{REQUEST_FILENAME} !-f
    # RewriteCond %{REQUEST_FILENAME} !-d
    # RewriteRule . index.php [L]
</IfModule>

# Segurança
<FilesMatch "\.php$">
    <IfModule mod_authz_core.c>
        Require all granted
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Allow from all
    </IfModule>
</FilesMatch>

# Desabilitar listagem de diretórios
Options -Indexes

# Compressão GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
HTACCESS;
    }
    
    /**
     * Conectar ao banco de dados
     */
    private function conectarAoBanco() {
        if ($this->conexao === null) {
            $this->conexao = @new mysqli(
                $this->config['db_host'],
                $this->config['db_user'],
                $this->config['db_pass'],
                $this->config['db_name']
            );
            
            if ($this->conexao->connect_error) {
                $this->erros[] = 'Erro ao conectar ao banco: ' . htmlspecialchars($this->conexao->connect_error);
                return false;
            }
            
            $this->conexao->set_charset($this->config['db_charset']);
        }
        
        return true;
    }
    
    /**
     * Verificar pré-requisitos
     */
    public function verificarRequisitos() {
        $requisitos = [
            'PHP 7.4+' => version_compare(PHP_VERSION, '7.4', '>='),
            'MySQLi Extension' => extension_loaded('mysqli'),
            'JSON Extension' => extension_loaded('json'),
            'Permissão de Escrita' => is_writable('.')
        ];
        
        foreach ($requisitos as $nome => $resultado) {
            if ($resultado) {
                $this->sucessos[] = $nome . ' ✓';
            } else {
                $this->erros[] = $nome . ' ✗';
            }
        }
        
        return count($this->erros) === 0;
    }
    
    /**
     * Renderizar HTML
     */
    public function renderizar() {
        ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador - Sistema de Caixa ERP Dist</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        header {
            text-align: center;
            margin-bottom: 40px;
        }

        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
        }

        .progress-bar {
            background-color: #e0e0e0;
            height: 8px;
            border-radius: 4px;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, #667eea, #764ba2);
            height: 100%;
            width: <?php echo ($this->etapa / 7) * 100; ?>%;
            transition: width 0.3s ease;
        }

        .step-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .step-info h3 {
            color: #667eea;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .step-info p {
            color: #666;
            font-size: 13px;
        }

        .messages {
            margin-bottom: 20px;
        }

        .message {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 13px;
            border-left: 4px solid;
        }

        .message.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left-color: #4caf50;
        }

        .message.error {
            background-color: #ffebee;
            color: #c62828;
            border-left-color: #f44336;
        }

        .message.warning {
            background-color: #fff3e0;
            color: #e65100;
            border-left-color: #ff9800;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
            font-size: 13px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        button {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .success-box {
            background-color: #e8f5e9;
            border: 2px solid #4caf50;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-box h2 {
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .success-box p {
            color: #558b2f;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .success-box a {
            display: inline-block;
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .success-box a:hover {
            background-color: #388e3c;
        }

        .step-number {
            display: inline-block;
            background-color: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: 600;
            margin-right: 10px;
        }

        .loading {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛒 Instalador do Sistema de Caixa</h1>
            <p class="subtitle">ERP Dist v1.0.0</p>
        </header>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>

        <div class="messages">
            <?php foreach ($this->sucessos as $msg): ?>
                <div class="message success">✓ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
            
            <?php foreach ($this->avisos as $msg): ?>
                <div class="message warning">⚠ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
            
            <?php foreach ($this->erros as $msg): ?>
                <div class="message error">✗ <?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        </div>

        <?php
        switch ($this->etapa) {
            case 1:
                $this->renderizarEtapa1();
                break;
            case 2:
                $this->renderizarEtapa2();
                break;
            case 3:
                $this->renderizarEtapa3();
                break;
            case 4:
                $this->renderizarEtapa4();
                break;
            case 5:
                $this->renderizarEtapa5();
                break;
            case 6:
                $this->renderizarEtapa6();
                break;
            case 7:
                $this->renderizarEtapa7();
                break;
        }
        ?>
    </div>
</body>
</html>
        <?php
    }
    
    /**
     * Etapa 1: Verificar Requisitos
     */
    private function renderizarEtapa1() {
        $requisitos_ok = $this->verificarRequisitos();
        ?>
        <div class="step-info">
            <h3><span class="step-number">1</span>Verificar Requisitos</h3>
            <p>Verificando se seu servidor atende aos requisitos mínimos...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="verificar_requisitos">
            <div class="button-group">
                <?php if ($requisitos_ok): ?>
                    <button type="submit" class="btn-primary" formaction="?" onclick="document.querySelector('input[name=acao]').value='configurar_banco'">
                        ✓ Próximo →
                    </button>
                <?php else: ?>
                    <button type="button" class="btn-primary" disabled>
                        ✗ Requisitos não atendidos
                    </button>
                <?php endif; ?>
            </div>
        </form>
        <?php
    }
    
    /**
     * Etapa 2: Configurar Banco de Dados
     */
    private function renderizarEtapa2() {
        $config = isset($_SESSION['config']) ? $_SESSION['config'] : $this->config;
        ?>
        <div class="step-info">
            <h3><span class="step-number">2</span>Configurar Banco de Dados</h3>
            <p>Digite as credenciais do seu banco de dados MySQL...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="configurar_banco">
            
            <div class="form-group">
                <label for="db_host">Host do Banco de Dados:</label>
                <input type="text" id="db_host" name="db_host" value="<?php echo htmlspecialchars($config['db_host']); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_user">Usuário:</label>
                <input type="text" id="db_user" name="db_user" value="<?php echo htmlspecialchars($config['db_user']); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_pass">Senha:</label>
                <input type="password" id="db_pass" name="db_pass" value="<?php echo htmlspecialchars($config['db_pass']); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_name">Nome do Banco:</label>
                <input type="text" id="db_name" name="db_name" value="<?php echo htmlspecialchars($config['db_name']); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-primary">Testar e Continuar →</button>
            </div>
        </form>
        <?php
    }
    
    /**
     * Etapa 3: Criar Banco de Dados
     */
    private function renderizarEtapa3() {
        ?>
        <div class="step-info">
            <h3><span class="step-number">3</span>Criar Banco de Dados</h3>
            <p>Criando o banco de dados no servidor...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="criar_banco">
            <div class="button-group">
                <button type="submit" class="btn-primary">Criar Banco →</button>
            </div>
        </form>
        <?php
    }
    
    /**
     * Etapa 4: Criar Tabelas
     */
    private function renderizarEtapa4() {
        ?>
        <div class="step-info">
            <h3><span class="step-number">4</span>Criar Tabelas</h3>
            <p>Criando as tabelas do sistema...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="criar_tabelas">
            <div class="button-group">
                <button type="submit" class="btn-primary">Criar Tabelas →</button>
            </div>
        </form>
        <?php
    }
    
    /**
     * Etapa 5: Inserir Dados de Exemplo
     */
    private function renderizarEtapa5() {
        ?>
        <div class="step-info">
            <h3><span class="step-number">5</span>Inserir Dados de Exemplo</h3>
            <p>Inserindo produtos de exemplo para teste...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="inserir_dados">
            <div class="button-group">
                <button type="submit" class="btn-primary">Inserir Dados →</button>
            </div>
        </form>
        <?php
    }
    
    /**
     * Etapa 6: Testar Conexão
     */
    private function renderizarEtapa6() {
        ?>
        <div class="step-info">
            <h3><span class="step-number">6</span>Testar Conexão</h3>
            <p>Testando a conexão e permissões do banco...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="testar_conexao">
            <div class="button-group">
                <button type="submit" class="btn-primary">Testar Conexão →</button>
            </div>
        </form>
        <?php
    }
    
    /**
     * Etapa 7: Finalizar Instalação
     */
    private function renderizarEtapa7() {
        $tem_erros = count($this->erros) > 0;
        ?>
        <div class="step-info">
            <h3><span class="step-number">7</span>Finalizar Instalação</h3>
            <p>Criando arquivo de configuração e finalizando...</p>
        </div>

        <form method="POST">
            <input type="hidden" name="acao" value="finalizar">
            <div class="button-group">
                <button type="submit" class="btn-primary" <?php echo $tem_erros ? 'disabled' : ''; ?>>
                    <?php echo $tem_erros ? '✗ Erros encontrados' : '✓ Finalizar Instalação'; ?>
                </button>
            </div>
        </form>

        <?php if (!$tem_erros && count($this->sucessos) > 5): ?>
            <div class="success-box">
                <h2>✓ Instalação Concluída!</h2>
                <p>O sistema foi instalado com sucesso. Clique no botão abaixo para acessar o sistema de caixa.</p>
                <a href="caixa.html">Acessar Sistema →</a>
            </div>
        <?php endif; ?>
        <?php
    }
}

// Função auxiliar
function sanitizar($entrada) {
    return htmlspecialchars(trim($entrada), ENT_QUOTES, 'UTF-8');
}

// Executar instalador
$instalador = new InstaladorSistemaCaixa();
$instalador->renderizar();
?>
