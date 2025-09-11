<?php
class Usuario {
    private static $usuarios = [
        [
            "id" => 1,
            "nome" => "Admin",
            "email" => "admin@elegance.com",
            "senha" => "123456"
        ]
    ];

    public static function getAll() {
        return self::$usuarios;
    }

    public static function getByEmail($email) {
        foreach (self::$usuarios as $usuario) {
            if ($usuario["email"] === $email) {
                return $usuario;
            }
        }
        return null;
    }

    public static function registrar($nome, $email, $senha) {
        if (self::getByEmail($email)) {
            return false;
        }
        $novoUsuario = [
            "id" => count(self::$usuarios) + 1,
            "nome" => $nome,
            "email" => $email,
            "senha" => $senha
        ];

        self::$usuarios[] = $novoUsuario;
        return $novoUsuario;
    }

    /**
     * Valida o login de um usuário.
     * @param string $email
     * @param string $senha
     * @return array|false Retorna os dados do usuário em caso de sucesso ou false em caso de falha.
     */
    public static function validarLogin($email, $senha) {
        $usuario = self::getByEmail($email);
        if ($usuario && $usuario['senha'] === $senha) {
            return $usuario;
        }
        return false;
    }
}