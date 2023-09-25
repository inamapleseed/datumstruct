<p><strong>[text_hello_customer]</strong></p>

<p>[text_order_confirm]</p>

[if_customer]
  <p>[text_order_link]</p>
  <p>[button={order_url}][text_order_btn][/button]</p>
[/if_customer]

[if_download]
  <p>[text_download_link]</p>
  <p>[button={download_url}][text_download_btn][/button]</p>
[/if_download]
  
<p>{invoice}</p>

<p>[text_goodbye]</p>