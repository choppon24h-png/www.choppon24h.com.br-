<?php
/**
 * API para salvar venda com todos os itens
 */

require_once 'config.php';

// Verificar se é uma requisição AJAX POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    
    if (empty($dados) || empty($dados['itens']) || count($dados['itens']) === 0) {
        retornarJSON(false, 'Nenhum item para salvar');
    }
    
    $conexao = conectarBD();
    
    // Iniciar transação
    $conexao->begin_transaction();
    
    try {
        // Calcular totais
        $valor_total = 0;
        $quantidade_itens = 0;
        
        foreach ($dados['itens'] as $item) {
            $valor_total += (float)$item['subtotal'];
            $quantidade_itens += (int)$item['quantidade'];
        }
        
        // Inserir venda
        $query_venda = "
            INSERT INTO vendas (valor_total, quantidade_itens, status, observacoes)
            VALUES (?, ?, 'finalizada', ?)
        ";
        
        $stmt_venda = $conexao->prepare($query_venda);
        
        if ($stmt_venda === false) {
            throw new Exception('Erro ao preparar insert de venda: ' . $conexao->error);
        }
        
        $observacoes = isset($dados['observacoes']) ? sanitizar($dados['observacoes']) : '';
        $stmt_venda->bind_param('dis', $valor_total, $quantidade_itens, $observacoes);
        
        if (!$stmt_venda->execute()) {
            throw new Exception('Erro ao inserir venda: ' . $stmt_venda->error);
        }
        
        $venda_id = $conexao->insert_id;
        $stmt_venda->close();
        
        // Inserir itens da venda
        $query_item = "
            INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ";
        
        $stmt_item = $conexao->prepare($query_item);
        
        if ($stmt_item === false) {
            throw new Exception('Erro ao preparar insert de itens: ' . $conexao->error);
        }
        
        foreach ($dados['itens'] as $item) {
            $produto_id = (int)$item['produto_id'];
            $quantidade = (int)$item['quantidade'];
            $preco_unitario = (float)$item['preco_unitario'];
            $subtotal = (float)$item['subtotal'];
            
            $stmt_item->bind_param('iiidd', $venda_id, $produto_id, $quantidade, $preco_unitario, $subtotal);
            
            if (!$stmt_item->execute()) {
                throw new Exception('Erro ao inserir item: ' . $stmt_item->error);
            }
        }
        
        $stmt_item->close();
        
        // Confirmar transação
        $conexao->commit();
        fecharBD($conexao);
        
        retornarJSON(true, 'Venda salva com sucesso!', [
            'venda_id' => $venda_id,
            'valor_total' => $valor_total,
            'quantidade_itens' => $quantidade_itens
        ]);
        
    } catch (Exception $e) {
        // Reverter transação em caso de erro
        $conexao->rollback();
        fecharBD($conexao);
        
        retornarJSON(false, 'Erro ao salvar venda: ' . $e->getMessage());
    }
    
} else {
    retornarJSON(false, 'Método de requisição inválido');
}
?>
