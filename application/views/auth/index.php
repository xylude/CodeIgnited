<div class="section">
    <div class="padded_20 fullborder">
        <?= (isset($message)) ? '<p>' . $message . '</p>' : ''; ?>
        <form method="post" action="">
            <p>
                <label for="username">Username</label>
                <input type="text" name="username" value="Username" class="clearField" />
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" id="new_password_input" name="password" />
            </p>
            <p>
                <input type="submit" class="button" value="Login" />
            </p>
        </form>
    </div>
</div>