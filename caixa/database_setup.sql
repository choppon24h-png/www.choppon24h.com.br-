-- Estrutura do banco de dados para sistema de caixa
-- Banco: inlaud99_erpdist
-- Usuário: inlaud99_admin
-- Senha: Admin259087@

-- Tabela de Grupos/Categorias
CREATE TABLE IF NOT EXISTS grupos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL UNIQUE,
  descricao TEXT,
  ativo TINYINT(1) DEFAULT 1,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Produtos
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Vendas
CREATE TABLE IF NOT EXISTS vendas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero_venda INT UNIQUE AUTO_INCREMENT,
  data_venda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  valor_total DECIMAL(10, 2) DEFAULT 0,
  quantidade_itens INT DEFAULT 0,
  status VARCHAR(20) DEFAULT 'pendente',
  observacoes TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Itens da Venda
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir grupos de exemplo
INSERT INTO grupos (nome, descricao) VALUES 
('Eletrônicos', 'Produtos eletrônicos em geral'),
('Alimentos', 'Alimentos e bebidas'),
('Vestuário', 'Roupas e acessórios'),
('Higiene', 'Produtos de higiene e limpeza');

-- Inserir produtos de exemplo
INSERT INTO produtos (nome, codigo, codigo_barras, grupo_id, preco, estoque) VALUES 
('Notebook Dell', 'PROD001', '1234567890123', 1, 2500.00, 10),
('Mouse Logitech', 'PROD002', '1234567890124', 1, 85.00, 50),
('Teclado Mecânico', 'PROD003', '1234567890125', 1, 350.00, 25),
('Arroz Integral 5kg', 'PROD004', '1234567890126', 2, 25.00, 100),
('Feijão Carioca 1kg', 'PROD005', '1234567890127', 2, 8.50, 80),
('Camiseta Básica', 'PROD006', '1234567890128', 3, 45.00, 60),
('Calça Jeans', 'PROD007', '1234567890129', 3, 120.00, 40),
('Sabonete Neutro', 'PROD008', '1234567890130', 4, 5.00, 200),
('Detergente Neutro', 'PROD009', '1234567890131', 4, 3.50, 150);
