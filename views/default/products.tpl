<main class="page-products">
  {$infoMsg}
  <h1 class="h h--1">Товары</h1>
  <a class="page-products__button button" href="/admin/addprod/">Добавить товар</a>
  <div class="page-products__header">
    <span class="page-products__header-field">Название товара</span>
    <span class="page-products__header-field">ID</span>
    <span class="page-products__header-field">Цена</span>
    <span class="page-products__header-field">Категория</span>
    <span class="page-products__header-field">Новинка</span>
  </div>
  <ul class="page-products__list">
  {foreach $rsProducts as $item}
    <li class="product-item page-products__item">
      <b class="product-item__name">{$item['title']}</b>
      <span class="product-item__field">{$item['id']}</span>
      <span class="product-item__field">{$item['price']} руб.</span>
      <span class="product-item__field">{$item['ctgry']}</span>
      <span class="product-item__field">
      {if $item['new_item'] == 1}
      Да
      {else}
      Нет
      {/if}
      </span>
      <span hidden class="prod-id">{$item['id']}</span>
      <a href="/admin/editprod/id/{$item['id']}" class="product-item__edit" aria-label="Редактировать"></a>
      <button class="product-item__delete"></button>
    </li>
  {/foreach}
  </ul>
  
  <section class='pag__area'> 
  {include file='pagination.tpl'}
  </section>
</main>