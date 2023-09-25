<?php
// Heading
$_['heading_title'] = '<img src="view/universal_import/img/icon.png" alt="" style="vertical-align:bottom;padding-right:4px"/><b style="color:#156584;">Universal Import/Export Pro</b>';

// Text 
$_['text_module'] = 'Модули';
$_['text_browse'] = 'Открыть';
$_['text_continue'] = 'Следующий шаг';
$_['text_add_column'] = 'Добавить колонку';
$_['text_remove_column'] = 'Удалить колонку';

$_['text_success'] = 'Выполнено: Изменения настроек сохранены!';
$_['text_store_select'] = 'Магазин:';
$_['text_import'] = 'Импорт';
$_['text_type_product'] = 'Товар';
$_['text_type_product_update'] = 'Быстроое обновление товаров';
$_['text_type_category'] = 'Категория';
$_['text_type_information'] = 'Информация';
$_['text_type_manufacturer'] = 'Производитель';
$_['text_type_customer'] = 'Клент';
$_['text_ignore'] = '';
$_['text_column'] = 'колонка';
$_['text_next_step'] = 'Проверить и продолжить';
$_['text_start_process'] = 'Начать процесс импорта';
$_['text_pausing_process'] = 'Остановка, пожалуйста дождитесь завершения изменений...';
$_['text_resume_process'] = 'Продолжить процесс';
$_['text_start_simu_process'] = 'Начать полную симуляцию';
$_['text_pause_process'] = 'Производится импортирование - Нажмите для паузы';
$_['text_pause_simu_process'] = 'Симуляция в процессе - Нажмите для паузы';
$_['text_previous_step'] = 'Предыдущий шаг';
$_['text_data_preview'] = 'Превью данных';
$_['text_row'] = 'Строка';

$_['text_action_update'] = 'Обновить - перезаписать';
$_['text_action_soft_update'] = 'Обновить - сохранить';
$_['text_action_insert'] = 'Вставить';
$_['text_action_skip'] = 'Пропустить';
$_['text_action_delete'] = 'Удалить';
$_['text_action_overwrite'] = 'Перезаписать';
$_['text_action_rename'] = 'Переназвать';
$_['text_img_action_keep'] = 'Оставить нынешнее изображение';
$_['text_img_action_rename'] = 'Переименовать новое изображение';
$_['text_img_action_overwrite'] = 'Перезаписать новым изображением';

// Tabs
$_['text_tab_0'] = 'Импорт';

$_['text_import_step1'] = '<b>Шаг 1</b> - Выбор файла';
$_['text_import_step2'] = '<b>Шаг 2</b> - Настройки импорта';
$_['text_import_step3'] = '<b>Шаг 3</b> - Соответствие столбцов';
$_['text_import_step4'] = '<b>Шаг 4</b> - Проверка данных';
$_['text_import_step5'] = '<b>Шаг 5</b> - Начать процесс';

// Profile manager
$_['text_profile_dir_not_writable'] = 'Папка с профилем настроек не имеет доступа к записи, убедитесь что у вас есть разрешения на запись в папки (и подпапки):';
$_['text_profile_name'] = 'Имя профиля';
$_['text_new_profile'] = '- Сохранить как новый профиль-';
$_['text_save_profile'] = 'Сохранить профиль';
$_['text_delete_profile'] = 'Удалить профиль';
$_['text_save_profile_i'] = 'Сохраните ваш текущий набор настроек на случай использование в дальнейших импортированиях.<br/>Вы можете выбрать сущестующий профиль настроек чтобы изменить его или создать новый.';
$_['text_profile_saved'] = 'Ваш профиль успешно сохранен';

// Step 1
$_['entry_name'] = 'Имя';
$_['entry_demo_file'] = 'Загрузить демо файл';
$_['entry_demo_file_i'] = 'Используйте предзагруженные файлы для проверки возможностей модуля. Если вы хотите загрузить свой собственный - оставьте данное поле пустым и загрузите файл в предыдущем поле.';
$_['entry_type'] = 'Тип импорта';
$_['entry_type_i'] = 'Какой тип данных вы собираетесь импортировать?';
$_['entry_profile'] = 'Профиль настроек';
$_['text_select_profile'] = 'Выберите настройки профиля';
$_['entry_profile_i'] = 'Профиль содержит все настройки, которые вы можете указать в шагах 2 и 3. Вы можете сохранить новый профиль или изменения на шаге 4.';
$_['entry_file'] = 'Выбрать файл';
$_['entry_file_i'] = 'Вставьте ваш файл сюда.<br/>Поддерживается неограниченный размер файла.<br/>Поддерживаемые типы файлов : CSV, XML, XLS, XLSX, ODS';
$_['text_dropzone'] = 'Перетяните файл сюда или нажмите для загрузки через проводник';
$_['text_file_loaded'] = 'Файл загружен:';
$_['text_file_error'] = 'Ошибка:';

// Step 2
$_['text_common_settings'] = 'Общие настройки';
$_['entry_xml_node'] = 'XML item node';
$_['entry_xml_node_i'] = 'Set the xml node for each item in your file for example <product> or <item> (set value without brackets). The system will try to auto-detect it, just change it if incorrect value';
$_['entry_csv_separator'] = 'разделитель полей CSV';
$_['entry_multiple_separator'] = 'Разделитель нескольких значений';
$_['entry_multiple_separator_i'] = 'Если поле содержит несколько значений, укажите символ разделяющий их.';
$_['entry_csv_header'] = 'Первая строка это шапка';
$_['entry_item_identifier'] = 'Уникальный идентификатор товара';
$_['entry_item_identifier_i'] = 'Уникальное значение по которому будут обновляться уже добавленные товары в базу.';
$_['entry_item_exists'] = 'Действия с существующими товарами';
$_['entry_item_exists_i'] = 'Перезаписать: все данные будут заново перезаписаны, информация в пустых полях на шаге 3 будет удалена (это стандартный метод opencart, включающий все вносимые модификации) - Обновить: будут обновлены только те поля, которые вы укажите в шаге 3, информация не указана в полях шага 3 будет сохранена.';
$_['entry_item_not_exists'] = 'Новые позиции прайса';
$_['text_image_settings'] = 'Настройки изображения';
$_['entry_image_download'] = 'Скачать изображения';
$_['entry_image_download_i'] = 'Если изображение нужно скачать с внешнего сайта - укжаите полный url, если оно храниться в каталоге вашего сайта - используйте формат /image/catalog/your-image-name.jpg';
$_['entry_image_exists'] = 'Существующее изображение';
$_['entry_image_exists_i'] = 'Что делать если изображение с таким именем уже существует?';
$_['entry_image_location'] = 'Расположение изображения';
$_['entry_image_location_i'] = 'В случае скачивания изображений с других сайтов, укажите путь к ним в каталоге вашего сайта (напр: products/). Этот параметр не используется при назначении файлов вашего каталога.';
$_['entry_image_keep_path'] = 'Копировать путь url';
$_['entry_image_keep_path_i'] = 'Соблюдать ту же структуру папок что и на сайте доноре. Например, anyshop.com/dir/subdir/image.jpg изображение будет сохранено в dir/subdir/image.jpg вашего сайта.';
$_['text_category_settings'] = 'Настройки категории';
$_['entry_category_create'] = 'Создать если не существует';
$_['text_manufacturer_settings'] = 'Настройки производителя';
$_['entry_manufacturer_create'] = 'Создать если не существует';

// Step 3
$_['tab_extra'] = 'Собственные поля';
$_['entry_extra'] = 'Собственное поле';
$_['entry_extra_ml'] = 'Описание собственного поля';
$_['placeholder_extra_col'] = 'Название поля';
$_['info_extra_field_title'] = 'Собственные поля';
$_['info_extra_field'] = 'Здесь вы можете создавать собственные поля, это полезно если вы используете какие-либо модули, добавляющие данные в ваши таблицы.<br/>Если ваше собственное поле мультиязычное, тогда оно находится в описательной части, в таком случае вам стоит использовать Описание собственного поля. Это может быть полезным для seo модуля, который добавляет к примеру SEO H1 и другие данные, узнайте имя которое использует данный модуль и вставьте "meta_h1" или "seo_h1" в поле Имя, а в правом выпадающем меню выберите колонку в импортируемом файле с которо будут использованы данные.<br/><br/>Если в процессе импорта вы получите неопределенные ошибки индексирования, это следствие наличия большого количества собственных полей, созданных сторонними модулями. Поэтому вы должны использовать данную секцию чтобы добавить недостающие поля в процесс импорта, ошибки будут подсказывать конкретные имена недостающих в файле полей которые обязательны.';
$_['text_remove_extra_col'] = 'Удалить собственное поле';
$_['text_add_extra_field'] = 'Добавить новое собственное поле';
$_['text_add_extra_field_ml'] = 'Добавить новое описание для собственного поля';
$_['tab_functions'] = 'Дополнительные функции';
$_['text_add_function'] = 'Добавить функцию';
$_['text_function_type'] = 'Тип';
$_['text_function_action'] = 'Действие';
$_['text_remove_function'] = 'Удалить функцию';
$_['tab_quick_update'] = 'Быстрое обновление';
$_['entry_product_id'] = 'ID продукта';
$_['entry_seo_h1'] = 'SEO H1';
$_['entry_seo_h2'] = 'SEO H2';
$_['entry_seo_h3'] = 'SEO H3';
$_['entry_img_title'] = 'title изображения';
$_['entry_img_alt'] = 'alt изображения';
$_['entry_separator'] = 'Разделитель';
$_['entry_subcat_separator'] = 'Разделитель подкатегории';
$_['entry_subcat_separator_i'] = 'Например кат1 > подкат1 ; кат2 > подкат2';
$_['entry_dimension_l'] = 'Длина';
$_['entry_dimension_w'] = 'Ширина';
$_['entry_dimension_h'] = 'Высота';
$_['entry_category_id'] = 'ID Категории';
$_['entry_information_id'] = 'ID Информации';
$_['entry_manufacturer_id'] = 'ID Производителя';
$_['entry_custsomer_id'] = 'ID Клиента';
$_['entry_email'] = 'Email';
$_['import_default_value'] = 'По умолчанию';
$_['entry_default_i'] = 'Вставьте сюда дефолтное занчение для данного поля, если поле будет пустым или не связано с любыми показателями в файле будет использовано значение по умолчанию';
$_['help_field_category'] = 'Если несколько категорий перечислены в данном поле, ипользуйте разделитель, например: Категория1 > Подкатегория1 ; Категория2 > Подкатегория2';
$_['help_field_related_id'] = 'Используйте разделитель чтобы вставить несколько значений, значением может быть ID товара или идентификатор установленный в шаге 2';
$_['help_field_image'] = 'В случае использования разделителя между адресами изображений, первое будет назначено основным';
$_['help_field_product_image'] = 'Чтобы загрузить несколько изображений вы можете использовать разделитель или нажать Добавить строку чтобы выбрать столбцы для дополнительных изображений в вашем файле';
$_['help_field_product_option_'] = '';
$_['help_field_product_attribute'] = 'Может иметь три разных формата - 1: <название_группы_атрибутов>:<имя атрибута>:<значение> -2: <имя_атрибута>:<значение> - 3: <значение> (заголовок столбца будет использоваться как название атрибута) - Группа и атрибут создаются автоматически, если еще не созданы. В случае 2 и 3, если атрибут не создан он назначается в группу `Default`';
$_['help_field_product_special'] = 'Должно быть в формате типа: <номер_группы_клиента>:<приоритет>:<цена>:<дата_начала>:<дата_окончания> - или <цена> (в этом случае никакой конечной даты не будет установлено)';
$_['help_field_product_discount'] = 'Должно быть в формате: <номер_группы_клиента>:<количество>:<приоритет>:<цена>:<дата_начала>:<дата_окончания>';
$_['help_field_product_id'] = 'Выберите это если хотите использовать порядковый номер товара (product ID)';
$_['help_field_manufacturer_id'] = 'Используйте порядковый номер производителя или его название';
$_['help_field_product_category'] = 'Используйте порядковый номер категории или её имя';
$_['help_field_parent_id'] = 'Если более дух уровней, данное поле должно иметь следующий формат разделителя подкатегории: Категория1 > Подкатегория1 > Подкатегория2';
$_['entry_salt'] = 'salt';
$_['help_field_password'] = '';
$_['help_field_salt'] = 'Salt is additional protection for the password, this field must be set if the passwords you import are already encrypted, if not set your users won\'t be able to login';
$_['entry_pwd_hash'] = 'Шифрование';
$_['entry_pwd_hash_i'] = 'If your password is encrypted check the corresponding value here and fill the salt field which is necessary for the customer to login';
$_['text_pwd_clear'] = 'Очистить пароль ';
$_['text_pwd_hash'] = 'Зашифрованный пароль';

$_['xfn_delete_item'] = 'Удалить пункт';
$_['text_delete_if'] = 'Удалить если';
$_['text_value_is'] = 'Значение равно';

// Step 4
$_['text_simu_summary'] = 'Суммарно симулировать (10 строк):';
$_['text_full_simu_summary'] = 'Отчет полной симуляции:';
$_['entry_row_status'] = 'Действие со строкой';
$_['text_simu_inserted'] = 'Вставить';
$_['text_simu_updated'] = 'Обновить';
$_['text_simu_deleted'] = 'Удалить';
$_['text_simu_skipped'] = 'Пропустить';
$_['text_simu_error'] = 'Ошибка';

// Step 5
$_['text_process_summary'] = 'Суммарный отчет';
$_['text_rows_csv'] = 'Всего строк в csv';
$_['text_rows_process'] = 'Всего строк к выполнению';
$_['text_rows_insert'] = 'Всего строк к вставке';
$_['text_rows_update'] = 'Всего строк к обновлению';
$_['text_process_done'] = 'Статус процесса';
$_['text_rows_processed'] = 'Выполнено';
$_['text_rows_inserted'] = 'Вставлено';
$_['text_rows_updated'] = 'Обновлено';
$_['text_rows_deleted'] = 'Удалено';
$_['text_rows_skipped'] = 'Пропущено';
$_['text_rows_error'] = 'Ошибка';
$_['text_empty_line_skip'] = 'Пустая строка';


$_['entry_color_scheme'] = 'Color scheme:<span class="help">Быстрый доступ к некоторым цветовом темам, менайте их на вкладке дазайн </span>';
$_['entry_logo'] = 'Логотип:';
$_['entry_feed_title'] = 'Имя:';
$_['entry_cache_delay'] = 'Задержка кеширования:<span class="help">Сколько времени отображать сгенерированный файл до повторной генерации ?</span>';
$_['entry_language'] = 'Язык:<span class="Помощь"></span>';
$_['entry_feed_url'] = 'Путь к данным Url:<span class="help">Предоставьте этот url сервису по назначению</span>';

// File format
$_['text_format_csv'] = 'CSV';
$_['text_format_xml'] = 'XML';
$_['text_format_xls'] = 'XLS';
$_['text_format_xlsx'] = 'XLSX';
$_['text_format_ods'] = 'ODS';
$_['text_format_pdf'] = 'PDF';
$_['text_format_html'] = 'HTML';

// Export
$_['text_tab_1'] = 'Экспорт';
$_['entry_export_type'] = 'Тип экспорта';
$_['entry_export_type_i'] = 'Какой тип информации вы хотите экспортировать?';
$_['entry_export_format'] = 'Формат экспорта';
$_['entry_export_format_i'] = 'Выберите формат экспортируемого файла на выходе';
$_['text_start_export'] = 'Сгенерировать файл экспорта';
$_['export_all'] = '- Все -';

// Export filters
$_['export_filters'] = 'Филтра';
$_['filter_language'] = 'Язык';
$_['filter_manufacturer'] = 'Производитель';
$_['filter_manufacturer_i'] = 'Вы можете выбрать нескольких производителей, начните вводить название для быстрого поиска нужного производителя.';
$_['filter_category'] = 'Категория';
$_['filter_category_i'] = 'Вы можете выбрать несколько категорий, начните вводить название для быстрого поиска нужной категории';
$_['filter_store'] = 'Магазин';
$_['filter_limit'] = 'Лимит';
$_['filter_limit_i'] = 'Ограничение по количеству экспортируемых данных  Начать: начать экспорт с этого товара - Лимит: сколько товаров экспортировать';
$_['filter_limit_start'] = 'Начать';
$_['filter_limit_limit'] = 'Лимит';

// Export options
$_['export_options'] = 'Опции';
$_['param_image_path'] = 'Режим пути к изображению';
$_['param_image_path_i'] = 'Используйте полный url чтобы получить абсолютный адрес к вашему изображению - Стандартный путь Opencart используется только при экспорте на том же сайте.';
$_['image_path_relative'] = 'Стандартный путь  Opencart';
$_['image_path_absolute'] = 'Полный url';

// Content editor
$_['text_tab_2'] = 'Опции';
$_['tab_option_1'] = 'Производительность';
$_['entry_batch_import'] = 'Импортировать за раз';
$_['entry_batch_export'] = 'Экспортировать за раз';
$_['batch_import_i'] = 'Выберите количество обрабатываемых товаров за один запрос, изменяйте эти настройки с целью улучшения параметров импорта/экспорта.<br/>Высокое количество товаров за один запрос может ускорить процесс, но слишком большие показатели могут привести к сбою из-за недостатков ресурсов вашего сервера.';

$_['text_tab_about'] = 'О модуле';

// Entry
$_['entry_status'] = 'Статус:';

// Info
$_['info_title_default']		= 'Помощь';
$_['info_msg_default']	= 'Раздел помощи для данной темы не найден';

// Error
$_['error_permission'] = 'ВНИМАНИЕ: у вас нет прав доступа для изменения этого расширения!';
$_['error_permission_demo'] = 'Режим ДЕМО, сохраненеие невозможно';
