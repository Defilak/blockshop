<?php

class User extends ActiveRecordEntity
{
    public $username;
    public $hash;

    public function getEconomy(): Economy
    {
        return Economy::where('username', $this->username);
    }

    public function getBanEntry(): ?Banlist
    {
        return Banlist::where('name', $this->username);
    }

    protected static function getTableName(): string
    {
        return 'users';
    }
}


/*const nominal = 0;

class User
{
    private $data;

    private function __construct(array $userData = [])
    {
        $this->data = $userData;
    }

    //magic
    public function __set($property, $value)
    {
        return $this->_data[$property] = $value;
    }

    //magic
    public function __get($property)
    {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null;
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['shopname']);
    }

    public static function login($login, $password)
    {
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $login) && preg_match('/^[a-zA-Z0-9_-]+$/', $password)) {
            $stmt = DB::prepare("SELECT * FROM users WHERE username = :username AND hash = :hash")
                ->bindValue(':username', $login)
                ->bindValue(':hash', sha1($password))
                ->execute();

            if (!empty($stmt->fetch())) {
                $user = new User([
                    'id' => $stmt->id,
                    'name' => $stmt->name
                ]);
                
                $_SESSION['shopname'] = $user->name;
                return $user;
            }

            return false;
        }
    }
}
/*
SELECT users.id, users.hash, iconomy.balance, iconomy.money, iconomy.group, iconomy.bancount, iconomy.buys FROM users
INNER JOIN iconomy ON iconomy.username = users.username
WHERE users.username = 'defi' AND users.hash = '601f1889667efaebb33b8c12572835da3f027f78'
*/