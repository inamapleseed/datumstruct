<?php
//  Related Options / Связанные опции
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru

// Heading
$_['module_name']           = 'Связанные опции PRO';
$_['heading_title']         = 'LIVEOPENCART: '.$_['module_name'];
$_['text_edit']             = 'Настройки модуля: '.$_['module_name'].'';
$_['related_options_title'] = $_['module_name'];

// Text
$_['text_module']           = 'Модули';
$_['text_success']          = 'Настройки обновлены!';
$_['text_content_top']      = 'Верх страницы';
$_['text_content_bottom']   = 'Низ страницы';
$_['text_column_left']      = 'Левая колонка';
$_['text_column_right']     = 'Правая колонка';
$_['text_ro_updated_to']    = 'Модуль обновлен до версии ';
$_['text_ro_all_options']   = 'Все доступные опции';
$_['text_ro_support']       = "Разработка: <a href='http://19th19th.ru' target='_blank'>19th19th.ru</a> | Поддержка, вопросы и предложения: <a href=\"mailto:help@liveopencart.ru\">help@liveopencart.ru</a>";
$_['text_ro_clear_options'] = 'Очистить параметры';



// Entry
$_['entry_settings']                  = 'Настройки модуля';
$_['entry_additional']                = 'Дополнительные поля';

$_['entry_PHPExcel_not_found']        = 'Не установлена библиотека <a href="http://liveopencart.ru/news_site/phpexcel/" target="_blank" title="Что такое PHPExcel? Как установить PHPExcel?">PHPExcel</a>. Не найден файл: ';
$_['entry_export']                    = 'Экспорт';
$_['entry_export_description']        = 'Данные выгружаются в формате XLS.<br>В первой строке таблицы содержатся заголовки, в последующих строках данные';
$_['entry_export_get_file']           = 'Получить файл';
$_['entry_export_check_all']          = 'Отметить все';
$_['entry_export_fields']             = 'Выгружаемые данные:';
$_['entry_import']                    = 'Импорт (старые версии)';
$_['entry_import_ok']                 = 'Импорт завершен';
$_['entry_import_description']        = '
<b>Испорт данных их старых версию модуля Связанные опции PRO (до версии 1.1.0) и модуля Связанные опции 2 (до версии 2.2.0)</b><br><br>

Формат файла: XLS. Данные берутся с первого листа.
<br>В первой строке таблицы должны быть заголовки вида: product_id, relatedoptions_model, quantity, price, option_id1, option_value_id1, option_id2, option_value_id2, ... (не путать с product_option_id и product_option_value_id)
<br>Начиная со второй строки таблицы должны быть данные соответствующие заголовкам.<br><br>
При совпадении импортируемых сочетаний связанных опций товаров с существующими, существующие сочетания будут заменены.';

$_['entry_import_nothing_before']     = 'Не удалять связанные опции перед загрузкой данных';
$_['entry_import_delete_before']      = 'Удалить все связанные опции перед загрузкой данные';
$_['entry_import_product_before']     = 'Удалить связанные опции только для товаров присутствующих в загружаемом файле';
$_['button_upload']		                = 'Загрузить файл';
$_['button_upload_help']              = 'загрузка начнется сразу после выбора файла';
$_['entry_server_response']           = 'Ответ сервера';
$_['entry_import_result']             = 'Обработано товаров/связанных опций';

$_['entry_export_new']                = 'Экспорт';
$_['entry_export_new_fields']         = 'Выгружаемые данные:';
$_['entry_export_new_get_file']       = 'Получить файл';
$_['entry_export_new_check_all']      = 'Выделить все';
$_['entry_export_new_description']    = 'Формат выгружаемого файла: XLS.<br>
Каждый вариант комбинации связанных опций выгружается на отдельный лист.<br>
Также, на отдельные листы выгружаются скидки и акции связанных опций.<br>
Первая строка каждого листа содержит заголовок (названия колонок), все последующие строки содержат соответствующие данные.<br>
';
$_['ro_entry_export_method']                = 'Способ выгрузки данных';
$_['ro_entry_export_method_all']            = 'Выгружать все комбинации связанных опций';
$_['ro_entry_export_method_by_product_ids']	= 'Выгружать комбинации связанных опций по интервалу идентификаторов товаров (product_id)';
$_['ro_entry_export_method_by_ro_variant']	= 'Выгружать только выбранный вариант связанных опций';
$_['ro_entry_start_product_id']             = 'С идентификатора';
$_['ro_entry_end_product_id']               = 'По идентификатор';
$_['ro_entry_export_by_variant']            = 'Export variant';
$_['ro_entry_min_product_id']            		= 'мин.ид.:';
$_['ro_entry_max_product_id']            		= 'макс.ид.:';

$_['entry_import_new']                    = 'Импорт';
$_['entry_import_new_nothing_before']     = 'Не удалять связанные опции перед загрузкой данных';
$_['entry_import_new_delete_before']      = 'Удалить все связанные опции перед загрузкой данных';
$_['entry_import_new_product_before']     = 'Удалить связанные опции только для товаров присутствующих в загружаемом файле';
$_['entry_import_new_button_upload']	    = 'Загрузить файл';
$_['entry_import_new_button_upload_help'] = 'загрузка начнется сразу же после выбора файла';
$_['entry_import_new_ok']                 = 'Импорт завершен';
$_['entry_import_new_server_response']    = 'Ответ сервера:';
$_['entry_import_new_result']             = 'Обработано товаров/связанных опций';
$_['entry_import_new_error_not_uploaded']	= 'файл не загружен';
$_['entry_import_new_error_not_found']	  = 'колонка не найдена на листе';
$_['entry_import_new_error_no_ro']	      = 'соответствующая комбинация связанных опций не найдена для ';
$_['entry_import_new_error_skipped']	    = 'лист пропущен';
$_['entry_import_new_error_no_data']	    = 'нет данных на листе';
$_['entry_import_new_error_no_sheets']    = 'листы с данными не найдены';
$_['entry_import_new_error']              = 'Ошибка:';
$_['entry_import_new_description']        = '
Формат загружаемого файла: XLS<br>
Каждый вариант комбинации связанных опций должен быть расположен на отдельном листе (имя листа должно начинаться с букв "RO").<br>
Если необходимо импортировать скидки и акции для связанных опций, они также должны располагаться на отдельных листах ("discounts" и "specials" соответственно).<br>
Первая строка каждого листа должна содержать заголовок (обозначения колонок) product_id, options_values_ids (options ids and options values ids with comma delimiter - option_id:option_value_id, ...), quantity,	model, sku, ean, upc, location, price_prefix, price, defaultselect, defaultselectpriority, weight_prefix, weight, stock_status_id.<br>
Все последующие строки должны содержать данные в соответствии с указанными в заголовке колонками.<br>
';

$_['entry_update_quantity']           = 'Пересчитывать количество';
$_['entry_update_quantity_help']      = 'автоматически пересчитывать количество товара на основании данных по связанным опциям';
$_['entry_stock_control']             = 'Контролировать остаток';
$_['entry_stock_control_help']        = 'запретить добавлять в корзину товар в количестве превышающем остаток по связанным опциям';
$_['entry_update_options']            = 'Обновлять опции';
$_['entry_update_options_help']    	  = 'автоматически обновлять опции товара на основании данных по связанным опциям';
$_['entry_update_options_remove']     = 'Удалять опции при обновлении';
$_['entry_update_options_remove_help']= 'При удалении опции из комбинаций связанных опций (на закладке \''.$_['module_name'].'\'), она будет автоматически удалена из опций товара (на закладке \'Опции\') ';
$_['entry_subtract_stock']            = 'Вычитать остаток опций';
$_['entry_subtract_stock_help']    	  = 'включить для обновляемых опций настройку вычитания остатка';
$_['text_subtract_stock_from_product']            = 'Взять из параметров товара';
$_['text_subtract_stock_from_product_first_time'] = 'Взять из параметров товара (только при добавлении опции)';
$_['entry_required']                  = 'Связанные опции - обязательные';
$_['entry_required_help']    	        = 'сделать обновляемые опции обязательными для заполнения';
$_['text_required_first_time']        = 'Да (только при добавлении опции)';
$_['entry_options_values']            = 'Значения опций';
$_['entry_add_related_options']       = 'Добавить связанные опции';
$_['entry_related_options_quantity']  = 'Количество';
$_['entry_ro_version']                = $_['module_name'].', версия';

$_['entry_additional_fields']         = 'Дополнительные данные для связанных опций';
$_['text_ro_set_options_variants']    = 'Необходимо задать варианты связанных опций на странице настроек модуля';
$_['entry_ro_disable_all_options_variant']= 'Не использовать вариант "'.$_['text_ro_all_options'].'"';
$_['entry_ro_disable_all_options_variant_help']= 'отключает специальный вариант связанных опций включающий в себя все опции совместимые с модулем (в большинстве случаев это вариант не нужен)';
$_['entry_ro_use_variants']           = 'Использовать различные варианты связанных опций';
$_['entry_ro_use_variants_help']      = 'позволяет использовать различные по составу опций комбинации в разных товарах';
$_['entry_ro_variant']                = 'Вариант связанных опций';
$_['entry_ro_variant_name']           = 'Название варианта';
$_['entry_ro_options']                = 'Опции варианта';
$_['entry_ro_sort_order']             = 'Сортировка';
$_['entry_ro_add_variant']            = 'Добавить вариант';
$_['entry_ro_delete_variant']         = 'Удалить вариант';
$_['entry_ro_add_option']             = 'Добавить опцию';
$_['entry_ro_delete_option']          = 'Удалить опцию';
$_['entry_ro_use']                    = 'Использовать связанные опции';
$_['entry_show_clear_options']        = 'Показать "Очистить параметры"';
$_['entry_show_clear_options_help']   = 'отображать у покупателя кнопку для сброса выбранных значений опций товара';
$_['option_show_clear_options_not']   = 'не использовать';
$_['option_show_clear_options_top']   = 'выше опций';
$_['option_show_clear_options_bot']   = 'ниже опций';
$_['entry_hide_inaccessible']         = 'Скрывать недоступные значения';
$_['entry_hide_inaccessible_help']    = 'скрывать от покупателя недоступные для выбора значения опций';
$_['entry_hide_option']               = 'Скрывать недоступные опции';
$_['entry_hide_option_help']          = 'скрывать от покупателя опции все значения которых недостуны для выбора';
$_['entry_unavailable_not_required']  = 'Считать недоступные опции необязательные';
$_['entry_unavailable_not_required_help'] = 'если опция недоступна для выбора, позволять добавлять товар в корзину без этой опции';
$_['entry_spec_model']                = 'Модель';
$_['entry_spec_model_help']           = 'указывать разные модели для сочетаний связанных опций (указанные модели будут отображаться в корзине и сохраняться в заказе)';
$_['entry_spec_model_0']              = 'Выключено';
$_['entry_spec_model_1']              = 'Включено';
$_['entry_spec_model_2']              = 'Включено: сумма моделей выбранных связаных опций ( model 1 + model 2 + ...)';
$_['entry_spec_model_3']              = 'Включено: модель товара плюс сумма моделей выбранных связаных опций';

$_['entry_spec_model_delimiter_product'] 	= 'Разделитель товар-опции';
$_['entry_spec_model_delimiter_ro']				= 'Разделитель опции-опции';

$_['entry_spec_sku']                  = 'Артикул (SKU)';
$_['entry_spec_sku_help']             = 'указывать разные артикулы (SKU) для сочетаний связанных опций (указанные SKU будут сохраняться в заказах)';
$_['entry_spec_upc']                  = 'UPC';
$_['entry_spec_upc_help']             = 'указывать разные значения UPC для сочетаний связанных опций (указанные значения будут сохраняться в заказах)';
$_['entry_spec_ean']                  = 'EAN';
$_['entry_spec_ean_help']             = 'указывать разные значения EAN для сочетаний связанных опций (указанные значения будут сохраняться в заказах)';
$_['entry_spec_location']             = 'Расположение';
$_['entry_spec_location_help']        = 'указывать разные расположения для сочетаний связанных опций (указанные расположения будут сохраняться в заказах)';
$_['entry_spec_price']                = 'Цена';
$_['entry_spec_price_help']           = 'указывать разные цены для сочетаний связанных опций, если цена для набора связанных опций не заполнена - используется обычная цена товара';
$_['entry_spec_ofs']                  = 'Отсутствие на складе';
$_['entry_spec_ofs_help']             = 'указывать различные статуры \'Отсутствие на складе\' для комбинаций связанных опций (указанный для комбинации статус будет отображатьсян а странице товара, если покупатель выберет комбинацию связанных опций с нулевым остатком)';
$_['entry_spec_weight']               = 'Вес';
$_['entry_spec_weight_help']          = 'указывать разные веса для сочетаний связанных опций (будет использоваться при расчете весов товаров в заказах)';
$_['entry_spec_price_discount']       = 'Скидки';
$_['entry_spec_price_discount_help']  = 'указывать разные скидки для сочетаний связанных опций (работает только вместе c включенной функцией \''.$_['entry_spec_price'].'\'), если скидки для набора связанных опций не заполнены - используются обычные скидки товара';
$_['entry_add_discount']              = 'Добавить скидку';
$_['entry_del_discount_title']        = 'Удалить скидку';
$_['entry_spec_price_special']        = 'Акции';
$_['entry_spec_price_special_help']   = 'указывать разные акции для сочетаний связанных опций (работает только вместе c включенной функцией \''.$_['entry_spec_price'].'\'), если акции для набора связанных опций не заполнены - используются обычные акции товара';
$_['entry_add_special']               = 'Добавить акцию';
$_['entry_del_special_title']         = 'Удалить акцию';
$_['entry_prices']                    = 'Цены';
$_['entry_select_first_short']        = 'Автовыбор';
$_['entry_select_first_priority']     = 'Приоритет';
$_['entry_select_first']              = 'Автовыбор сочетания';
$_['entry_select_first_help']         = 'автоматический выбор значений опций на странице товара';
$_['option_select_first_not']         = 'не использовать';
$_['option_select_first']             = 'первое доступное';
$_['option_select_first_last']        = 'последнее оставшееся';
$_['option_select_first_always']      = 'первое доступноe значение опции (всегда)';
$_['entry_step_by_step']              = 'Пошаговый выбор опций';
$_['entry_step_by_step_help']         = 'покупатель сначала выбирает значение первой опции, потом второй, затем третьей и т.д. (в любой момент покупатель может начать выбор сначала, или перевыбрать значение одной из выбранных опций - неподходящие значения последующих опций будут сброшены)';
$_['entry_allow_zero_select']         = 'Выбор сочетаний без остатка';
$_['entry_allow_zero_select_help']    = 'позволить покупателю выбирать сочетания опций с нулевым остатком';
$_['entry_edit_columns']              = 'Редактирование опций';
$_['entry_edit_columns_0']            = '1 колонка';
$_['entry_edit_columns_2']            = '2 колонки';
$_['entry_edit_columns_3']            = '3 колонки';
$_['entry_edit_columns_4']            = '4 колонки';
$_['entry_edit_columns_5']            = '5 колонок';
$_['entry_edit_columns_100']          = 'по ширине';
$_['entry_edit_columns_help']         = 'расположение полей выбора опций при редактировании связанных опций';
$_['entry_add_all_variants']          = 'Добавить все возможные комбинации опций';
$_['entry_add_product_variants']      = 'Добавить все комбинации опций товара';
$_['text_extension_page']             = "";
$_['entry_spec_price_prefix']         = "Префикс цены";
$_['entry_spec_price_prefix_help']    = "Добавляет возможность указать префикс '+' или '-' для цен связанных опций";
$_['text_update_alert']               = '(доступна новая версия)';
$_['text_combs_number']               = 'Количество комбинаций опций ';
$_['text_combs_number_out_of_limit']  = ' слишком велико. ';
$_['text_combs_number_is_big']        = '. Добавление такого количества комбинаций опции может занять продолжительное время. Продолжить?';
$_['text_combs_will_be_added']        = ' комбинаций опций будет добавлено. Продолжить?';
$_['entry_delete_all_combs']	        = 'Удалить все комбинации опций.';
$_['text_delete_all_combs']	        	= 'Все комбинации опций будут удалены. Продолжить?';

$_['entry_about'] = 'О модуле';
$_['text_vqmod_href']									= 'http://github.com/vqmod/vqmod';
$_['text_vqmod_required']							= 'Для работы модуля требуется <a href="'.$_['text_vqmod_href'].'" target="_blank">vQmod</a> версии 2.6.1 или выше (<a href="http://liveopencart.ru/chto-takoe-vqmod/" target="_blank">что такое vQmod?</a>)';
$_['module_description']    = '
Модуль позволяет создавать для товаров комбинации связанных опций и указывать для каждой комбинации отдельный остаток, цену, модель и другие данные.
Позволяет ограничить выбор опций покупателем только доступными комбинациями связанных опций.
Может быть полезен для товаров, имеющих взаимозависимые опции, например, цвет и размер у одежды.
<br><br>
<span class="help">'.$_['text_vqmod_required'].'.</span>';

$_['text_conversation'] = 'Есть вопросы по работе модуля? Требуется интеграция с шаблоном или доработка? Пишите: <b>help@liveopencart.ru</b>.';

$_['entry_we_recommend'] = 'Также рекомендуем:';
$_['text_we_recommend'] = '
<ul>
<li>
<a href="http://liveopencart.ru/opencart-moduli-shablony/moduli/tsenyi/jivaya-tsena-dinamicheskoe-obnovlenie-tsenyi-2" title="Динамическое обновление цены - Живая цена для OpenCart/ocStore" target="_blank">
Динамическое обновление цены - Живая цена</a> - 
Модуль предназначен для динамического обновления цены на странице товара в зависимости от выбранных покупателем опций и количества.
Для определения доступной скидки учитывается количество указанное на странице товара в сумме с количеством товара уже добавленным в корзину.
</li>
<li>
<a href="http://liveopencart.ru/opencart-moduli-shablony/moduli/vneshniy-vid/izobrajeniya-optsiy-pro-2" title="Изображения опций PRO для OpenCart/ocStore" target="_blank">
Изображения опций PRO</a> - модуль позволяющий привязывать изображения к опциям товара (одно или несколько изображений для каждой опции)
и динамически менять видимые изображения на странице товара в зависимости от выбранной покупателем опции.
</li>
</ul>
';

$_['module_copyright'] = 'Модуль "'.$_['module_name'].'" это коммерческое дополнение. Не выкладывайте его на сайтах для скачивания и не передавайте его копии другим лицам.<br>
Приобретая модуль, Вы приобретаете право его использования на одном сайте. <br>Если Вы хотите использовать модуль на нескольких сайтах, следует приобрести отдельную копию модуля для каждого сайта.<br>';

//warning
$_['warning_equal_options']    		= 'совпадающий набор опций';
$_['warning_max_input_vars']      = 'Внимание: для корректного сохранения всех данных товара (включая опции), параметр конфигурации PHP <b>max_input_vars</b> должен быть увеличен (текущее значение %s).';

// Error
$_['error_equal_options']         = 'не должно быть совпадающих наборов связанных опций';
$_['error_not_enough_options']    = 'В наборе опций заданы не все опции';
$_['error_permission']            = 'У Вас нет прав для доступа к модулю!';
$_['error_vqmod_not_found']       = 'Не обнаружены изменения вносимые модулем! Пожалуйста, проверьте установлен ли <a href="'.$_['text_vqmod_href'].'" target="_blank">vQmod</a>. '.$_['text_vqmod_required'].'.';
