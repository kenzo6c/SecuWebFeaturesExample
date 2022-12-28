
<?php
require("log.php");

class Security
{

    private $CSRFTokenName = "CSRFtoken";

    public function __construct($nbrOfAttempts, $CSRFTokenLength)
    {
        $this->server = &$_SERVER;
        $this->session = &$_SESSION;
        $this->post = &$_POST;
        $this->CSRFTokenLength = $CSRFTokenLength;

        $this->logger = new Logger();

        if (!isset($this->session["loggedin"])) $this->session["loggedin"] = false;
        if (!isset($this->session["nbrOfAttempts"])) $this->session["nbrOfAttempts"] = $nbrOfAttempts;
    }

    private function resetCSRFToken()
    {
        $this->session[$this->CSRFTokenName] = bin2hex(openssl_random_pseudo_bytes($this->CSRFTokenLength));
    }

    private function getCSRFToken()
    {
        if (empty($this->session[$this->CSRFTokenName]))
        {
            $this->resetCSRFToken();
        }
        return $this->session[$this->CSRFTokenName];
    }

    public function preventXSS($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function insertCSRFField()
    {
        echo '<input type="hidden" name="'. $this->CSRFTokenName . '" value="' . $this->preventXSS($this->getCSRFToken()) . '">';
    }

    public function isTokenValid()
    {
        if (!isset($this->session[$this->CSRFTokenName]))
        {
            return false;
        }

        if (empty($this->post["submit"]) || empty($this->post[$this->CSRFTokenName]))
        {
            return false;
        }

        $token = $this->post[$this->CSRFTokenName];

        if (!is_string($token))
        {
            return false;
        }

        return hash_equals($token, $this->session[$this->CSRFTokenName]);
        // $_POST["CSRFtoken"] == $_SESSION["CSRFtoken"] ???
    }

    public function isFormValid($formName, $fields)
    {
        if (!$this->isTokenValid())
        {
            return false;
        }
        echo "Token is valid <br/>";

        if (empty($this->post[$formName]))
        {
            return false;
        }

        foreach($fields as $field)
        {
            if (empty($this->post[$formName][$field]))
            {
                return false;
            }
        }

        return true;
    }

    public function authUser($user)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        $loginOk = false;
        foreach ($accountsData as $account) {
            if ($user["username"] === $account["username"])
            {
                if (password_verify($user["password"], $account["hash"]))
                {
                    $loginOk = true;
                }
                break;
            }
        }

        if ($loginOk)
        {
            $this->logger->printlog('User "' . $user["username"] . '" connected');

            $this->session["user"] = $user["username"];
            $this->session["loggedin"] = true;
            return true;

        }
        return false;
    }

    public function verifyPassword($username, $password)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        if (password_verify($password, $accountsData[$username]["hash"]))
        {
            return true;
        }

        return false;
    }

    public function changePassword($username, $newPassword, $hashAlgorithm)
    {
        $this->logger->printlog('User "' . $username . '" changed his password.');
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        $accountsData[$username]["hash"] = password_hash($newPassword, $hashAlgorithm);

        file_put_contents("data/accounts.json", json_encode($accountsData));
    }

    public function checkAccess($requiredLevel)
    {
        if (empty($this->session["level"]) || !is_numeric($this->session["level"]) || $this->session["level"] < $requiredLevel)
        {
            header("Location: index.php");
            exit();
        }
    }

    public function disconnect()
    {
        if (!empty($this->session["user"]))
        {
            $this->logger->printlog('User "' . $this->session["user"] . '" disconnected');
        }
        $this->session["user"] = null;
        $this->session["loggedin"] = false;
        $this->resetCSRFToken();
    }
}
?>