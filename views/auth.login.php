<?php self::layout('views/layout') ?>

<div class="form login-form">
    <form action="<?= url('/login') ?>" method="post">

        <header>NOBLE<strong>TEXT</strong></header>

        <div class="line">
            <input type="text" name="username" placeholder="username" />
        </div>

        <div class="line">
            <input type="password" name="password" placeholder="password" />
            <button type="submit">Go</button>
        </div>

    </form>
</div>