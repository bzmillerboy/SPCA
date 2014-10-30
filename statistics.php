<?php require("incl.head.php"); ?>
<title>Statistics – SPCA Cincinnati</title>
<?php require("incl.top.php"); ?>

</section><!--/header-section-->

<section class="single-page">

  <div class="content">
    <div class="container">
    <h1 class="page-title">Statistics</h1>
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-md-9">
            <h2 >Animal Statistics</h2>
            <p>Thanks to our Adopters, Contributors and Volunteers, the SPCA Cincinnati has been able to sustain our family-friendly adoption fees to help in saving more animals' lives. Our two shelters are open 360 days a year, totaling 4,442 hours, to serve the Tri-State.</p>
            <br>

            <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
              <tr>
                  <th>Year</th>
                  <th>2010</th>
                  <th>2011</th>
                  <th>2012</th>
                  <th>2013</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                  <td>Adoptions</td>
                  <td>3,593</td>
                  <td>4,458</td>
                  <td>6,153</td>
                  <td>7,239</td>
              </tr>
              <tr>
                  <td>Increase over 2010</td>
                  <td>–</td>
                  <td>+865</td>
                  <td>+2,560</td>
                  <td>+3,346</td>
              </tr>
              <tr>
                  <td>% Increase over 2010</td>
                  <td>–</td>
                  <td>+24%</td>
                  <td>+72%</td>
                  <td>+102%</td>
              </tr>
              </tbody>
            </table>
            </div>
            <br>
            <div style="width: 100%">
              <canvas id="canvas" height="450" width="600"></canvas>
            </div>

            <script>
              var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
              var barChartData = {
                labels : ["2010","2011","2012","2013"],
                datasets : [
                  {
                    fillColor : "rgba(207, 0, 28, 1)",
                    strokeColor : "rgba(207, 0, 28, 1)",
                    highlightFill: "rgba(207, 0, 28, .8)",
                    highlightStroke: "rgba(207, 0, 28, .9)",
                    data: [3593, 4458, 6153, 7239]
                  }
                ]
              }
              window.onload = function(){
                var ctx = document.getElementById("canvas").getContext("2d");
                window.myBar = new Chart(ctx).Bar(barChartData, {
                  responsive : true,
                  //Number - Spacing between each of the X value sets
                  barValueSpacing : 15,
                });
              }
            </script>

            <br>
            <p>As a community we are saving more lives every year. Together we can reduce pet homelessness and animal suffering. By preventing abuse, keeping pets in homes, promoting adoptions and providing pre-adoption care we have increased the number of animals saved while reducing the number of animals entering our shelters. You are an integral part of this success!</p>

            <div class="row">
              <div class="col-sm-4">
                <div class="panel panel-default text-center">
                  <div class="panel-heading">
                    <h3 class="panel-title">2011 Animal Summary</h3>
                  </div>
                  <div class="panel-body">
                    <a class="btn btn-primary" href="/docs/2011_Animal_Statistics_Summary.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="panel panel-default text-center">
                  <div class="panel-heading">
                    <h3 class="panel-title">2011 Dog & Cat Stats</h3>
                  </div>
                  <div class="panel-body">
                    <a class="btn btn-primary" href="/docs/2011_DogCat_Stats.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="panel panel-default text-center">
                  <div class="panel-heading">
                    <h3 class="panel-title">2012 Animal Summary</h3>
                  </div>
                  <div class="panel-body">
                    <a class="btn btn-primary" href="/docs/2012_Animals_Statistics_Summary.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                  </div>
                </div>
              </div>
              
              <div class="clearfix"></div>
              
              <div class="col-sm-4">
                <div class="panel panel-default text-center">
                  <div class="panel-heading">
                    <h3 class="panel-title">2012 Dog & Cat Stats</h3>
                  </div>
                  <div class="panel-body">
                    <a class="btn btn-primary" href="/docs/2012_DogCat_Stats.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="panel panel-default text-center">
                  <div class="panel-heading">
                    <h3 class="panel-title">2013 Animal Summary</h3>
                  </div>
                  <div class="panel-body">
                    <a class="btn btn-primary" href="/docs/2013_Animal_Statistics_Summary.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="panel panel-default text-center">
                  <div class="panel-heading">
                    <h3 class="panel-title">2013 Dog & Cat Stats</h3>
                  </div>
                  <div class="panel-body">
                    <a class="btn btn-primary" href="/docs/2013_DogCat_Stats.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                  </div>
                </div>
              </div>
              
            </div>


            <hr>


            <h2>Financial Reports</h2>
            <p>Here is a collection of our annual reports, financial statements and other financial information. Click the buttons below to download the documents.</p>

          <div class="row">
            <div class="col-sm-4">
              <div class="panel panel-default text-center">
                <div class="panel-heading">
                  <h3 class="panel-title">2012 Annual Report</h3>
                </div>
                <div class="panel-body">
                  <a class="btn btn-primary" href="/docs/2012_Annual_Report.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="panel panel-default text-center">
                <div class="panel-heading">
                  <h3 class="panel-title">2012 Financial Statements</h3>
                </div>
                <div class="panel-body">
                  <a class="btn btn-primary" href="/docs/2012_SPCA_Financial_Statements.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="panel panel-default text-center">
                <div class="panel-heading">
                  <h3 class="panel-title">2012 Form 990</h3>
                </div>
                <div class="panel-body">
                  <a class="btn btn-primary" href="/docs/2012_SPCA_Cincinnati_990.pdf" target="_blank"><i class="fa fa-download"></i> Download</a>
                </div>
              </div>
            </div>
          </div>


        </div>
        <div class="col-md-3">
          <?php require("incl.about-sidenav.php"); ?>
        </div>


      </div>
    </div>
  </div>
</section><!--/single-page-section-->

<hr>

<?php require("incl.bottom.php"); ?>
<!-- Page-Specific Scripts -->
<script>
  $("a#about-statistics").addClass('active');
</script>
</body>
</html>
