<?php require("incl.head.php"); ?>


<title>Cincinnati SPCA</title>
<?php require("incl.top.php"); ?>

  <div class="container hero">
    <h1 class="hero-title">Striving to strengthen the<br> human-animal bond</h1>
    <div class="row">
      <div class="col-sm-4">
        <h3>Support Us</h3>
        <p>Support from you helps us protect lost, abandoned and mistreated pets in our community.</p>
        <a class="btn btn-primary btn-block btn-lg" href="#">Give</a>
      </div>
      <div class="col-sm-4">
        <h3>Find a Friend</h3>
        <p>Support from you helps us protect lost, abandoned and mistreated pets in our community.</p>
        <a class="btn btn-default-inverse btn-block btn-lg" href="/available-animals">Adopt</a>
      </div>

      <div class="col-sm-4">
        <h3>Help Out</h3>
        <p>Support from you helps us protect lost, abandoned and mistreated pets in our community.</p>
        <a class="btn btn-default-inverse btn-block btn-lg" href="/volunteer-at-our-shelter">Volunteer</a>
      </div>
    </div>
  </div><!--/hero -->
</section><!--/header-section-->





<section class="slider-section">
  <ul id="slider">
    <li>
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <img alt= "Best Friends and Brews"class="bfab-logo" src= "images/best-friends-and-brews-logo.jpg" width="100%">
            <p>Please join us for a beer tasting event in support of SPCA&nbsp;Cincinnati. Live entertainment and SPCA&nbsp;choice raffle as well as food from local Cincinnati restaurants.<br><br>
            <strong>When:</strong> Friday, October 10, 2014<br>
            <strong>Where:</strong>&nbsp;SPCA Sharonville Shelter<br>
            11900 Conrey Road</p>
            <a class="btn btn-primary" href="#">Buy Tickets</a>
            <a class="btn btn-default" href= "#">Learn More</a>
          </div>
          <div class="col-lg-6">
            <img alt= "Best Friends and Brews Sponsors"class="" src= "images/brew-logos.jpg" width="100%">
          </div>
        </div>
      </div>
    </li>
    <li>
      <div class="container">
          <div class="row">
            <div class="col-lg-7">
              <h1>Double the help</h1>
              <h1>Double the hope</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
              <a class= "btn btn-primary" href="#">Donate Now</a>
              <a class= "btn btn-default" href="#">Learn More</a>
            </div>
            <div class="col-lg-5">
              <img alt= "A dog and a cat" class="img-responsive" src= "images/double-donate.jpg">
            </div>
          </div>
        </div>
      </div>
    </li>
  </ul>
</section><!--/slider-section-->







<section class="about-section">

  <div class="container about-content">
    <div class="row">
      <div class=" col-lg-10 col-lg-offset-1">
        <h1>About</h1>
        <p>Founded in 1873, SPCA Cincinnati has a long history of service to the Greater Cincinnati community.&nbsp; Originally founded as the Animal and Human Humane Society, it was the first organization of its kind in Ohio.&nbsp; Eventually two separate agencies evolved, leading to the creation of the Hamilton County Society for the Prevention of Cruelty to Animals, which was headquartered in downtown Cincinnati.&nbsp; The headquarters moved to Colerain Avenue in Northside in the 1930s, and the current building was constructed in 1964.&nbsp; Thanks to the generosity of the community, SPCA Cincinnati built the Sharonville Humane Center in 2008, and received Simmonds Farm as a donation in 2009.Today, SPCA Cincinnati remains the very epitome of true service to the people and animals in our community. SPCA Cincinnati takes in over 7,000 dogs and over 9,000 cats annually.&nbsp; In addition to providing shelter and rehabilitation to these animals in need, SPCA Cincinnati collaborates with government, law enforcement, and social service organizations in its ongoing effort to encourage humane principles and foster the human-animal bond.&nbsp;<br> <br> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
      </div>
    </div>
  </div>

  <div class="newsletter-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Get Updates</h2>
          <p>Sign up for our newsletter and stay up to date on the latest going around with the SPCA&nbsp;Cincinnati. No spam, we promise. </p>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <form class="form" id="newsletter-form" role="form" method="post">
          <div class="form-group col-md-3">
            <input type="text" class="form-control" name="First_Name" placeholder="First name" aria-describedby="name-format">
          </div>
          <div class="form-group col-md-3">
            <input type="text" class="form-control" name="Last_Name" placeholder="Last name" aria-describedby="name-format">
          </div>
          <div class="form-group col-md-3">
            <input type="email" class="form-control" name="email" placeholder="Email Address">
          </div>
          <div class="form-group col-md-3">
            <button type="submit" class="btn btn-secondary btn-block">Sign Up</button>
          </div>
        </form>
        <script>
        $(document).ready(function() {
            $('#newsletter-form').bootstrapValidator({
                fields: {
                    First_Name: {
                        message: 'The first name field is not valid',
                        validators: {
                            notEmpty: {
                                message: 'First Name is required and cannot be empty'
                            },
                            different: {
                                field: 'Last_Name',
                                message: 'The First Name and Last Name cannot be the same as each other'
                            }
                        }
                    },
                    Last_Name: {
                        message: 'The last name field is not valid',
                        validators: {
                            notEmpty: {
                                message: 'Last Name is required and cannot be empty'
                            },
                            different: {
                                field: 'First_Name',
                                message: 'The First Name and Last Name cannot be the same as each other'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email address is required and cannot be empty'
                            },
                            emailAddress: {
                                message: 'The email address is not a valid'
                            }
                        }
                    }
                }
            });
        });
        </script>
      </div>
    </div>
  </div><!--/newsletter-section-->

</section><!--/about-section-->






<section class="news-section">
  <div class="container">
    <div class="row">
      <div class=" col-lg-5 news-list">
        <?php require("incl.news.php"); ?>
        <a class="btn btn-default" href="news">View More News</a>
      </div>
      <div class="col-lg-5 col-lg-offset-2  news-list">
        <?php require("incl.events.php"); ?>
        <a class="btn btn-default" href="events">View More Events</a>
      </div>
    </div>
  </div>
</section><!--/news-section-->





<?php require("incl.bottom.php"); ?>
