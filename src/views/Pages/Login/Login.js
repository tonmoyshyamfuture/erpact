import React, { Component } from 'react';
import { Redirect } from 'react-router-dom';
import {Checkbox, Radio} from 'react-icheck';

// css
import 'icheck/skins/all.css';
import './login.css';

import logo from '../../../assets/images/logo-act.png';

class Login extends Component {
  constructor(props) {
    super(props);
    
    this.state = {
      isloggedin: false
    };
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
  
  login = () => {
    this.setState({
      isloggedin: true
    }, () => {
        sessionStorage.setItem('isloggedin', true)
    })
    
  }

  render() {
    const { isloggedin } = this.state

    if (isloggedin === true) {
      return <Redirect to="/" />
    }

    return (      
      <div className="hold-transition login-page">
        <div className="login-box">
            <div className="login-logo">
                <img src={logo} className="img-responsive" alt="ACT"/>
            </div>
            <div className="login-box-body">
                <p className="login-box-msg">Please login to your Account</p>
                <form action="dashboard.php" method="post">
                    <div className="form-group">
                        <i className="fa fa-envelope"></i>
                        <input type="email" className="form-control" placeholder="Email" />
                    </div>
                    <div className="form-group">
                        <i className="fa fa-lock"></i>
                        <input type="password" className="form-control" placeholder="Password" />
                        <a href="#"><i className="fa fa-question-circle forgotpass"></i></a>
                    </div>
                    <div className="row">
                        <div className="col-xs-8">
                            <div className="checkbox icheck">
                                <Checkbox id="checkbox1" checkboxClass="icheckbox_square-blue" increaseArea="20%" />
                                <label htmlFor="checkbox1"> &nbsp; Stay signed in</label>                                
                            </div>
                        </div>
                        <div className="col-xs-4">
                            <button type="submit" className="btn btn-primary btn-block btn-flat" onClick={this.login}>Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    )
  }
}

export default Login;
