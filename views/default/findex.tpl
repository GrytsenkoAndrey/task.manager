{foreach $rsProducts as $item}
        <article class="shop__item product" tabindex="{$item['id']}">
          <div class="product__image">
            <img src="{$templateWebPath}img/products/{$item['logo']}" alt="{$item['title']}">
          </div>
          <p class="product__name">{$item['title']}</p>
          <span class="product__price">{$item['price']} руб.</span>
        </article>
        {/foreach}