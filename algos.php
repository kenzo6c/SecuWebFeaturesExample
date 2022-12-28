<?php
    print_r(password_algos());
    if(PASSWORD_BCRYPT === password_algos()[0])
    {
        print("yes");
    }
    else
    {
        print("no");
    }

    // algo = ...
    // if algo === PASSWORD_BCRYPT
    // print("bcrypt")
    // ....
?>