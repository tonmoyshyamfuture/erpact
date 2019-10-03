<style>

.form-control{border-radius: 0; box-shadow: none; height: 40px;}
.form-control:focus{box-shadow: none; text-shadow: none;}
.td-input{padding: 0 !important;}
.td-input .form-control{border-color: transparent; height: auto}
.td-input .form-control:focus{border-color: #d2e0ee;}
.table{border:1px solid #ddd; margin: 0;}
.table>thead>tr>th{border: 0;}
.table>tbody>tr>td{ vertical-align: middle }
.table>thead>tr>th,.table>tfoot>tr>th,.table>tbody>tr>td{font-weight: normal;padding:8px 4px}
.form-control.prod-desc{position: relative; }
.form-control.prod-desc:focus{position: absolute; left: 20px; top: 46px; right: 2px; width: calc(100% - 40px);}

</style>
<div class="side_toggle">
    <div id="myDiv" style="display: none;"><button class="btn btn-sm btn-danger myButton  btn-closePanel" autocomplete="off"><i class="fa fa-times"></i></button>
        <form style="padding:20px;">
            <div class="form-group">
                <label for="">Form Submission</label>
                <div class="form-group">
                                        <div class="radio">
                        <label><input type="radio" value="1" name="activity_submit" checked="" autocomplete="off">Submit &amp; Show New Form</label>
                    </div>
                                        <div class="radio">
                        <label><input type="radio" value="2" name="activity_submit" autocomplete="off">Submit &amp; Show List</label>
                    </div>
                                        <div class="radio">
                        <label><input type="radio" value="3" name="activity_submit" autocomplete="off">Submit &amp; View</label>
                    </div>
                                    </div>
            </div>

            <div class="form-group">
                <label for="">Adder Account</label>
                <div class="form-group">
                    <a href="javascript:void(0);" class="new-ledger-btn">Add Ledger</a><span class="pull-right text-muted">Ctrl+Shift+l</span><br>
                    <a href="javascript:void(0);" class="add-group-btn">Add Group</a><span class="pull-right text-muted">Ctrl+Alt+g</span><br>
                    <a href="">Add Category</a><br>
                    <a href="">Add Unit</a><br>
                    <a href="">HSN</a><br>
                    <a href="">Add Product</a><br>
                    <a href="">Add Service</a>

                </div>
            </div>
            <div class="form-group">
                <label for="">Select currency</label>
                <div class="form-group">
                    <select class="form-control" id="selected_currency" autocomplete="off">
                                                        <option value="1">USD</option>
                                                                <option value="2">EUR</option>
                                                                <option value="3">AFN</option>
                                                                <option value="4">JPY</option>
                                                                <option value="5">AED</option>
                                                                <option value="6" selected="">INR</option>
                                                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="">Make this entry as post dated entry?</label>
                <div class="form-group">
                    <label class="radio-inline"><input type="radio" value="1" name="postdated" autocomplete="off">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="postdated" checked="true" autocomplete="off">No</label>

                </div>
            </div>
                            <!-- <div class="form-group">
                    <label for="">Do you want service entry?</label>
                    <div class="form-group ">
                        <label class="radio-inline"><input type="radio" value="1" name="service_product" >Yes</label>
                        <label class="radio-inline"><input type="radio" value="0" name="service_product" checked>No</label>

                    </div>
                </div> -->
            <div class="form-group">
                <label for="">Do you want reverse entry with respect to branch?</label>
                <div class="form-group">
                    <label class="radio-inline"><input type="radio" value="1" name="select_branch_entry_no" class="branch-entry-no" autocomplete="off">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="select_branch_entry_no" checked="true" class="branch-entry-no" autocomplete="off">No</label>

                </div>
            </div>
                        <div class="form-group">
                <label for="">Do you want to set tax 0 for different export country?</label>
                <div class="form-group">
                    <label class="radio-inline"><input type="radio" value="1" name="tax_status_country" class="tax-status-country" autocomplete="off">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="tax_status_country" checked="true" class="tax-status-country" autocomplete="off">No</label>

                </div>
            </div>
                    </form>
    </div>
</div>

<div class="wrapper2">
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1>Add Sales</h1>
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <button type="button" class="myButton btn btn-settings btn-svg"><i data-feather="settings"></i></button>
                </div>
            </div>
        </div>
    </section>
    <ul class="breadcrumb"><li><a href="">Home</a> <span class="divider"></span></li><li><a href="">Transaction</a> <span class="divider"></span></li><li><a href="">sales</a> <span class="divider"></span></li><li class="active">Add</li></ul>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                  <div class="box-body">

                    <div class="xx">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Invoice Number</label>
                                <input type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Debtors <span class="text-success">(Regular)</span></label>
                                <input type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Sales</label>
                                <input type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Advance Bill Name" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Recurring" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Ref. No." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Ref. Date" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Billing Address</label><br>
                                <strong>Company name</strong><br>
                                125 AJC Bose Road, Kolkata - 700 001<br>
                                West Bengal, India<br>
                                GSTIN: 19AAABBBCCCDD
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Shipping Address</label><br>
                                <strong>Company name</strong><br>
                                125 AJC Bose Road, Kolkata - 700 001<br>
                                West Bengal, India<br>
                                GSTIN: 19AAABBBCCCDD
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Credit days</label>
                                <input type="text" class="form-control" placeholder="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Salesman" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label>Credit Limit</label>
                                <input type="text" class="form-control" placeholder="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Others" autocomplete="off">
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th width="20"></th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Qty.</th>
                                        <th class="text-right">Rate</th>
                                        <th class="text-right">Dis.</th>
                                        <th class="text-right">Gross Total</th>
                                        <th class="text-right">CGST</th>
                                        <th class="text-right">SGST</th>
                                        <th class="text-right">CESS</th>
                                        <th class="text-right">Total Tax</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="position: relative !important">
                                        <td><a href=""><i class='fa fa-trash-o text-danger'></i></a></td>
                                        <td>HDPE Granules</td>
                                        <td class="td-input">
                                            <input type="text" class="form-control prod-desc" placeholder="product description" >
                                        </td>
                                        <td class="td-input">
                                            <input type="text" class="form-control" placeholder="Unit" >
                                        </td>
                                        <td class="td-input">
                                            <input type="text" class="form-control" placeholder="Qty" >
                                        </td>
                                        <td class="td-input">
                                            <input type="text" class="form-control text-right" placeholder="Rate">
                                        </td>
                                        <td class="td-input">
                                            <input type="text" class="form-control text-right" placeholder="Discount">
                                        </td>
                                        <td class="text-right">10,00,000.00</td>
                                        <td class="text-right">
                                            50,000.00<br>5(%)
                                        </td>
                                        <td class="text-right">
                                            50,000.00<br>5(%)
                                        </td>
                                        <td class="text-right">
                                            20,000.00<br>2(%)
                                        </td>
                                        <td class="text-right">
                                            1,20,000.00
                                        </td>
                                        <td class="text-right">9,365,550.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="13" class="td-input">
                                            <input type="text" class="form-control" placeholder="Search product..." >
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="11" class="td-input"><input type="text" class="form-control" placeholder="Expenses" ></td>
                                        <td colspan="2" class="text-right">5000.00</td>
                                    </tr>
                                    <tr class="bold">
                                        <td colspan="7">Total</td>
                                        <td class="text-right">10,00,000.00</td>
                                        <td class="text-right">50,000.00</td>
                                        <td class="text-right">50,000.00</td>
                                        <td class="text-right">20,000.00</td>
                                        <td  class="text-right">1,20,000.00</td>
                                        <td  class="text-right">9,365,550.00</td>
                                    </tr>
                                </tbody>
                            </table>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">

                            <div class="form-group">
                                <label>Notes</label>
                                <input type="text" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Terms &amp; Conditions</label>
                                <input type="text" class="form-control" value="Goods once sold cannot be returned except manufacturing defects." autocomplete="off">
                            </div>
                        </div>

                    </div>
                </div>
                  </div>
                  <div class="box-footer">
                      <div class="footer-button" >
                          <button type="submit" class="btn  btn-primary" >Save</button>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
