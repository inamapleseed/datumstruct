<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>How to increase the memory limit?</title>
<style type="text/css">
body { font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
li { margin-bottom: 5px; }
code { font-size: 15px; }
.bold { font-weight: bold; }
</style>
</head>

<body>
	<h2>How to increase the memory limit?</h2>
    <p>The server settings that should be modified are:</p>
    <ul>
        <li><code class="bold">memory_limit</code> - this setting affects the Export functionality in ExcelPort. You should increase it in case you receive errors on this feature.</li>
        <li><code class="bold">max_execution_time</code> - it is recommended to use a higher value in case your database has lots of entries. It is estimated that a database with about 80000 products takes about 5-6 minutes to get backed up. You should be fine if you set this setting at 600 seconds</li>
        <li><code class="bold">upload_max_filesize</code> - this sets the maximum file size your server can accept. Set it accordingly to the file you need to upload</li>
        <li><code class="bold">post_max_size</code> - usually a bit higher than <code>upload_max_filesize</code></li>
    </ul>
    
    <h3>Method 1: Modify your php.ini file with the following entries:</h3>
    <code>
    memory_limit = 256M<br />
    max_execution_time = 600<br />
    upload_max_filesize = 200M<br />
    post_max_size = 201M
    </code>
    
    <h3>Method 2: In your /admin/ folder of OpenCart, create a .htaccess file with the following entries:</h3>
    <code>
    php_value memory_limit 256M<br />
    php_value max_execution_time 600<br />
    php_value upload_max_filesize 200M<br />
    php_value post_max_size 201M
    </code>
    <p>You can find additional information in <a href="http://php.net/manual/en/ini.core.php" target="_blank">php.net</a></p>
</body>
</html>
