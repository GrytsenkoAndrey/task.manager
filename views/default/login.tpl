<main class="page-authorization">
{$infoMsg}
  <h1 class="h h--1">Авторизация</h1>
  <form class="custom-form" action="/user/login" method="post">
    <input type="email" class="custom-form__input" name="email" id="email" required="">
    <input type="password" class="custom-form__input" name="password" id="password" required="">
    <button class="button" type="submit">Войти в личный кабинет</button>
    <p><a href="/user/register" title="Регистрация">Регистрация</a></p>
  </form>
</main>