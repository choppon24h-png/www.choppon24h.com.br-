<?php
/**
 * API para gerenciamento de estoque
 */

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$acao = isset($_POST['acao']) ? sanitizar($_POST['acao']) : '';

switch ($acao) {
    case 'listar':
        listarEstoque();
        break;
    
    case 'atualizar_estoque':
        atualizarEstoque();
        break;
    
    case 'ajustar_estoque':
        ajustarEstoque();
        break;
    
    case 'relatorio':
        gerarRelatorio();
        break;
    
    default:
        retornarJSON(false, 'Ação inválida');
}

/**
 * Listar estoque de todos os produtos
 */
function listarEstoque() {
    $conexao = conectarBD();
    
    $busca = isset($_POST['busca']) ? sanitizar($_POST['busca']) : '';
    $grupo_id = isset($_POST['grupo_id']) ? (int)$_POST['grupo_id'] : 0;
    $filtro_estoque = isset($_POST['filtro_estoque']) ? sanitizar($_POST['filtro_estoque']) : '';
    
    $query = "
        SELECT 
            p.id,
            p.nome,
            p.codigo,
            p.codigo_barras,
            p.preco,
            p.estoque,
            p.ativo,
            g.nome as grupo,
            (p.estoque * p.preco) as valor_total
        FROM produtos p
        INNER JOIN grupos g ON p.grupo_id = g.id
        WHERE 1=1
    ";
    
    if (!empty($busca)) {
        $busca_escapado = $conexao->real_escape_string('%' . $busca . '%');
        $query .= " AND (p.nome LIKE '$busca_escapado' OR p.codigo LIKE '$busca_escapado')";
    }
    
    if ($grupo_id > 0) {
        $query .= " AND p.grupo_id = $grupo_id";
    }
    
    // Filtros de estoque
    if ($filtro_estoque === 'baixo') {
        $query .= " AND p.estoque <= 10";
    } elseif ($filtro_estoque === 'critico') {
        $query .= " AND p.estoque = 0";
    } elseif ($filtro_estoque === 'alto') {
        $query .= " AND p.estoque > 50";
    }
    
    $query .= " ORDER BY p.estoque ASC, p.nome ASC";
    
    $resultado = $conexao->query($query);
    
    if (!$resultado) {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao listar estoque: ' . $conexao->error);
    }
    
    $produtos = [];
    $valor_total_estoque = 0;
    
    while ($linha = $resultado->fetch_assoc()) {
        $valor_item = (float)$linha['valor_total'];
        $valor_total_estoque += $valor_item;
        
        $produtos[] = [
            'id' => (int)$linha['id'],
            'nome' => $linha['nome'],
            'codigo' => $linha['codigo'],
            'codigo_barras' => $linha['codigo_barras'],
            'preco' => (float)$linha['preco'],
            'estoque' => (int)$linha['estoque'],
            'ativo' => (int)$linha['ativo'],
            'grupo' => $linha['grupo'],
            'valor_total' => $valor_item,
            'status_estoque' => obterStatusEstoque((int)$linha['estoque'])
        ];
    }
    
    fecharBD($conexao);
    
    retornarJSON(true, 'Estoque listado com sucesso', [
        'produtos' => $produtos,
        'total_produtos' => count($produtos),
        'valor_total_estoque' => $valor_total_estoque
    ]);
}

/**
 * Atualizar estoque de um produto
 */
function atualizarEstoque() {
    if (empty($_POST['id']) || !isset($_POST['estoque'])) {
        retornarJSON(false, 'ID e estoque são obrigatórios');
    }
    
    $id = (int)$_POST['id'];
    $estoque = (int)$_POST['estoque'];
    
    if ($estoque < 0) {
        retornarJSON(false, 'Estoque não pode ser negativo');
    }
    
    $conexao = conectarBD();
    
    // Obter estoque anterior
    $resultado = $conexao->query("SELECT estoque FROM produtos WHERE id = $id");
    if (!$resultado || $resultado->num_rows === 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Produto não encontrado');
    }
    
    $linha = $resultado->fetch_assoc();
    $estoque_anterior = (int)$linha['estoque'];
    
    $query = "UPDATE produtos SET estoque = $estoque WHERE id = $id";
    
    if ($conexao->query($query)) {
        fecharBD($conexao);
        retornarJSON(true, 'Estoque atualizado com sucesso', [
            'estoque_anterior' => $estoque_anterior,
            'estoque_novo' => $estoque,
            'diferenca' => $estoque - $estoque_anterior
        ]);
    } else {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao atualizar estoque: ' . $conexao->error);
    }
}

/**
 * Ajustar estoque (adicionar ou remover quantidade)
 */
function ajustarEstoque() {
    if (empty($_POST['id']) || !isset($_POST['quantidade'])) {
        retornarJSON(false, 'ID e quantidade são obrigatórios');
    }
    
    $id = (int)$_POST['id'];
    $quantidade = (int)$_POST['quantidade'];
    $tipo = isset($_POST['tipo']) ? sanitizar($_POST['tipo']) : 'entrada';
    
    if ($tipo !== 'entrada' && $tipo !== 'saida') {
        retornarJSON(false, 'Tipo deve ser entrada ou saida');
    }
    
    $conexao = conectarBD();
    
    // Obter estoque atual
    $resultado = $conexao->query("SELECT estoque FROM produtos WHERE id = $id");
    if (!$resultado || $resultado->num_rows === 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Produto não encontrado');
    }
    
    $linha = $resultado->fetch_assoc();
    $estoque_atual = (int)$linha['estoque'];
    
    if ($tipo === 'entrada') {
        $novo_estoque = $estoque_atual + $quantidade;
    } else {
        $novo_estoque = $estoque_atual - $quantidade;
        
        if ($novo_estoque < 0) {
            fecharBD($conexao);
            retornarJSON(false, 'Estoque insuficiente para saída');
        }
    }
    
    $query = "UPDATE produtos SET estoque = $novo_estoque WHERE id = $id";
    
    if ($conexao->query($query)) {
        fecharBD($conexao);
        retornarJSON(true, 'Estoque ajustado com sucesso', [
            'estoque_anterior' => $estoque_atual,
            'estoque_novo' => $novo_estoque,
            'tipo_operacao' => $tipo,
            'quantidade_movimentada' => $quantidade
        ]);
    } else {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao ajustar estoque: ' . $conexao->error);
    }
}

/**
 * Gerar relatório de estoque
 */
function gerarRelatorio() {
    $conexao = conectarBD();
    
    $query = "
        SELECT 
            g.nome as grupo,
            COUNT(p.id) as total_produtos,
            SUM(p.estoque) as total_unidades,
            SUM(p.estoque * p.preco) as valor_total,
            AVG(p.preco) as preco_medio
        FROM produtos p
        INNER JOIN grupos g ON p.grupo_id = g.id
        WHERE p.ativo = 1
        GROUP BY g.id, g.nome
        ORDER BY g.nome ASC
    ";
    
    $resultado = $conexao->query($query);
    
    if (!$resultado) {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao gerar relatório: ' . $conexao->error);
    }
    
    $relatorio = [];
    $total_geral_produtos = 0;
    $total_geral_unidades = 0;
    $valor_total_geral = 0;
    
    while ($linha = $resultado->fetch_assoc()) {
        $total_geral_produtos += (int)$linha['total_produtos'];
        $total_geral_unidades += (int)$linha['total_unidades'];
        $valor_total_geral += (float)$linha['valor_total'];
        
        $relatorio[] = [
            'grupo' => $linha['grupo'],
            'total_produtos' => (int)$linha['total_produtos'],
            'total_unidades' => (int)$linha['total_unidades'],
            'valor_total' => (float)$linha['valor_total'],
            'preco_medio' => (float)$linha['preco_medio']
        ];
    }
    
    fecharBD($conexao);
    
    retornarJSON(true, 'Relatório gerado com sucesso', [
        'por_grupo' => $relatorio,
        'resumo' => [
            'total_grupos' => count($relatorio),
            'total_produtos' => $total_geral_produtos,
            'total_unidades' => $total_geral_unidades,
            'valor_total_estoque' => $valor_total_geral
        ]
    ]);
}

/**
 * Obter status do estoque
 */
function obterStatusEstoque($quantidade) {
    if ($quantidade === 0) {
        return 'critico';
    } elseif ($quantidade <= 10) {
        return 'baixo';
    } elseif ($quantidade > 50) {
        return 'alto';
    } else {
        return 'normal';
    }
}
?>
