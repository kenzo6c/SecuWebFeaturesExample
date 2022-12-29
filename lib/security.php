
<?php
require("log.php");

class Security
{

    private $CSRFTokenName = "CSRFtoken";

    public function __construct($config)
    {
        $this->server = &$_SERVER;
        $this->session = &$_SESSION;
        $this->post = &$_POST;

        $this->logger = new Logger();
        $this->config = &$config;

        if (!isset($this->session["loggedin"])) $this->session["loggedin"] = false;
        if (!isset($this->session["maxAttemptsSession"])) $this->session["maxAttemptsSession"] = $config["maxAttemptsSession"];
        if (!isset($this->session["waitingTime"])) $this->session["waitingTime"] = 0;
    }

    private function resetCSRFToken()
    {
        $this->session[$this->CSRFTokenName] = bin2hex(openssl_random_pseudo_bytes($this->config["CSRFTokenLength"]));
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

        if (empty($this->post[$this->CSRFTokenName]))
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

    public function isFormValidLog($formName, $fields)
    {
        $result = $this->isFormValid($formName, $fields);
        if (!$result)
        {
            $this->logger->footerLog("Formulaire invalide.", "danger");
        }
        return $result;
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
            $this->logger->printLog('User "' . $user["username"] . '" connected');

            $this->session["user"] = $user["username"];
            $this->session["access"] = $accountsData[$user["username"]]["access"];
            $this->session["is_root"] = $accountsData[$user["username"]]["is_root"];
            $this->session["loggedin"] = true;
            $this->resetAttempts($user["username"]);

            return true;

        }
        return false;
    }

    public function checkIfUserExists($username)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        if (isset($accountsData[$username]))
        {
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

    public function passwordWeakness($password)
    {
        if (strlen($password) < $this->config["passwordminlength"])
        {
            return "Trop court. (minimum " . $this->config["passwordminlength"] . " caractères).";
        }
        if (strlen($password) > $this->config["passwordmaxlength"])
        {
            return "Trop long. (maximum " . $this->config["passwordmaxlength"] . " caractères).";
        }
        if ($this->config["requireDigit"] && !preg_match("#[0-9]+#", $password))
        {
            return "Doit inclure au moins un chiffre.";
        }
        if ($this->config["requireLetter"] && !preg_match("#[a-zA-Z]+#", $password))
        {
            return "Doit inclure au moins une lettre.";
        }
        if ($this->config["requireSymbol"] && !preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $password))
        {
            return "Doit inclure au moins un caractère spécial.";
        }
        return "None";
    }

    public function changePassword($username, $newPassword, $hashAlgorithm, $forced=false)
    {
        if ($forced)
        {
            $this->logger->printLog('An administrator has changed the password of "' . $username . '".');
        }
        else
        {
            $this->logger->printLog('User "' . $username . '" has changed his password.');
        }
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        $accountsData[$username]["hash"] = password_hash($newPassword, $hashAlgorithm);
        $hashInfo = password_get_info($accountsData[$username]["hash"]);
        $accountsData[$username]["algoPHP"] = $hashInfo["algo"];
        $accountsData[$username]["algoHuman"] = $hashInfo["algoName"];

        file_put_contents("data/accounts.json", json_encode($accountsData));

        $this->resetAttempts($username);
    }

    public function userIsRoot($username)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        if ($accountsData[$username]["is_root"])
        {
            return true;
        }
        return false;
    }

    public function hasAccess($requiredAccess)
    {
        if ($this->session["is_root"] || (!empty($_SESSION["access"]) && in_array($requiredAccess, $this->session["access"], true)))
        {
            return true;
        }
        return false;
    }

    public function hasAttempts($username)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        if (!empty($accountsData[$username]) && $accountsData[$username]["attempts_left"] <= 0)
        {
            return false;
        }
        return true;
    }

    public function waitingTimeLeft()
    {
        if (empty($this->session["waitingTime"]) || $this->session["waitingTime"] === 0)
        {
            return 0;
        }
        if (time() > $this->session["waitingTime"])
        {
            $this->session["waitingTime"] = 0;
            return 0;
        }
        return $this->session["waitingTime"] - time();
    }

    public function decrementAttempts($username, $log = true)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);

        if (!empty($accountsData[$username]) && !$accountsData[$username]["is_root"])
        {
            $accountsData[$username]["attempts_left"] -= 1;
            file_put_contents("data/accounts.json", json_encode($accountsData));
            $this->logger->printLog('User "' . $username . '" has ' . $accountsData[$username]["attempts_left"] . ' attempts left after decrement.');
        }
        else
        {
            $this->logger->printLog('User "' . $username . '" not found (or is an administrator) => no decrement.');
        }

        $this->session["waitingTime"] = time() + $this->config["attemptsWaitTime"];

        if ($log && !empty($accountsData[$username]) && !$accountsData[$username]["is_root"])
        {
            $this->logger->footerLog("Essai(s) restant(s) : " . $accountsData[$username]["attempts_left"]);
        }

        return $accountsData[$username]["attempts_left"];
    }

    public function resetAttempts($username)
    {
        $accountsData = json_decode(file_get_contents("data/accounts.json"), true);
        $accountsData[$username]["attempts_left"] = $this->config["maxAttemptsAccount"];
        file_put_contents("data/accounts.json", json_encode($accountsData));
    }

    public function disconnect()
    {
        if (!empty($this->session["user"]))
        {
            $this->logger->printLog('User "' . $this->session["user"] . '" disconnected');
        }
        $this->session["user"] = null;
        $this->session["access"] = null;
        $this->session["is_root"] = false;
        $this->session["loggedin"] = false;
        $this->resetCSRFToken();
    }
}
?>