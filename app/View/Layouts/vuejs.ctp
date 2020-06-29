<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="bg07" lang="vi">
<head>
	<?php echo $this->Html->charset(); ?>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">

	<meta property="fb:admins" content="100000086780889" />
	<meta property="fb:app_id" content="549731485071114" />

	<meta property="og:locale" content="vi_VN" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="GHTK - Dịch vụ giao hàng trong ngày chuyên nghiệp" />

	<meta property="og:description" content="Dịch vụ giao hàng trong ngày chuyên nghiệp" />
	<meta property="og:site_name" content="Giao hàng 6h trong ngày" />
	<meta property="og:image" content="http://giaohangtietkiem.vn/wp-content/uploads/2015/10/2015-10-16_13-10-40-copy.png" />
    <title>Hệ thống quản lý chấm công nội bộ GHTK</title>

	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:description" content="Dịch vụ giao hàng trong ngày chuyên nghiệp"/>
	<meta name="twitter:title" content="GHTK - Dịch vụ giao hàng trong ngày chuyên nghiệp"/>
	<meta name="twitter:domain" content="Giao hàng 6h trong ngày"/>

	<?php $prefix = Router::url('/' . $this->plugin  . '/'); ?>

	<link rel="apple-touch-icon-precomposed" sizes="144x144"
	      href="<?php echo $prefix?>img/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114"
	      href="<?php echo $prefix?>img/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72"
	      href="<?php echo $prefix?>img/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed"
	      href="<?php echo $prefix?>img/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" type="image/png" href="<?php echo $prefix?>img/ico/favicon.png">

	<?php
	if (!empty($isCod) || !empty($isGuest)) {
		echo $this->element('Admin.Asset/css/layout_cod'); 

	} else {
        echo $this->element('Admin.Asset/css/layout_admin'); 
        
	}

	echo $this->fetch('pageCss');

	echo $this->element('js_constants');

	echo $this->element('Admin.Asset/layout_default');

    ?>

	<?php if(isset($dataRequireChangePW['title'])): ?>
		<script type="text/javascript">
            //<![CDATA[
            var dataExpiresChangePw = {
                title: '<?php echo $dataRequireChangePW['title'];?>',
                message: '<?php echo $dataRequireChangePW['message'];?>'
            };
            //]]>
		</script>
	<?php endif;?>
</head>
<body>
<div id="page">
	<?= $this->element('Admin.header');?>

	<section id="content" role="main">
        <div id="ghtkapp">
            <?= $this->fetch('content');?>
        </div>
	</section>

	<?= $this->element('Admin.footer');?>
</div>

<?php
if (empty($isCod) && empty($isGuest) && empty($isHRM)) {
	echo $this->element('Admin.Tables/task_activities');
}

if (!empty($canChat)) {
	echo $this->element('Admin.Tables/online_customers');
}

$this->Html->script('jquery');

//echo $this->Html->script(
//    'Admin.axios/axios.min'
//);
if(strtolower(Configure::read('ENV')) == DEVELOPMENT){
    echo $this->Html->script(
        'Admin.vue/vue'
    );
} else{
    echo $this->Html->script(
        'Admin.vue/vue.min'
    );
}


if (!empty($isCod) || !empty($isGuest)) {
	echo $this->element('Admin.Asset/layout_body_cod'); 
    
} else {
	if (!empty($canChat)) {
        echo $this->element('Admin.Asset/layout_body_chat');
        
	} else {
        echo $this->element('Admin.Asset/layout_body_admin'); 
	}
}

//echo $this->element('Admin.Asset/vue_data');

echo $this->fetch('inlineScript');

echo $this->fetch('pageScript');

echo $this->fetch('modal');
?>

<?= $this->element('Admin.admin_bottom_actions'); ?>
</body>
</html>
