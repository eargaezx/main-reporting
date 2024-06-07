<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mercadito Naranja - ¡Tu mercadito de confianza!</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,500;0,600;0,700;1,800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/owl.carousel/<?= Router::url(['controller' => '/'], true) ?>/landing/owl.carousel.min.css" rel="stylesheet">
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= Router::url(['controller' => '/'], true) ?>/landing/css/style.css" rel="stylesheet">

  <style type="text/css">
  	html {
  		scroll-behavior: smooth;
	}
  </style>


<body>

  <!-- ======= Header ======= -->
  <?= $this->fetch('content')?>
  
  
  
<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/jquery/jquery.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/php-email-form/validate.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/venobox/venobox.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/waypoints/jquery.waypoints.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/counterup/counterup.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/vendor/aos/aos.js"></script>

<!-- Template Main JS File -->
<script src="<?= Router::url(['controller' => '/'], true) ?>/landing/js/main.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        const ventajas = 1285;
        const incluye = 3378;
        const para = 4218;
        const costo = 4828;
        const marketing = 5632;
        $("#ventajas").on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: ventajas
            }, 80, function () {});
        });
        $("#incluye").on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: incluye
            }, 80, function () {});
        });
        $("#para").on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: para
            }, 80, function () {});
        });
        $("#costo").on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: costo
            }, 80, function () {});
        });
        $("#marketing").on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: marketing
            }, 80, function () {});
        });
    });

</script>

</body>

</html>
