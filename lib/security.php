
<?php
class Security
{

    private $CSRFTokenName = "CSRFtoken";

    public function __construct($nbrOfAttempts)
    {
        $this->server = &$_SERVER;
        $this->session = &$_SESSION;
        $this->post = &$_POST;

        if (!isset($this->session["loggedin"])) $this->session["loggedin"] = false;
        if (!isset($this->session["nbrOfAttempts"])) $this->session["nbrOfAttempts"] = $nbrOfAttempts;
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

    public function resetAccess()
    {
        $this->session["user"] = null;
        $this->session["loggedin"] = false;
    }
}
?>