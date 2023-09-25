function increment(quantity){
  UpdateQuantity(quantity.find('.input-number'),!0);
}
function descrement(quantity){
	UpdateQuantity(quantity.find('.input-number'),!1);
}
function UpdateQuantity(t,n){var i=getQuantity(t);if (i = "NaN") {i = 0;}i+=1*(n?1:-1),1>i&&(i=1),t.attr("value",i.toString()).val(i.toString())}
function getQuantity(t){var n=parseInt(t.val());return("NaN"==typeof n||1>n)&&(n=1),n}
function quantity_increment(t){UpdateQuantity(t.find(".product-quantity"),!0)}
function quantity_decrement(t){UpdateQuantity(t.find(".product-quantity"),!1)}
