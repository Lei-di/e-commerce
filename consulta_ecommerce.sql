-- Consultas SQL para ecommerce_elegance

-- 1. Listar todos os clientes cadastrados
SELECT id_cliente, nome, email, telefone, cpf
FROM clientes;

-- 2. Listar produtos com categoria e quantidade em estoque
SELECT 
  p.id_produto,
  p.nome AS produto,
  c.nome AS categoria,
  p.preco,
  e.quantidade AS estoque
FROM produtos p
JOIN categorias c ON p.id_categoria = c.id_categoria
JOIN estoque e ON p.id_produto = e.id_produto
ORDER BY p.nome;

-- 3. Listar pedidos com cliente e valor total
SELECT 
  ped.id_pedido,
  c.nome AS cliente,
  ped.data_pedido,
  ped.status,
  SUM(ip.quantidade * ip.preco_unitario) AS total_pedido
FROM pedidos ped
JOIN clientes c ON ped.id_cliente = c.id_cliente
JOIN itens_pedido ip ON ped.id_pedido = ip.id_pedido
GROUP BY ped.id_pedido
ORDER BY ped.data_pedido DESC;

-- 4. Produtos mais vendidos (por quantidade)
SELECT 
  p.nome AS produto,
  SUM(ip.quantidade) AS total_vendido
FROM itens_pedido ip
JOIN produtos p ON ip.id_produto = p.id_produto
GROUP BY p.id_produto
ORDER BY total_vendido DESC;

-- 5. Valor total de vendas por cliente
SELECT 
  c.nome AS cliente,
  SUM(ip.quantidade * ip.preco_unitario) AS total_gasto
FROM clientes c
JOIN pedidos ped ON c.id_cliente = ped.id_cliente
JOIN itens_pedido ip ON ped.id_pedido = ip.id_pedido
GROUP BY c.id_cliente
ORDER BY total_gasto DESC;

-- 6. Vendas por mês (faturamento mensal)
SELECT 
  DATE_FORMAT(ped.data_pedido, '%Y-%m') AS mes,
  SUM(ip.quantidade * ip.preco_unitario) AS total_vendas
FROM pedidos ped
JOIN itens_pedido ip ON ped.id_pedido = ip.id_pedido
GROUP BY DATE_FORMAT(ped.data_pedido, '%Y-%m')
ORDER BY mes DESC;

-- 7. Avaliação média de cada produto
SELECT 
  p.nome AS produto,
  ROUND(AVG(a.nota),2) AS media_avaliacao,
  COUNT(a.id_avaliacao) AS qtd_avaliacoes
FROM produtos p
LEFT JOIN avaliacoes a ON p.id_produto = a.id_produto
GROUP BY p.id_produto
ORDER BY media_avaliacao DESC;

-- 8. Lista de desejos (clientes x produtos)
SELECT 
  c.nome AS cliente,
  p.nome AS produto_desejado,
  l.data_adicionado
FROM lista_desejos l
JOIN clientes c ON l.id_cliente = c.id_cliente
JOIN produtos p ON l.id_produto = p.id_produto
ORDER BY c.nome;

-- 9. Pagamentos pendentes ou não aprovados
SELECT 
  ped.id_pedido,
  c.nome AS cliente,
  pag.forma_pagamento,
  pag.valor,
  pag.status
FROM pagamentos pag
JOIN pedidos ped ON pag.id_pedido = ped.id_pedido
JOIN clientes c ON ped.id_cliente = c.id_cliente
WHERE pag.status <> 'Aprovado';
