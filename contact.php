<?php require("incl.head.php"); ?>
<title>Contact SPCA Cincinnati</title>
<?php require("incl.top.php"); ?>

</section><!--/header-section-->

<section class="single-page">

  <div class="content">
    <div class="container">
    <h1 class="page-title">Contact</h1>
    <div class="clearfix"></div>
      
      <div class="row">
        <div class="col-md-12 visible-xs visible-sm">
              <br>
              <div class="alert alert-warning" role="alert"><strong>Please note!</strong> To report an emergency or an animal cruelty situation, please CALL instead of emailing so we can respond to the situation as quickly as possible.  Call 513-541-6100 between 8am and 6pm, and 513-825-2280 after 6pm.<br><br><a class="btn btn-primary" href="#">Report an abused or stray animal</a>
              </div>
          </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
            <div class="event-overview">
            <br>
                <div class="row">
                    <div class="col-sm-4 clearfix">
                        <div class="icon fa fa-map-marker"></div>
                        <div class="info">
                            <h5>Address</h5>
                            <span class="text-muted">3949 Colerain Ave.<br>Cincinnati, OH 45223</span>
                        </div>
                    </div>
                    <div class="col-sm-4 clearfix">
                        <div class="icon fa fa-phone"></div>
                        <div class="info">
                            <h5>Phone Number</h5>
                            <span class="text-muted">513.541.6100</span>
                        </div>
                    </div>
                    <div class="col-sm-4 clearfix">
                        <div class="icon fa fa-fax"></div>
                        <div class="info">
                            <h5>Fax Number</h5>
                            <span class="text-muted">513.542.7722</span>
                        </div>
                    </div>
                </div>
            </div>
        <br>
        <a href="https://www.google.com/maps/place/1230+Elm+Street,+Cincinnati,+OH/"><img class="img-responsive" src="http://maps.googleapis.com/maps/api/staticmap?center=1230+Elm+Street,+Cincinnati,+OH&zoom=14&scale=2&size=640x175&maptype=roadmap&sensor=false&key=AIzaSyD22-VhdHtHpOnaqPwfTwsh7L6ArMRbUbI&format=jpg&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:%7C1230+Elm+Street,+Cincinnati,+OH" alt="Google Map of 3949 Colerain Ave., Cincinnati, OH 45223"></a>
        <br>
        </div>

        <div class="col-md-6">


            <h3>Send Us a Message</h3>
            <form role="form" action="/contact" method="post" data-abide>
                <div class="form-group">
                    <label for="contact-name">Name</label>
                    <input class="form-control" type='text' name='name' id="contact-name" value="<?=$_POST["name"]?>" required>
                </div>
                <div class="form-group">
                    <label for="contact-email">Email</label>
                    <input class="form-control" type='email' name='email' id="contact-email" value="<?=$_POST["email"]?>" required>
                </div>
                <div class="form-group">
                    <label for="contact-message">Message</label>
                    <textarea class="form-control" type='text' name='message' id="contact-message" rows="4"><?=$_POST["message"]?></textarea>
                </div>
                <div class="form-group">
                    <label for="contact-validation">What is 4 plus two?</label>
                    <input class="form-control" type='number' name='validation' id="contact-validation" value="<?=$_POST["validation"]?>" required>
                </div>
                    <input type='submit' name='submit' class="btn btn-default" value="Send" />
                </form>

        </div>

        <div class="col-md-6 hidden-xs hidden-sm">
            <br>
            <div class="alert alert-warning" role="alert"><strong>Please note!</strong> To report an emergency or an animal cruelty situation, please CALL instead of emailing so we can respond to the situation as quickly as possible.  Call 513-541-6100 between 8am and 6pm, and 513-825-2280 after 6pm.<br><br><a class="btn btn-primary" href="#">Report an abused or stray animal</a>
            </div>
        </div>


      </div>
    </div>
  </div>
</section><!--/single-page-section-->

<hr>

<?php require("incl.bottom.php"); ?>
<!-- Page-Specific Scripts -->
<script>
  $("a#about-about").addClass('active');
</script>
</body>
</html>
