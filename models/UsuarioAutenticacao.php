<?php

class UsuarioAutenticacao {

    // Simulação de um banco de dados de usuários em um array
    private $usuariosMock = [
        ['id' => 1, 'nome' => 'Usuario Teste', 'email' => 'teste@email.com', 'senha' => 'senha123']
    ];

    /**
     * Simula o registro de um novo usuário.
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @return array
     */
    public function registrarUsuario($nome, $email, $senha) {
        // Em um cenário real, você validaria os dados e faria a inserção no banco de dados.
        // Aqui, apenas simulamos o sucesso.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Email inválido.'];
        }

        // Simula o registro de um novo usuário no array de mock
        $novoUsuario = [
            'id' => count($this->usuariosMock) + 1,
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha // Em um cenário real, a senha deveria ser hasheada!
        ];

        // Adiciona o novo usuário ao array temporário
        $this->usuariosMock[] = $novoUsuario;

        return ['status' => 'success', 'message' => 'Usuário registrado com sucesso.'];
    }

    /**
     * Simula a autenticação do usuário.
     * @param string $email
     * @param string $senha
     * @return array|false Retorna os dados do usuário em caso de sucesso ou false em caso de falha.
     */
    public function autenticarUsuario($email, $senha) {
        // Busca o usuário no array de mock
        foreach ($this->usuariosMock as $usuario) {
            if ($usuario['email'] === $email && $usuario['senha'] === $senha) {
                // Em um cenário real, a comparação de senha seria com password_verify()
                return $usuario;
            }
        }
        return false;
    }
}
