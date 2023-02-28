<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SIMAKDA Service</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 30px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	
	</style>
    
    
<?php 
    $ippublic="https://simakda.kalbarprov.go.id/SIMAKDAservice_2023/index.php/api/"; 
    
?>    
</head>
<body>

<div id="container">
	<h1><b>SIMAKDA Service</b></h1>

	<div id="body">
    
    <table border="0" width="100%" style="font-size: 15px;">
<tr>
<td align="center">



<tr>
<td align="left" colspan="2">
<p><b><i>HTTP Request </i> SAMSAT :</b></p>
</td>
</tr>

<tr>
<td align="center" style="border-bottom: solid 1px gray;">
<p></p>
</td>
<td>
        <table width="80%">
        <tr>
        <td>1.</td>
        <td>Format untuk Respon <i>View</i> dan <i>Save</i> Data (Json)</td>
        </tr> 
        <tr>
        <td></td>
        <td>- ViewValidasi : <code><font size="3px"><?php echo $ippublic;?>setor/format/json</font></code></td>
        </tr> 
        <tr>
        <td></td>
        <td>- ViewValidasi : <code><font size="3px"><?php echo $ippublic;?>kasda/format/json</font></code></td>
        </tr>                
        <tr>
        <td></td>
        <td>- View : <code><font size="3px"><?php echo $ippublic;?>view/format/json</font></code></td>
        </tr>
        <tr>
        <td></td>
        <td>- Save : <code><font size="3px"><?php echo $ippublic;?>save/format/json</font></code></td>
        </tr>
       </table>
</td>
</tr>

</table>		
    
    </div>

	<p class="footer" style="text-align:left;">Info Lebih Lanjut: <b>MSM Consultans</b></p>
</div>

</body>
</html>