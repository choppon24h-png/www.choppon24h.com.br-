<?php
/**
 * API para gerenciamento de produtos (CRUD)
 */

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$acao = isset($_POST['acao']) ? sanitizar($_POST['acao']) : '';

switch ($acao) {
    case 'listar':
        listarProdutos();
        break;
    
    case 'obter':
        obterProduto();
        break;
    
    case 'criar':
        criarProduto();
        break;
    
    case 'atualizar':
        atualizarProduto();
        break;
    
    case 'deletar':
        deletarProduto();
        break;
    
    case 'listar_grupos':
        listarGrupos();
        break;
    
    default:
        retornarJSON(false, 'Ação inválida');
}

/**
 * Listar todos os produtos
 */
function listarProdutos() {
    $conexao = conectarBD();
    
    $busca = isset($_POST['busca']) ? sanitizar($_POST['busca']) : '';
    $grupo_id = isset($_POST['grupo_id']) ? (int)$_POST['grupo_id'] : 0;
    
    $query = "
        SELECT 
            p.id,
            p.nome,
            p.codigo,
            p.codigo_barras,
            p.preco,
            p.estoque,
            p.ativo,
            p.data_criacao,
            g.nome as grupo
        FROM produtos p
        INNER JOIN grupos g ON p.grupo_id = g.id
        WHERE 1=1
    ";
    
    if (!empty($busca)) {
        $busca_escapado = $conexao->real_escape_string('%' . $busca . '%');
        $query .= " AND (p.nome LIKE '$busca_escapado' OR p.codigo LIKE '$busca_escapado' OR p.codigo_barras LIKE '$busca_escapado')";
    }
    
    if ($grupo_id > 0) {
        $query .= " AND p.grupo_id = $grupo_id";
    }
    
    $query .= " ORDER BY p.nome ASC";
    
    $resultado = $conexao->query($query);
    
    if (!$resultado) {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao listar produtos: ' . $conexao->error);
    }
    
    $produtos = [];
    while ($linha = $resultado->fetch_assoc()) {
        $produtos[] = [
            'id' => (int)$linha['id'],
            'nome' => $linha['nome'],
            'codigo' => $linha['codigo'],
            'codigo_barras' => $linha['codigo_barras'],
            'preco' => (float)$linha['preco'],
            'estoque' => (int)$linha['estoque'],
            'ativo' => (int)$linha['ativo'],
            'grupo' => $linha['grupo'],
            'data_criacao' => $linha['data_criacao']
        ];
    }
    
    fecharBD($conexao);
    retornarJSON(true, 'Produtos listados com sucesso', $produtos);
}

/**
 * Obter um produto específico
 */
function obterProduto() {
    if (empty($_POST['id'])) {
        retornarJSON(false, 'ID do produto não fornecido');
    }
    
    $id = (int)$_POST['id'];
    $conexao = conectarBD();
    
    $query = "
        SELECT 
            p.id,
            p.nome,
            p.codigo,
            p.codigo_barras,
            p.preco,
            p.estoque,
            p.ativo,
            p.grupo_id,
            p.data_criacao,
            g.nome as grupo
        FROM produtos p
        INNER JOIN grupos g ON p.grupo_id = g.id
        WHERE p.id = $id
    ";
    
    $resultado = $conexao->query($query);
    
    if (!$resultado || $resultado->num_rows === 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Produto não encontrado');
    }
    
    $produto = $resultado->fetch_assoc();
    
    fecharBD($conexao);
    
    retornarJSON(true, 'Produto obtido com sucesso', [
        'id' => (int)$produto['id'],
        'nome' => $produto['nome'],
        'codigo' => $produto['codigo'],
        'codigo_barras' => $produto['codigo_barras'],
        'preco' => (float)$produto['preco'],
        'estoque' => (int)$produto['estoque'],
        'ativo' => (int)$produto['ativo'],
        'grupo_id' => (int)$produto['grupo_id'],
        'grupo' => $produto['grupo'],
        'data_criacao' => $produto['data_criacao']
    ]);
}

/**
 * Criar novo produto
 */
function criarProduto() {
    $campos_obrigatorios = ['nome', 'codigo', 'codigo_barras', 'grupo_id', 'preco', 'estoque'];
    
    foreach ($campos_obrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            retornarJSON(false, "Campo '$campo' é obrigatório");
        }
    }
    
    $nome = sanitizar($_POST['nome']);
    $codigo = sanitizar($_POST['codigo']);
    $codigo_barras = sanitizar($_POST['codigo_barras']);
    $grupo_id = (int)$_POST['grupo_id'];
    $preco = (float)$_POST['preco'];
    $estoque = (int)$_POST['estoque'];
    $ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;
    
    if (strlen($nome) < 3) {
        retornarJSON(false, 'Nome deve ter pelo menos 3 caracteres');
    }
    
    if ($preco < 0) {
        retornarJSON(false, 'Preço não pode ser negativo');
    }
    
    if ($estoque < 0) {
        retornarJSON(false, 'Estoque não pode ser negativo');
    }
    
    $conexao = conectarBD();
    
    // Verificar se código já existe
    $resultado = $conexao->query("SELECT id FROM produtos WHERE codigo = '$codigo'");
    if ($resultado && $resultado->num_rows > 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Código de produto já existe');
    }
    
    // Verificar se código de barras já existe
    $resultado = $conexao->query("SELECT id FROM produtos WHERE codigo_barras = '$codigo_barras'");
    if ($resultado && $resultado->num_rows > 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Código de barras já existe');
    }
    
    $nome_escapado = $conexao->real_escape_string($nome);
    $codigo_escapado = $conexao->real_escape_string($codigo);
    $codigo_barras_escapado = $conexao->real_escape_string($codigo_barras);
    
    $query = "
        INSERT INTO produtos (nome, codigo, codigo_barras, grupo_id, preco, estoque, ativo)
        VALUES ('$nome_escapado', '$codigo_escapado', '$codigo_barras_escapado', $grupo_id, $preco, $estoque, $ativo)
    ";
    
    if ($conexao->query($query)) {
        $produto_id = $conexao->insert_id;
        fecharBD($conexao);
        retornarJSON(true, 'Produto criado com sucesso', ['id' => $produto_id]);
    } else {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao criar produto: ' . $conexao->error);
    }
}

/**
 * Atualizar produto
 */
function atualizarProduto() {
    if (empty($_POST['id'])) {
        retornarJSON(false, 'ID do produto não fornecido');
    }
    
    $id = (int)$_POST['id'];
    $conexao = conectarBD();
    
    // Verificar se produto existe
    $resultado = $conexao->query("SELECT id FROM produtos WHERE id = $id");
    if (!$resultado || $resultado->num_rows === 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Produto não encontrado');
    }
    
    $atualizacoes = [];
    
    if (isset($_POST['nome']) && !empty($_POST['nome'])) {
        $nome = sanitizar($_POST['nome']);
        if (strlen($nome) < 3) {
            fecharBD($conexao);
            retornarJSON(false, 'Nome deve ter pelo menos 3 caracteres');
        }
        $nome_escapado = $conexao->real_escape_string($nome);
        $atualizacoes[] = "nome = '$nome_escapado'";
    }
    
    if (isset($_POST['preco'])) {
        $preco = (float)$_POST['preco'];
        if ($preco < 0) {
            fecharBD($conexao);
            retornarJSON(false, 'Preço não pode ser negativo');
        }
        $atualizacoes[] = "preco = $preco";
    }
    
    if (isset($_POST['estoque'])) {
        $estoque = (int)$_POST['estoque'];
        if ($estoque < 0) {
            fecharBD($conexao);
            retornarJSON(false, 'Estoque não pode ser negativo');
        }
        $atualizacoes[] = "estoque = $estoque";
    }
    
    if (isset($_POST['ativo'])) {
        $ativo = (int)$_POST['ativo'];
        $atualizacoes[] = "ativo = $ativo";
    }
    
    if (isset($_POST['grupo_id'])) {
        $grupo_id = (int)$_POST['grupo_id'];
        $atualizacoes[] = "grupo_id = $grupo_id";
    }
    
    if (empty($atualizacoes)) {
        fecharBD($conexao);
        retornarJSON(false, 'Nenhum campo para atualizar');
    }
    
    $query = "UPDATE produtos SET " . implode(', ', $atualizacoes) . " WHERE id = $id";
    
    if ($conexao->query($query)) {
        fecharBD($conexao);
        retornarJSON(true, 'Produto atualizado com sucesso');
    } else {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao atualizar produto: ' . $conexao->error);
    }
}

/**
 * Deletar produto
 */
function deletarProduto() {
    if (empty($_POST['id'])) {
        retornarJSON(false, 'ID do produto não fornecido');
    }
    
    $id = (int)$_POST['id'];
    $conexao = conectarBD();
    
    // Verificar se produto existe
    $resultado = $conexao->query("SELECT id FROM produtos WHERE id = $id");
    if (!$resultado || $resultado->num_rows === 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Produto não encontrado');
    }
    
    // Verificar se há vendas com este produto
    $resultado = $conexao->query("SELECT COUNT(*) as total FROM itens_venda WHERE produto_id = $id");
    $linha = $resultado->fetch_assoc();
    
    if ($linha['total'] > 0) {
        fecharBD($conexao);
        retornarJSON(false, 'Não é possível deletar produto com vendas associadas');
    }
    
    $query = "DELETE FROM produtos WHERE id = $id";
    
    if ($conexao->query($query)) {
        fecharBD($conexao);
        retornarJSON(true, 'Produto deletado com sucesso');
    } else {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao deletar produto: ' . $conexao->error);
    }
}

/**
 * Listar grupos
 */
function listarGrupos() {
    $conexao = conectarBD();
    
    $query = "SELECT id, nome FROM grupos WHERE ativo = 1 ORDER BY nome ASC";
    $resultado = $conexao->query($query);
    
    if (!$resultado) {
        fecharBD($conexao);
        retornarJSON(false, 'Erro ao listar grupos: ' . $conexao->error);
    }
    
    $grupos = [];
    while ($linha = $resultado->fetch_assoc()) {
        $grupos[] = [
            'id' => (int)$linha['id'],
            'nome' => $linha['nome']
        ];
    }
    
    fecharBD($conexao);
    retornarJSON(true, 'Grupos listados com sucesso', $grupos);
}
?>
