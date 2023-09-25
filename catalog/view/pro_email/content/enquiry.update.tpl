<p><strong>[text_hello_customer]</strong></p>

<p>[text_update_status]</p>

[if_message]
<p>[text_update_comment]</p>
<p>{message}</p>
[/if_message]

[if_customer]
  <p>[text_order_link]</p>
  <p>[button={order_url}][text_order_btn][/button]</p>
[/if_customer]

<p>[text_goodbye]</p>