<main class="page-order">
{$infoMsg}
<h1 class="h h--1">Список заказов</h1>
  <ul class="page-order__list">
  {if count($rsOrders) <> 0 }
  {foreach $rsOrders as $item}
    <li class="order-item page-order__item">
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
          <span class="order-item__title">Номер заказа</span>
          <span class="order-item__info order-item__info--id">{$item['id']}</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Сумма заказа</span>
          {$item['payment_amount']} руб.
        </div>
        <button class="order-item__toggle"></button>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
          <span class="order-item__title">Заказчик</span>
          <span class="order-item__info">{$item['sname']} {$item['name']} {$item['fname']}</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Номер телефона</span>
          <span class="order-item__info">+{$item['phone']}</span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ доставки</span>
          <span class="order-item__info">
            {if $item['delivery'] == 1}
                Курьерская доставка
            {else}
                Самовывоз
            {/if}
          </span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ оплаты</span>
          <span class="order-item__info">
          {if $item['paytype'] == 1}
            Наличными
          {else}
            Карта
          {/if}
          </span>
        </div>
        <div class="order-item__group order-item__group--status">
          <span class="order-item__title">Статус заказа</span>
          {if $item['payment_status'] == 1 }
          <span class="order-item__info order-item__info--yes">Выполнено</span>
          {else}
          <span class="order-item__info order-item__info--no">Не выполнено</span>
          {/if}
          <button class="order-item__btn" onClick="javascript:chOrderStatus({$item['id']}, {$item['payment_status']});">Изменить</button>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Адрес доставки</span>
          <span class="order-item__info">г. Москва, ул. Пушкина, д.5, кв. 233</span>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Комментарий к заказу</span>
          <span class="order-item__info">{$item['comments']}</span>
        </div>
      </div>
    </li>
    {/foreach}
    {else}
    <p>Пока нет ни одного заказа</p>
    {/if}
  </ul>

  <section class='pag__area'> 
  {include file='pagination.tpl'}
  </section>
</main>