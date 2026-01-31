<?php
use Google\Client;

class Usuarios extends LiteRecord
{
    public function identificarseConGoogle(): bool
    {
        $keys = Config::get('keys.google.oauth2');
        $keys = json_decode($keys, true)['web'];
        
        $credentialToken = $_POST['credential'] ?? null;
        if (!$credentialToken) {
            return false;
        }

        if (!PRODUCTION && $credentialToken === 'DEV_TOKEN_GOOGLE') {
            $payload = ['email' => 'distrotuz@gmail.com'];
        } else {
            $client = new Google\Client();
            $client->setClientId($keys['client_id']);
            $client->setClientSecret($keys['client_secret']);
            $payload = $client->verifyIdToken($credentialToken);
        }

        if ($payload && !empty($payload['email'])) {
            $sql = "SELECT * FROM usuarios WHERE email=? AND confirmado=1";
            $user = self::first($sql, [$payload['email']]);
            
            if ($user) {
                $this->updateActivity($user->id);
                $this->setSession($user);
                return true;
            }
        }

        return false;
    }

    public function identificarse(array $post): bool|object
    {
        // 1. Honeypot check
        if (!empty($post['nombre']) || !empty($post['apellidos'])) {
            return false;
        }

        // 2. Validate Email
        $email = filter_var($post['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            Flash::error('Email inválido.');
            return false;
        }

        // 3. Find User
        $sql = "SELECT * FROM usuarios WHERE email=? AND confirmado=1";
        $user = self::first($sql, [$email]);
        if (!$user) {
            Flash::error('Credenciales incorrectas.');
            return false;
        }

        // 4. Verify Password
        if (!password_verify($post['clave'], $user->la_clave)) {
            Flash::error('Credenciales incorrectas.');
            return false;
        }

        // 5. Update Activity & Session
        $this->updateActivity($user->id);
        $this->setSession($user);

        return $user;
    }

    private function updateActivity(int $id): void
    {
        $this->set_('tocado=?, ip=?, browser=?')
            ->where('id=?')
            ->vals([
                date('Y-m-d H:i:s'),
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $id // For the where clause
            ])
            ->upd();
    }

    private function setSession(object $user): void
    {
        Session::set('idu', $user->idu);
    }
}
