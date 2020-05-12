<main class="page-add">
  {$infoMsg}
  <h1 class="h h--1">Добавление товара</h1>
  <form class="custom-form" method="post" enctype="application/x-www-form-urlencoded"> <!-- action="/admin/addprod/" -->
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
      <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" name="product-name" id="product-name">
        <p class="custom-form__input-label">
          Название товара
        </p>
      </label>
      <label for="product-price" class="custom-form__input-wrapper">
        <input type="text" class="custom-form__input" name="product-price" id="product-price">
        <p class="custom-form__input-label">
          Цена товара
        </p>
      </label>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
      <ul class="add-list">
        <li class="add-list__item add-list__item--add">
          <input type="file" id="product-photo" name="product-photo" id="product-photo" hidden="">
          <label for="product-photo">Добавить фотографию</label>
        </li>
      </ul>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Раздел</legend>
      <div class="page-add__select">
        <select name="category" class="custom-form__select" id="product-category">
          <option hidden="">Название раздела</option>
          <option value="women">Женщины</option>
          <option value="men">Мужчины</option>
          <option value="children">Дети</option>
          <option value="accessories">Аксессуары</option>
        </select>
      </div>
      <fieldset class="page-add__group custom-form__group">
      <label for="product-qnt" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" name="product-qnt" id="product-qnt">
        <p class="custom-form__input-label">
          Количество товара
        </p>
      </label>
      </fieldset>
      <input type="checkbox" name="new" id="new" class="custom-form__checkbox">
      <label for="new" class="custom-form__checkbox-label" name="new" value="on">Новинка</label>
      <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox">
      <label for="sale" class="custom-form__checkbox-label" name="sale" value="on">Распродажа</label>
    </fieldset>
    <button class="button" type="submit">Добавить товар</button>
  </form>

  <section class="shop-page__popup-end page-add__popup-end" hidden>
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно добавлен</h2>
    </div>
  </section>
</main>