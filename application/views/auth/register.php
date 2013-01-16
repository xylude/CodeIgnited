<?php

/* 
    Document   : register
    Created on : May 30, 2012, 8:43:38 AM
    Author     : Jason Crider
    Description:
        
*/
?>
<div class="section">
    <div class="padded_20">
        <?= (isset($message))?'<p>'.$message.'</p>':''; ?>
        <form method="post" action="">
            <p>
                <label for="username">Username</label>
                <input type="text" name="username" />
            </p>
            <p>
                <label for="email">Email</label>
                <input type="text" name="email" />
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" name="password" />
            </p>
            <p>
                <label for="rpassword">Repeat Password</label>
                <input type="password" name="rpassword" />
            </p>
            <p>
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" />
            </p>
            <p>
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" />
            </p>
            <p>
                <input type="submit" class="button" value="Register" />
            </p>
        </form>
    </div>
</div>