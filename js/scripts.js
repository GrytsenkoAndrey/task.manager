'use strict';

const toggleHidden = (...fields) => {

  fields.forEach((field) => {

    if (field.hidden === true) {

      field.hidden = false;

    } else {

      field.hidden = true;

    }
  });
};

const labelHidden = (form) => {

  form.addEventListener('focusout', (evt) => {

    const field = evt.target;
    const label = field.nextElementSibling;

    if (field.tagName === 'INPUT' && field.value && label) {

      label.hidden = true;

    } else if (label) {

      label.hidden = false;

    }
  });
};

const toggleDelivery = (elem) => {

  const delivery = elem.querySelector('.js-radio');
  const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
  const deliveryNo = elem.querySelector('.shop-page__delivery--no');
  const fields = deliveryYes.querySelectorAll('.custom-form__input');

  delivery.addEventListener('change', (evt) => {

    if (evt.target.id === 'dev-no') {

      fields.forEach(inp => {
        if (inp.required === true) {
          inp.required = false;
        }
      });


      toggleHidden(deliveryYes, deliveryNo);

      deliveryNo.classList.add('fade');
      setTimeout(() => {
        deliveryNo.classList.remove('fade');
      }, 1000);

    } else {

      fields.forEach(inp => {
        if (inp.required === false) {
          inp.required = true;
        }
      });

      toggleHidden(deliveryYes, deliveryNo);

      deliveryYes.classList.add('fade');
      setTimeout(() => {
        deliveryYes.classList.remove('fade');
      }, 1000);
    }
  });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

  filterWrapper.addEventListener('click', evt => {

    const filterList = filterWrapper.querySelectorAll('.filter__list-item');

    filterList.forEach(filter => {

      if (filter.classList.contains('active')) {

        filter.classList.remove('active');

      }

    });

    const filter = evt.target;

    filter.classList.add('active');

  });

}

/*   */

const shopList = document.querySelector('.shop__list');
if (shopList) {

    shopList.addEventListener('click', (evt) => {

        const prod = evt.path || (evt.composedPath && evt.composedPath());

    //-- формируем переменные с данными о товаре для передачи в форму заказа
    const prod_id_num = evt.target.querySelector('.prod-id-num');
    var prod_id = prod_id_num.getAttribute('alt');
    const prod_p_name = evt.target.querySelector('.product__name');
    var prod_name = prod_p_name.innerHTML;
    const prod_span_price = evt.target.querySelector('.product__price');
    var prod_price = prod_span_price.innerHTML;

    if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item')))
    {

      const shopOrder = document.querySelector('.shop-page__order');

      toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

      window.scroll(0, 0);

      shopOrder.classList.add('fade');
      setTimeout(() => shopOrder.classList.remove('fade'), 1000);

      const form = shopOrder.querySelector('.custom-form');

        // -- создаем элемент со значением prod_id
       /* console.log(prod_id);
        console.log(prod_name);
        console.log(prod_price);
        const id = form.querySelector('#prod_id');
        id.setAttribute('value', prod_id);
        const title = form.querySelector('#prod_title');
        title.setAttribute('value', prod_name);
        const price = form.querySelector('#prod_price');
        price.setAttribute('value', prod_price);
        // -- */

      labelHidden(form);
      toggleDelivery(shopOrder);

      const buttonOrder = shopOrder.querySelector('.button');
      const popupEnd = document.querySelector('.shop-page__popup-end');

      buttonOrder.addEventListener('click', (evt) => {

        form.noValidate = true;

        const inputs = Array.from(shopOrder.querySelectorAll('[required]'));

        inputs.forEach(inp => {

          if (!!inp.value) {

            if (inp.classList.contains('custom-form__input--error')) {
              inp.classList.remove('custom-form__input--error');
            }

          } else {

            inp.classList.add('custom-form__input--error');

          }
        });

        if (inputs.every(inp => !!inp.value)) {
             
          evt.preventDefault();
          // подготовка данных
          var surname = form.querySelector('#surname').value;
          var name = form.querySelector('#name').value;
          var thirdname = form.querySelector('#thirdName').value;
          var phone = form.querySelector('#phone').value;
          var email = form.querySelector('#email').value;
          var devno = form.querySelector('#dev-no').value;
          var devyes = form.querySelector('#dev-yes').value;
          var city = form.querySelector('#city').value;
          var street = form.querySelector('#street').value;
          var home = form.querySelector('#home').value;
          var aprt = form.querySelector('#aprt').value;
          var cash = form.querySelector('#cash').value;
          var card = form.querySelector('#card').value;
          var comment = form.querySelector('#comment').value;
          
          toggleHidden(shopOrder, popupEnd);
        // убираем пробелы и обозначение валюты из цены
          prod_price = prod_price.replace(' ', '');
          prod_price = prod_price.substr(0, prod_price.search(' '));
          console.log(prod_price);
          /* ajax запрос для добавления товара в заказы --*/
          $.ajax({
              type: "POST",
              async:true,
              url: "/admin/addord/",
              data: {"id": prod_id, "title": prod_name, "price": prod_price, "surname": surname, "name": name, "thirdname": thirdname,"phone": phone, "email": email, "dev-no": devno, "dev-yes": devyes, "city": city, "street": street, "home": home, "aprt": aprt, "cash": cash, "card": card, "comment": comment},
              success:function(){}
          });
          /*. ajax -- */

          popupEnd.classList.add('fade');
          setTimeout(() => popupEnd.classList.remove('fade'), 1000);

          window.scroll(0, 0);

          const buttonEnd = popupEnd.querySelector('.button');

          buttonEnd.addEventListener('click', () => {


            popupEnd.classList.add('fade-reverse');

            setTimeout(() => {

              popupEnd.classList.remove('fade-reverse');

              toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

            }, 1000);

            window.location.reload();
          });

        } else {
          window.scroll(0, 0);
          evt.preventDefault();
        }
      });
    }
  });
}
 /* -- */

const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

  pageOrderList.addEventListener('click', evt => {
    
    if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
      var path = evt.path || (evt.composedPath && evt.composedPath());
      Array.from(path).forEach(element => {

        if (element.classList && element.classList.contains('page-order__item')) {

          element.classList.toggle('order-item--active');

        }

      });

      evt.target.classList.toggle('order-item__toggle--active');

    }

    if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {

      const status = evt.target.previousElementSibling;

      if (status.classList && status.classList.contains('order-item__info--no')) {
        status.textContent = 'Выполнено';
      } else {
        status.textContent = 'Не выполнено';
      }

      status.classList.toggle('order-item__info--no');
      status.classList.toggle('order-item__info--yes');

    }

  });

}

const checkList = (list, btn) => {

  if (list.children.length === 1) {

    btn.hidden = false;

  } else {
    btn.hidden = true;
  }

};


// добавление товара
const addList = document.querySelector('.add-list');
if (addList) {

  const form = document.querySelector('.custom-form');
  labelHidden(form);

  const addButton = addList.querySelector('.add-list__item--add');
  const addInput = addList.querySelector('#product-photo');

  checkList(addList, addButton);

  addInput.addEventListener('change', evt => {

    const template = document.createElement('LI');
    const img = document.createElement('IMG');

    template.className = 'add-list__item add-list__item--active';
    template.addEventListener('click', evt => {
      addList.removeChild(evt.target);
      addInput.value = '';
      checkList(addList, addButton);
    });

    const file = evt.target.files[0];
    const reader = new FileReader();

    reader.onload = (evt) => {
      img.src = evt.target.result;
      template.appendChild(img);
      addList.appendChild(template);
      checkList(addList, addButton);
    };


    reader.readAsDataURL(file);


  });

/*  apg 2020-03-28   */
  const button = document.querySelector('.button');
  const popupEnd = document.querySelector('.page-add__popup-end');

  // нажата кнопка "Добавить товар"
  button.addEventListener('click', (evt) => {

    // вставить запрос на добавление товара
    // в базу данных
    // validate type of file

/*    var myfile = $('#product-photo').get(0).files[0];
    if(['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].indexOf($('#product-photo').get(0).files[0].type) == -1) {
        alert('Error : Only JPEG, PNG & GIF allowed');
        return;
    }
console.log('before reader');

    const rdr = new FileReader();
    rdr.readAsDataURL(myfile);
    //reader.onload = function(){
        console.log('after reader before ajax');
        var data = { 'title': 'Sample Photo Title', 'file': rdr.result };
            $.ajax({
                type: 'POST',
                url: '/admin/upload/',
                data: data,
                success: function(data) {
                    alert(data);
                },
                error: function() {
                    alert('not ok');
                },
            });
    //};
*/

    var fname = $("#product-photo").get(0).files[0].name;

    if (['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].indexOf($("#product-photo").get(0).files[0].type) == -1) {
        alert('Error : Only JPEG, PNG & GIF allowed');
        return;
    }

    var data = new FormData();
    data.append('title', 'Sample Photo Title');
    data.append('file', $("#product-photo").get(0).files[0]);

    // processData & contentType should be set to false
    $.ajax({
        type: 'POST',
        url: '/admin/upload/',
        data: data,
        success: function(data) {
            //console.log(data);
        },
        error: function(data) {
            //console.log('not ok');
        },
        processData: false,
        contentType: false
    });
    // подготовка данных
    var name = form.querySelector('#product-name').value;
    var price = form.querySelector('#product-price').value;
    var category = form.querySelector('#product-category').value;
    var product_qnt = form.querySelector('#product-qnt').value;
    var newitem = form.querySelector('#new').value;
    var sale = form.querySelector('#sale').value;
$.ajax({
    type: "POST",
    async: true,
    url: "/admin/addprod/",
    data: {'product-name':name,'product-price':price,'category':category,'product-photo':fname,'product-qnt':product_qnt,'new':newitem,'sale':sale},
    success: function(data) {
        //console.log(data);
    }
});

evt.preventDefault();
    form.hidden = true;
    popupEnd.hidden = false;


  });
} // end if
// страница добавления товара


// Product Edit Page
const editList = document.querySelector('.edit-list');
if (editList) {

    const form = document.querySelector('.custom-form');
    labelHidden(form);

    const editButton = editList.querySelector('.edit-list__item--edit');
    const editInput = editList.querySelector('#product-photo');

    checkList(editList, editButton);

    editInput.addEventListener('change', evt => {

    const template = document.createElement('LI');
    const img = document.createElement('IMG');

    template.className = 'edit-list__item edit-list__item--active';
    template.addEventListener('click', evt => {
        editList.removeChild(evt.target);
    editInput.value = '';
    checkList(editList, editButton);


    });

const file = evt.target.files[0];
const reader = new FileReader();

reader.onload = (evt) => {
    img.src = evt.target.result;
    template.appendChild(img);
    editList.appendChild(template);
    checkList(editList, editButton);
};

reader.readAsDataURL(file);

});

    const activeLi = editList.querySelector('.edit-list__item--active');
    if (activeLi) {
        activeLi.addEventListener('click', evt => {
            editList.removeChild(evt.target);
        editInput.value = '';
        checkList(editList, editButton);
        });
    }


const button = document.querySelector('.button');
const popupEnd = document.querySelector('.shop-page__popup-end');  /* ('.page-edit__popup-end'); */

button.addEventListener('click', (evt) => {

     var product_photo = ' ';
     if ($("#product-photo").get(0).files[0]) {
         if (['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].indexOf($("#product-photo").get(0).files[0].type) == -1) {
             alert('Error : Only JPEG, PNG & GIF allowed');
             return;
         }

         var data = new FormData();
         data.append('title', 'Sample Photo Title');
         data.append('file', $("#product-photo").get(0).files[0]);

         // processData & contentType should be set to false
         $.ajax({
             type: 'POST',
             url: '/admin/upload/',
             data: data,
             success: function (data) {
                 //console.log(data);
             },
             error: function (data) {
                 //console.log('not ok');
             },
             processData: false,
             contentType: false
         });
         product_photo = $("#product-photo").get(0).files[0].name;
     } else {
         product_photo = $('#current-photo').attr('alt');
     }

     // подготовка данных
     var name = document.getElementById('product-name').value;
     var price = document.getElementById('product-price').value;
     var category = document.getElementById('product-category').value;
     var product_qnt = document.getElementById('product-qnt').value;
     var newitem = document.getElementById('new').checked;
     var sale = document.getElementById('sale').checked;
     var id = document.getElementById('id').value;
console.log(newitem);
console.log(sale);
     $.ajax({
         type: "POST",
         async: true,
         url: "/admin/editprod/",
         data: {'id':id,'product-name':name,'product-price':price,'category':category,'product-photo':product_photo,'product-qnt':product_qnt,'new':newitem,'sale':sale},
         success: function(data) {
            //console.log(data);
         }
     });

     evt.preventDefault();
     form.hidden = true;
     popupEnd.hidden = false;



 });

}

/* -- список товаров -- */
const productsList = document.querySelector('.page-products__list');
if (productsList) {

  productsList.addEventListener('click', evt => {

    const target = evt.target;

    if (target.classList && target.classList.contains('product-item__delete')) {
      productsList.removeChild(target.parentElement);

    }

    const block = target.parentElement;
    const spanId = block.querySelector('.prod-id');

    $.ajax({
        type: "POST",
        async:true,
        url: "/admin/delprod/",
        data: {"id": spanId.innerHTML},
        success:function(){}
    }); /* /ajax */

  });

}

// jquery range max min
if (document.querySelector('.shop-page')) {

  $('.range__line').slider({
    min: 350,
    max: 32000,
    values: [350, 32000],
    range: true,
    stop: function(event, ui) {

      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');

    },
    slide: function(event, ui) {

      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');

    }
  });

}

/**
 * копируем значения цены в поля формы
 * для передачи их GET-параметрами
 */
function setPriceValues()
{
    const spmin = document.getElementById('min_price');
    const spmax = document.getElementById('max_price');
    var strmin = spmin.innerHTML;
    var strmax = spmax.innerHTML;
    const newitem = document.getElementById('new');
    const saleitem = document.getElementById('sale');

    strmin = strmin.substr(strmin, strmin.length -4).trim();
    strmax = strmax.substr(strmax, strmax.length -4).trim();

    // проверяем есть ли уже параметры
    var currentPath = String(window.location);
    var lineStart = '?';

    if (newitem.checked) {
        var strnew = 'new=on&';
    } else {
        var strnew = '';
    }
    if (saleitem.checked) {
        if (strnew.length < 1) {
            var strsale = 'sale=on&';
        } else {
            var strsale = 'sale=on&';
        }
    } else {
        var strsale = '';
    }

    var baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    var newUrl = baseUrl + lineStart + strnew + strsale + 'min=' + strmin.replace(' ', '') + '&max=' + strmax.replace(' ', '');
    //history.pushState(null, null, newUrl);
    window.location = newUrl;
}

/**
 * сортировка по цена-имя
 *
*/
function sortBy()
{
    const sOrder = document.getElementById('sortBy');
    const sDir = document.getElementById('sortOrder');
    const resList = document.getElementById('slist');

    var ord = '';
    var dir = '';

    // проверяем есть ли уже параметры
    var currentPath = String(window.location);
    console.log(currentPath);
    console.log(currentPath.indexOf(lineStart));

    var lineStart = '?';
    if (currentPath.indexOf(lineStart)> -1) {
        lineStart = '&';
        console.log('amp');
    } else {
        lineStart = '?';
        console.log('question');
    }

    // проверяем есть ли уже сортировка
    if (currentPath.indexOf('ord') > -1) {
        currentPath = currentPath.substr(0, currentPath.indexOf('ord') - 1);
    }
console.log(currentPath);
    if (sOrder.value == 'Сортировка') {
        ord = '';
    } else {
        ord = sOrder.value;
    }

    if (sDir.value == 'Порядок') {
        dir = '';
    } else {
        dir = sDir.value;
    }
    var newPath = currentPath + lineStart + 'ord=' + ord + '&dir=' + dir;
    console.log(newPath);

    if (ord != '' && dir != '') {
        window.location = newPath;
        //window.location.reload();
    }
/*
    $.ajax({
        type: "POST",
        async:true,
        url: "/index/orderSort",
        data: {"order": ord, "dir": dir},
        success: function(data) {
            $('#nlist').hide();
            resList.innerHTML = data; // $('#slist').html(data);
            resList.removeAttribute('hidden'); // $('#slist').show();
        }
    });
    */

}


/**
* ajax from /order/index/
*
* изменяем статус заказа Выполнен/Не выполнен
* на странице отображения заказов
*/
function chOrderStatus(id, status)
{
  if (status == 0) {
    status = 1;
  } else {
    status = 0;
  }
  //console.log(status);
  //console.log(id);

    $.ajax({
        type: "POST",
        async: true,
        url: "/admin/updstatus/",
        data: {"id": id, "status": status},
        success: function(data) {
          console.log(data);
        }
    })
}