<!-- Nav tabs -->
<div class="row">
      <div class="col-xs-12">
          <ul class="nav nav-pills nav-justified thumbnail setup-panel">
              <li class="active"><a href="#step-1">
                  <span class="list-group-item-heading">1. Amount</span>
              </a></li>
              <li class="disabled"><a href="#step-2">
                  <span class="list-group-item-heading">2. Contact</span>
              </a></li>
              <li class="disabled"><a href="#step-3">
                  <span class="list-group-item-heading">3. Payment</span>
              </a></li>
          </ul>
      </div>
</div>

<!-- Tab content -->
<form class="form-horizontal">
<fieldset>
<div class="row setup-content" id="step-1">
    <div class="col-xs-12">
        <div class="col-md-12 well">
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="amount">Amount</label>
                  <div class="col-md-6">
                    <div class="input-group input-group-lg">
                    <span class="input-group-addon">$</span>
                    <input id="amount" name="amount" type="text" placeholder="Amount" class="form-control input-lg" required="">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>

              <!-- Select Basic -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="frequency">Frequency</label>
                <div class="col-md-6">
                  <select id="frequency" name="frequency" class="form-control">
                    <option value="1">One Time</option>
                    <option value="2">Weekly</option>
                    <option value="3">Monthly</option>
                    <option value="4">Bi-Monthly</option>
                    <option value="5">Quarterly</option>
                    <option value="6">Semi-Annually</option>
                    <option value="">Annually</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <a id="activate-step-2" class="btn btn-primary">Next: Contact</a>
                </div>
              </div>
        </div>
    </div>
</div>
<div class="row setup-content" id="step-2">
    <div class="col-xs-12">
        <div class="col-md-12 well">
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 col-xs-12 control-label" for="first_name">Name</label>
                <div class="col-md-3 col-xs-6">
                  <input id="first_name" name="first_name" type="text" placeholder="First Name" class="form-control input-md" required="">
                </div>
                <div class="col-md-3 col-xs-6">
                  <input id="last_name" name="last_name" type="text" placeholder="Last Name" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="street">Street Address</label>
                <div class="col-md-6">
                  <input id="street" name="street" type="text" placeholder="Eg. 1234 Shepherd Lane" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="city">City</label>
                <div class="col-md-6">
                  <input id="city" name="city" type="text" placeholder="City" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Select Basic -->
              <div class="form-group">
                <label class="col-md-4 col-xs-12 control-label" for="state">State / Zip</label>
                <div class=" col-md-4 col-xs-8">
                  <select id="state" name="state" class="form-control">
                    <option value="1">Ohio</option>
                    <option value="2">Kentucky</option>
                    <option value="3">Indiana</option>
                  </select>
                </div>
                <div class="col-md-2 col-xs-4">
                 <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="email">Email</label>
                <div class="col-md-6">
                  <input id="email" name="email" type="text" placeholder="Eg. shepherd23@gmail.com" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="phone">Phone</label>
                <div class="col-md-6">
                  <input id="phone" name="phone" type="text" placeholder="Eg. 513.541.6100" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Textarea -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="comments">Comments</label>
                <div class="col-md-6">
                  <textarea class="form-control" id="comments" name="comments">Add notes or comments.</textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <a id="activate-step-3" class="btn btn-primary">Next: Payment</a>
                </div>
              </div>
        </div>
    </div>
</div>
<div class="row setup-content" id="step-3">
    <div class="col-xs-12">
        <div class="col-md-12 well">
              <!-- Select Basic -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="card_type">Card Type</label>
                <div class="col-md-6">
                  <select id="card_type" name="card_type" class="form-control">
                    <option value="1">Visa</option>
                    <option value="2">Mastercard</option>
                  </select>
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="card_num">Card Number</label>
                <div class="col-md-6">
                  <input id="card_num" name="card_num" type="text" placeholder="Valid card number" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Select Basic -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="exp_month">Expiration</label>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-xs-3">
                      <input id="exp_month" name="exp_month" class="form-control" placeholder="MM" required>
                    </div>
                    <div class="col-xs-3">
                      <input id="exp_day" name="exp_day" class="form-control" placeholder="DD" required>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="security_code">Security Code</label>
                <div class="col-md-2">
                  <input id="security_code" name="security_code" type="text" placeholder="CVV" class="form-control input-md" required="">
                </div>
              </div>

              <!-- Button -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="submit"></label>
                <div class="col-md-4">
                  <button id="submit" name="submit"  class="btn btn-primary">Give</button>
                </div>
              </div>
        </div>
    </div>
</div>
</fieldset>
</form>