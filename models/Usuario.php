<?php
// models/Usuario.php
// Conecta-se ao banco de dados real
require_once __DIR__ . '/../config/configBanco.php';

class Usuario {

    /**
     * Valida o login de um usuário contra o banco de dados.
     * @param string $email
     * @param string $senha
     * @return array|false Retorna os dados do usuário (da tabela 'clientes') ou false.
     */
    public static function validarLogin($email, $senha) {
        global $conn;
        
        try {
            $sql = "SELECT * FROM clientes WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se o usuário existe E se a senha bate com o hash salvo
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Remove a senha do array antes de retornar
                unset($usuario['senha']); 
                return $usuario;
            }
            
            return false;

        } catch (Exception $e) {
            // Logar erro: error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Registra um novo cliente e seu endereço principal.
     * @param array $dados Os dados vindos do formulário (nome, email, senha, celular, cpf, endereco)
     * @return array|false Retorna os dados do novo usuário ou false em caso de falha.
     */
    public static function registrar($dados) {
        global $conn;

        // 1. Validar se o e-mail já existe
        if (self::getByEmail($dados['email'])) {
            throw new Exception("O e-mail informado já está cadastrado.");
        }
        
        // 2. Hashear a senha (NUNCA salve senha em texto plano)
        $hashSenha = password_hash($dados['senha'], PASSWORD_BCRYPT);

        // 3. Iniciar transação
        $conn->beginTransaction();

        try {
            // 4. Inserir na tabela 'clientes'
            $sqlCliente = "INSERT INTO clientes (nome, email, senha, telefone, cpf) 
                           VALUES (:nome, :email, :senha, :celular, :cpf)";
            
            $stmtCliente = $conn->prepare($sqlCliente);
            $stmtCliente->execute([
                ':nome' => $dados['nome'],
                ':email' => $dados['email'],
                ':senha' => $hashSenha, // Salva a senha HASHED
                ':celular' => $dados['celular'],
                ':cpf' => $dados['cpf']
            ]);

            // 5. Pegar o ID do cliente recém-criado
            $idCliente = $conn->lastInsertId();

            // 6. Inserir na tabela 'enderecos' (usando sua estrutura com 'logradouro')
            $end = $dados['endereco']; 
            
            $sqlEndereco = "INSERT INTO enderecos (id_cliente, logradouro, numero, complemento, bairro, cidade, estado, cep)
                            VALUES (:id, :logradouro, :numero, :complemento, :bairro, :cidade, :estado, :cep)";
            
            $stmtEndereco = $conn->prepare($sqlEndereco);
            $stmtEndereco->execute([
                ':id'          => $idCliente,
                ':logradouro'  => $end['logradouro'],
                ':numero'      => $end['numero'],
                ':complemento' => $end['complemento'],
                ':bairro'      => $end['bairro'],
                ':cidade'      => $end['cidade'],
                ':estado'      => $end['estado'],
                ':cep'         => $end['cep']
            ]);

            // 7. Se tudo deu certo, confirma a transação
            $conn->commit();

            // 8. Retorna os dados do novo usuário (sem a senha)
            return [
                'id_cliente' => $idCliente,
                'nome' => $dados['nome'],
                'email' => $dados['email']
            ];

        } catch (Exception $e) {
            // 9. Se algo deu errado, desfaz tudo
            $conn->rollBack();
            error_log($e->getMessage()); // É bom logar o erro real
            throw new Exception("Erro ao finalizar o cadastro no banco de dados.");
        }
    }

    /**
     * Busca um usuário (cliente) pelo e-mail.
     * @param string $email
     * @return array|false
     */
    public static function getByEmail($email) {
        global $conn;
        try {
            $sql = "SELECT id_cliente, nome, email FROM clientes WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Busca dados completos de um cliente (perfil) pelo ID.
     * @param int $idCliente
     * @return array|false
     */
    public static function getById($idCliente) {
        global $conn;
        try {
            // 1. Buscar dados do cliente
            $sqlCliente = "SELECT id_cliente, nome, email, telefone, cpf 
                           FROM clientes 
                           WHERE id_cliente = :id LIMIT 1";
            $stmtCliente = $conn->prepare($sqlCliente);
            $stmtCliente->bindParam(':id', $idCliente, PDO::PARAM_INT);
            $stmtCliente->execute();
            $usuario = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return false; // Usuário não encontrado
            }

            // 2. Buscar endereços do cliente
            $sqlEnderecos = "SELECT logradouro, numero, complemento, bairro, cidade, estado, cep 
                             FROM enderecos 
                             WHERE id_cliente = :id";
            $stmtEnderecos = $conn->prepare($sqlEnderecos);
            $stmtEnderecos->bindParam(':id', $idCliente, PDO::PARAM_INT);
            $stmtEnderecos->execute();
            $enderecos = $stmtEnderecos->fetchAll(PDO::FETCH_ASSOC);

            // 3. Adicionar os endereços ao array do usuário
            $usuario['enderecos'] = $enderecos;

            return $usuario;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza os dados de perfil de um cliente.
     * @param int $idCliente
     * @param array $dados Os dados a serem atualizados (nome, email, telefone, etc.)
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public static function atualizarPerfil($idCliente, $dados) {
        global $conn;

        try {
            $sql = "UPDATE clientes SET ";
            $campos = [];
            $parametros = [':id_cliente' => $idCliente];

            if (isset($dados['nome'])) {
                $campos[] = "nome = :nome";
                $parametros[':nome'] = $dados['nome'];
            }
            if (isset($dados['email'])) {
                // Opcional: Verificar se o novo email já existe para outro usuário
                // $existingUser = self::getByEmail($dados['email']);
                // if ($existingUser && $existingUser['id_cliente'] != $idCliente) {
                //     throw new Exception("Este e-mail já está em uso por outro usuário.");
                // }
                $campos[] = "email = :email";
                $parametros[':email'] = $dados['email'];
            }
            if (isset($dados['telefone'])) {
                $campos[] = "telefone = :telefone";
                $parametros[':telefone'] = $dados['telefone'];
            }
            // Adicione outros campos que você permite atualizar aqui (ex: cpf)
            // if (isset($dados['cpf'])) { ... }

            if (empty($campos)) {
                return false; // Nenhum dado para atualizar
            }

            $sql .= implode(", ", $campos);
            $sql .= " WHERE id_cliente = :id_cliente";

            $stmt = $conn->prepare($sql);
            $stmt->execute($parametros);

            return $stmt->rowCount() > 0; // Retorna true se alguma linha foi afetada

        } catch (Exception $e) {
            error_log("Erro no Model Usuario::atualizarPerfil: " . $e->getMessage());
            // Lança a exceção para que o controller possa pegá-la
            throw $e; 
        }
    }
}