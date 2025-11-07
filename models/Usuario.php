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
                ':senha' => $hashSenha,
                ':celular' => $dados['celular'],
                ':cpf' => $dados['cpf']
            ]);

            // 5. Pegar o ID do cliente recém-criado
            $idCliente = $conn->lastInsertId();

            // 6. Inserir na tabela 'enderecos' (CONSULTA CORRIGIDA)
            $end = $dados['endereco']; // Apenas para encurtar
            
            // Query corrigida para bater com a sua tabela: logradouro, complemento, etc.
            $sqlEndereco = "INSERT INTO enderecos (id_cliente, logradouro, numero, complemento, bairro, cidade, estado, cep)
                            VALUES (:id, :logradouro, :numero, :complemento, :bairro, :cidade, :estado, :cep)";
            
            $stmtEndereco = $conn->prepare($sqlEndereco);
            $stmtEndereco->execute([
                ':id'          => $idCliente,
                ':logradouro'  => $end['logradouro'],
                ':numero'      => $end['numero'],
                ':complemento' => $end['complemento'], // Campo adicionado
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
            // error_log($e->getMessage()); // É bom logar o erro real
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
}