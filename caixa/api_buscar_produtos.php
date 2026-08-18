<?php
/**
 * API para buscar produtos em tempo real
 * Busca por: nome, código, código de barras, grupo
 */

require_once 'config.php';

// Verificar se é uma requisição AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['busca'])) {
    $busca = sanitizar($_POST['busca']);
    $conexao = conectarBD();
    
    // Preparar query com busca em múltiplos campos
    $query = "
        SELECT 
            p.id,
            p.nome,
            p.codigo,
            p.codigo_barras,
            p.preco,
            p.estoque,
            g.nome as grupo
        FROM produtos p
        INNER JOIN grupos g ON p.grupo_id = g.id
        WHERE p.ativo = 1
        AND (
            p.nome LIKE ? 
            OR p.codigo LIKE ? 
            OR p.codigo_barras LIKE ? 
            OR g.nome LIKE ?
        )
        LIMIT 10
    ";
    
    $stmt = $conexao->prepare($query);
    
    if ($stmt === false) {
        retornarJSON(false, 'Erro ao preparar consulta: ' . $conexao->error);
    }
    
    // Preparar termos de busca
    $termo = '%' . $busca . '%';
    
    // Bind dos parâmetros
    $stmt->bind_param('ssss', $termo, $termo, $termo, $termo);
    
    // Executar query
    if (!$stmt->execute()) {
        retornarJSON(false, 'Erro ao executar consulta: ' . $stmt->error);
    }
    
    // Obter resultados
    $resultado = $stmt->get_result();
    $produtos = [];
    
    while ($linha = $resultado->fetch_assoc()) {
        $produtos[] = [
            'id' => (int)$linha['id'],
            'nome' => $linha['nome'],
            'codigo' => $linha['codigo'],
            'codigo_barras' => $linha['codigo_barras'],
            'preco' => (float)$linha['preco'],
            'estoque' => (int)$linha['estoque'],
            'grupo' => $linha['grupo']
        ];
    }
    
    $stmt->close();
    fecharBD($conexao);
    
    if (count($produtos) > 0) {
        retornarJSON(true, 'Produtos encontrados', $produtos);
    } else {
        retornarJSON(false, 'Nenhum produto encontrado');
    }
} else {
    retornarJSON(false, 'Requisição inválida');
}
?>
