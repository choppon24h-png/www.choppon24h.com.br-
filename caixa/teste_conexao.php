<?php
/**
 * Arquivo de teste para validar conexão com banco de dados
 * Acesse: https://seu-dominio.com/teste_conexao.php
 */

require_once 'config.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexão - Sistema de Caixa</title>
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
            border-radius: 8px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }

        .test-item {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #ddd;
        }

        .test-item.success {
            background-color: #e8f5e9;
            border-left-color: #4caf50;
        }

        .test-item.error {
            background-color: #ffebee;
            border-left-color: #f44336;
        }

        .test-item.warning {
            background-color: #fff3e0;
            border-left-color: #ff9800;
        }

        .test-item-title {
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .test-item.success .test-item-title {
            color: #2e7d32;
        }

        .test-item.error .test-item-title {
            color: #c62828;
        }

        .test-item.warning .test-item-title {
            color: #e65100;
        }

        .test-item-content {
            font-size: 13px;
            margin-top: 5px;
            line-height: 1.5;
        }

        .icon {
            font-size: 18px;
        }

        .summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 4px;
            text-align: center;
        }

        .summary-text {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .summary-text.success {
            color: #4caf50;
        }

        .summary-text.error {
            color: #f44336;
        }

        .summary-text.warning {
            color: #ff9800;
        }

        code {
            background-color: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .back-link:hover {
            background-color: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Teste de Conexão</h1>

        <?php
        $testes_passaram = true;
        $testes_avisos = false;

        // Teste 1: Verificar PHP Version
        echo '<div class="test-item success">';
        echo '<div class="test-item-title"><span class="icon">✓</span> Versão do PHP</div>';
        echo '<div class="test-item-content">PHP ' . phpversion() . ' detectado</div>';
        echo '</div>';

        // Teste 2: Verificar extensão MySQLi
        echo '<div class="test-item ' . (extension_loaded('mysqli') ? 'success' : 'error') . '">';
        echo '<div class="test-item-title"><span class="icon">' . (extension_loaded('mysqli') ? '✓' : '✗') . '</span> Extensão MySQLi</div>';
        if (extension_loaded('mysqli')) {
            echo '<div class="test-item-content">Extensão MySQLi está habilitada</div>';
        } else {
            echo '<div class="test-item-content">Extensão MySQLi não está habilitada. Contate o suporte do HostGator.</div>';
            $testes_passaram = false;
        }
        echo '</div>';

        // Teste 3: Verificar extensão JSON
        echo '<div class="test-item ' . (extension_loaded('json') ? 'success' : 'error') . '">';
        echo '<div class="test-item-title"><span class="icon">' . (extension_loaded('json') ? '✓' : '✗') . '</span> Extensão JSON</div>';
        if (extension_loaded('json')) {
            echo '<div class="test-item-content">Extensão JSON está habilitada</div>';
        } else {
            echo '<div class="test-item-content">Extensão JSON não está habilitada. Contate o suporte do HostGator.</div>';
            $testes_passaram = false;
        }
        echo '</div>';

        // Teste 4: Conexão com banco de dados
        echo '<div class="test-item">';
        $conexao = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conexao->connect_error) {
            echo '<div class="test-item error">';
            echo '<div class="test-item-title"><span class="icon">✗</span> Conexão com Banco de Dados</div>';
            echo '<div class="test-item-content">Erro: ' . htmlspecialchars($conexao->connect_error) . '<br>';
            echo 'Host: <code>' . DB_HOST . '</code><br>';
            echo 'Usuário: <code>' . DB_USER . '</code><br>';
            echo 'Banco: <code>' . DB_NAME . '</code></div>';
            echo '</div>';
            $testes_passaram = false;
        } else {
            echo '<div class="test-item success">';
            echo '<div class="test-item-title"><span class="icon">✓</span> Conexão com Banco de Dados</div>';
            echo '<div class="test-item-content">Conectado com sucesso!<br>';
            echo 'Host: <code>' . DB_HOST . '</code><br>';
            echo 'Banco: <code>' . DB_NAME . '</code><br>';
            echo 'Charset: <code>' . $conexao->character_set_name() . '</code></div>';
            echo '</div>';

            // Teste 5: Verificar tabelas
            echo '<div class="test-item">';
            $tabelas_esperadas = ['grupos', 'produtos', 'vendas', 'itens_venda'];
            $tabelas_faltando = [];

            foreach ($tabelas_esperadas as $tabela) {
                $resultado = $conexao->query("SHOW TABLES LIKE '$tabela'");
                if (!$resultado || $resultado->num_rows === 0) {
                    $tabelas_faltando[] = $tabela;
                }
            }

            if (empty($tabelas_faltando)) {
                echo '<div class="test-item success">';
                echo '<div class="test-item-title"><span class="icon">✓</span> Tabelas do Banco</div>';
                echo '<div class="test-item-content">Todas as tabelas encontradas:<br>';
                foreach ($tabelas_esperadas as $tabela) {
                    echo '• <code>' . $tabela . '</code><br>';
                }
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="test-item error">';
                echo '<div class="test-item-title"><span class="icon">✗</span> Tabelas do Banco</div>';
                echo '<div class="test-item-content">Tabelas faltando: ' . implode(', ', $tabelas_faltando) . '<br>';
                echo 'Execute o script <code>database_setup.sql</code> no phpMyAdmin.</div>';
                echo '</div>';
                $testes_passaram = false;
            }

            // Teste 6: Contar produtos
            echo '<div class="test-item">';
            $resultado = $conexao->query("SELECT COUNT(*) as total FROM produtos");
            $linha = $resultado->fetch_assoc();
            $total_produtos = $linha['total'];

            if ($total_produtos > 0) {
                echo '<div class="test-item success">';
                echo '<div class="test-item-title"><span class="icon">✓</span> Produtos Cadastrados</div>';
                echo '<div class="test-item-content">' . $total_produtos . ' produto(s) encontrado(s)</div>';
                echo '</div>';
            } else {
                echo '<div class="test-item warning">';
                echo '<div class="test-item-title"><span class="icon">⚠</span> Produtos Cadastrados</div>';
                echo '<div class="test-item-content">Nenhum produto cadastrado. Insira dados de teste.</div>';
                echo '</div>';
                $testes_avisos = true;
            }

            // Teste 7: Verificar permissões de escrita
            echo '<div class="test-item">';
            $resultado = $conexao->query("INSERT INTO vendas (valor_total, quantidade_itens) VALUES (0, 0)");
            if ($resultado) {
                $venda_id = $conexao->insert_id;
                $conexao->query("DELETE FROM vendas WHERE id = $venda_id");
                echo '<div class="test-item success">';
                echo '<div class="test-item-title"><span class="icon">✓</span> Permissões de Escrita</div>';
                echo '<div class="test-item-content">Banco de dados permite leitura e escrita</div>';
                echo '</div>';
            } else {
                echo '<div class="test-item error">';
                echo '<div class="test-item-title"><span class="icon">✗</span> Permissões de Escrita</div>';
                echo '<div class="test-item-content">Erro: ' . htmlspecialchars($conexao->error) . '</div>';
                echo '</div>';
                $testes_passaram = false;
            }

            $conexao->close();
        }

        // Resumo
        echo '<div class="summary">';
        if ($testes_passaram) {
            echo '<div class="summary-text success">✓ Todos os testes passaram!</div>';
            echo '<p>O sistema está pronto para usar. <a href="caixa.html" class="back-link">Ir para o Sistema</a></p>';
        } elseif ($testes_avisos) {
            echo '<div class="summary-text warning">⚠ Testes passaram com avisos</div>';
            echo '<p>Verifique os avisos acima. <a href="caixa.html" class="back-link">Ir para o Sistema</a></p>';
        } else {
            echo '<div class="summary-text error">✗ Alguns testes falharam</div>';
            echo '<p>Corrija os erros acima antes de usar o sistema.</p>';
        }
        echo '</div>';
        ?>
    </div>
</body>
</html>
