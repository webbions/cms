<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

    <h1>wellcome Client</h1>

<?php
if(!isset($_SESSION['username']))
{
redirect('Client/index');
}
echo $_SESSION['username'];

?>

<a href="<?php echo base_url(); ?>Client/index/logout">Logout</a>
</body>
</html>