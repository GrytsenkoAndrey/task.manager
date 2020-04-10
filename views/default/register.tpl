<div class="page-authorization"> <!-- container -->
    <!-- row 1
<div class="row flogin"> -->
<form class="custom-form" method="POST" action="/user/register/">
    <!-- <a href="/" title="Task Manager :: Main"><img class="mb-4" src="/img/home-icon.png" alt="Home page" width="72" height="72"></a> -->
    <h1 class="h h--1">Регистрация</h1>
    <label for="sname">Фамилия</label>
    <input type="text" class="custom-form__input" placeholder="Фамилия" name="sname" required autofocus>
    <br>
    <label for="name">Имя</label>
    <input type="text" class="custom-form__input" placeholder="Имя" name="name" required>
    <br>
    <label for="lname">Отчество</label>
    <input type="text" class="custom-form__input" placeholder="Отчество" name="lname" required>
    <br>
    <label for="login">E-mail</label>
    <input type="email" class="custom-form__input" placeholder="E-mail" name="login" required>
    <br>
    <label for="phone">Телефон</label>
    <input type="text" class="custom-form__input" placeholder="Телефон" name="phone" required>
    <br>
    <label for="pass">Пароль</label>
    <input type="password" id="inputPassword" class="custom-form__input" placeholder="Пароль" name="pass" required>
    <div class="checkbox mb-3">
        <label>
        <input type="checkbox" value="note" name="notf"> Уведомления почтой
        </label>
    </div>
    <button class="button" type="submit" name="sub">Регистрация</button>
</form>
<!--</div>  end flogin btn btn-lg btn-primary btn-block-->

</div> <!-- end container -->

<!-- javascript -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>