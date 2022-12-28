
<?php
class Security
{

    private $CSRFTokenName = "CSRFtoken";

    public function __construct()
    {
        $this->server = &$_SERVER;
        $this->session = &$_SESSION;
        $this->post = &$_POST;
    }

    public function getCSRFToken()
    {
        if (empty($this->session[$this->CSRFTokenName]))
        {
            $this->session[$this->CSRFTokenName] = bin2hex(openssl_random_pseudo_bytes(64));
        }
        return $this->session[$this->CSRFTokenName];
    }

    public function insertCSRFField()
    {
        echo '<input type="hidden" name="'. $this->CSRFTokenName . '" value="' . $this->preventXSS($this->getCSRFToken()) . '">';
    }

    public function preventXSS($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function isAuthTokenValid()
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

    public function isAuthValid()
    {
        if (!$this->isAuthTokenValid())
        {
            return false;
        }
        echo "Token is valid <br/>";

        if (empty($this->post["userauth"]) || empty($this->post["userauth"]["username"]) || empty($this->post["userauth"]["password"]))
        {
            return false;
        }

        return true;
    }

    public function authUser()
    {
        $accountsData = json_decode(file_get_contents("accounts.json"), true);
        $user = $this->post["userauth"];

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
            $this->session["user"] = $user["username"];
            $this->session["loggedin"] = true;
            return true;
        }
        return false;
    }

    public function checkAccess($requiredLevel)
    {
        if (empty($this->session["level"]) || !is_numeric($this->session["level"]) || $this->session["level"] < $requiredLevel)
        {
            header("index.php");
            exit();
        }
    }

    public function resetAccess()
    {
        $this->session["user"] = null;
        $this->session["loggedin"] = false;
    }
}
?>