<main class="page-edit">
  {$infoMsg}
  <h1 class="h h--1">Редактирование товара</h1>
  <form class="custom-form" method="post" enctype="multipart/form-data">
    <fieldset class="page-edit__group custom-form__group">
      <legend class="page-edit__small-title custom-form__title">Данные о товаре</legend>
      <label for="product-name" class="custom-form__input-wrapper page-edit__first-wrapper">
        <input type="text" class="custom-form__input" name="product-name" id="product-name" value="{$rsProduct['title']}">
      </label>
      <label for="product-price" class="custom-form__input-wrapper">
        <input type="text" class="custom-form__input" name="product-price" id="product-price" value="{$rsProduct['price']}">
      </label>
    </fieldset>
    <fieldset class="page-edit__group custom-form__group">
      <legend class="page-edit__small-title custom-form__title">Фотография товара</legend>
      <ul class="edit-list">
        <li class="edit-list__item edit-list__item--edit" hidden>
          <input type="file" name="product-photo" id="product-photo" hidden="">
          <label for="product-photo">Изменить фотографию</label>
        </li>
        <li class="edit-list__item edit-list__item--active">
            <img src="{$templateWebPath}img/products/{$rsProduct['logo']}" id="current-photo" alt="{$rsProduct['logo']}">
        </li>
      </ul>
    </fieldset>
    <fieldset class="page-edit__group custom-form__group">
      <legend class="page-edit__small-title custom-form__title">Раздел</legend>
      <div class="page-edit__select">
        <select name="category" class="custom-form__select" id="product-category">
          <option hidden="">Название раздела</option>
          {foreach $rsCategories as $item}
              {if $item['category'] == $rsProduct['ctgry'] }
                  <option value="{$item['category']}" selected>{$item['description']}</option> 
              {else}
                  <option value="{$item['category']}">{$item['description']}</option>
              {/if}
          {/foreach}
        </select>
      </div>
      <fieldset class="page-edit__group custom-form__group">
      <label for="product-qnt" class="custom-form__input-wrapper page-edit__first-wrapper">
        <input type="text" class="custom-form__input" name="product-qnt" id="product-qnt" value="{$rsProduct['quantity']}">
      </label>
      </fieldset>
      <input type="checkbox" name="new" id="new" class="custom-form__checkbox"  {$rsProduct['new_item']}>
      <label for="new" class="custom-form__checkbox-label" name="new" value="on">Новинка</label>
      <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox" {$rsProduct['top_item']}>
      <label for="sale" class="custom-form__checkbox-label" name="sale" value="on" >Распродажа</label>
    </fieldset>
    <input type="text" hidden name="id" id="id" value="{$rsProduct['id']}">
    <button class="button" type="submit">Изменить товар</button>
  </form>

  <section class="shop-page__popup-end page-add__popup-end" hidden>
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно изменен</h2>
    </div>
  </section>
</main>