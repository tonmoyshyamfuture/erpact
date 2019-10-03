import React, { Component } from 'react';

class Dashboard extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount = () => {    
    var location = this.props.location.pathname.split('/')[1];
    var current_page = location.split('-');
    var page_title = current_page[0]
    page_title = page_title.toLowerCase().replace(/\b[a-z]/g, (letter) => {
        return letter.toUpperCase();
    });
    document.title = "ACT | " + page_title;    
  }

  render() {    
    return (
      <div className="content-wrapper">
        <section className="content banner-db">
            <div className="row">
                <div className="col-md-12">
                    <div className="welcomeadmin">
                        <div className="pull-left">
                            <h1>Hi <span>Souvik</span>, welcome to Accounts Dashboard</h1>
                        </div>
                        <div className="pull-right">
                            <button className="btn btn-info btn-sm pull-right daterange-btn mt-20"><i className="fa fa-calendar"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div className="db-wrapper clearfix">
                <div className="row">
                    <div className="col-md-6">
                        <div className="box-dashboard">
                            <div className="header">
                                <h3>Total Receivable</h3>
                            </div>
                            <div className="details">
                                <div className="row">
                                    <div className="col-xs-6">Total Receivable</div>
                                    <div className="col-xs-6 text-right"><i className="fa fa-inr"></i> <span>1,00,000.00</span></div>
                                    <div className="col-xs-12">
                                        <div className="separator clearfix"></div>
                                    </div>
                                </div>
                                <div className="row text-right">
                                    <div className="col-xs-6 border-right">
                                        <p className="text-info lead">Current</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                    </div>
                                    <div className="col-xs-6">
                                        <p className="text-danger lead">Overdue</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <div className="box-dashboard">
                            <div className="header">
                                <h3>Total Payable</h3>
                            </div>
                            <div className="details">
                                <div className="row">
                                    <div className="col-xs-6">Total Payable</div>
                                    <div className="col-xs-6 text-right"><i className="fa fa-inr"></i> <span>2,00,000.00</span></div>
                                    <div className="col-xs-12">
                                        <div className="separator clearfix"></div>
                                    </div>
                                </div>
                                <div className="row text-right">
                                    <div className="col-xs-6 border-right">
                                        <p className="text-info lead">Current</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                    </div>
                                    <div className="col-xs-6">
                                        <p className="text-danger lead">Overdue</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div className="row">
                    <div className="col-md-3">
                        <div className="box-dashboard">
                            <div className="header">
                                <h3>Cash Flow</h3>
                            </div>
                            <div className="details">
                                <div className="row text-right">
                                    <div className="col-xs-12">
                                        <p className="text-info lead">cash as on 01 Apr 2016</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                        <p className="text-success lead">Incoming</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                        <p className="text-danger lead">Outgoing</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                        <p className="text-info lead">cash as on 28 Feb 2017</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="col-md-3">
                        <div className="box-dashboard">
                            <div className="header">
                                <h3>Fund Flow</h3>
                            </div>
                            <div className="details">
                                <div className="row text-right">
                                    <div className="col-xs-12">
                                        <p className="text-info lead">cash as on 01 Apr 2016</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                        <p className="text-success lead">Incoming</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                        <p className="text-danger lead">Outgoing</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                        <p className="text-info lead">cash as on 28 Feb 2017</p>
                                        <p><i className="fa fa-inr"></i> <span>1,00,000.00</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="col-md-6">
                        <div className="box-dashboard">
                            <div className="header">
                                <h3>Watchlist</h3>
                            </div>
                            <div className="details">
                                <div className="row">
                                    <div className="col-xs-12">
                                        <table className="table table-watchlist">
                                            <thead>
                                                <tr className="text-info lead">
                                                    <th>Account</th>
                                                    <th>This Month (<i className="fa fa-inr"></i>)</th>
                                                    <th> (<i className="fa fa-inr"></i>)</th>
                                                </tr>
                                                <tr>
                                                    <td>Ledger name</td>
                                                    <td>70,000.00</td>
                                                    <td>2,5000.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Ledger name</td>
                                                    <td>70,000.00</td>
                                                    <td>2,5000.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Ledger name</td>
                                                    <td>70,000.00</td>
                                                    <td>2,5000.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Ledger name</td>
                                                    <td>70,000.00</td>
                                                    <td>2,5000.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Ledger name</td>
                                                    <td>70,000.00</td>
                                                    <td>2,5000.00</td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>              

            </div>
        </section>
    </div>
    );
  }
}
  
export default Dashboard;