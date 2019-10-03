import React, { Component } from 'react';
import {withRouter} from "react-router-dom";

import { Redirect } from 'react-router-dom';

import logo from '../../assets/images/logo-act-icon.png';

import user_pic from '../../assets/images/user.png';

import navigation from '../../_nav';

import PropTypes from 'prop-types';

const propTypes = {
    children: PropTypes.node,
};
  
const defaultProps = {};

class DefaultHeader extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isloggedin: sessionStorage.getItem('isloggedin') === 'true' ? true : false,
            toggleKey: false,
            transactionList: []
        };
    }

    componentDidMount = () => {
        var data = navigation.items.filter(x => x.is_tran == true)
        // console.log(data)
        this.setState({
            transactionList: data[0].children
        })
    }

    signout = () => {        
        this.setState({
            isloggedin: false
        }, () => {
            sessionStorage.clear();
        })
        
    }

    toggle = () => {
        this.setState({
            toggleKey: !this.state.toggleKey
        }, () => {
            this.props.settingLeftMenu(this.state.toggleKey)
        })
        
    }

    routeChange = (data) => {          
        if(data.children == undefined && !data.external){
            if(data.tran_type != undefined){
                var url = '/transaction/' + data.url + '/' + data.tran_type;
                if(this.props.location.pathname != url){
                    this.props.history.push(url)
                }
            }
            else {
                if(this.props.location.pathname != data.url){
                    this.props.history.push(data.url)
                }
            }            
        }
        else if(data.children == undefined && data.external){
            window.location = data.url
        }       
        
    }

    render() {   
        const { children, ...attributes } = this.props;        

        if (this.state.isloggedin === false) {
            return <Redirect to="/" />
        }

        const menuItems = this.state.transactionList.map((nav, i) =>
            <li key={i} >
                <a href="javascript:void(0)" onClick={() => this.routeChange(nav)}>{nav.name}</a>
            </li>
        )

        return (
            <header className="main-header">
                <a href="dashboard.html" className="logo">
                    <img src={logo} alt="Logo ACT"/>
                </a>
                <nav className="navbar navbar-static-top" role="navigation">
                    <a className="sidebar-toggle" data-toggle="offcanvas" role="button" onClick={this.toggle}>
                        <span className="sr-only">Toggle navigation</span>
                    </a>
                    <div className="navbar-custom-menu-form">
                        <ul className="nav navbar-nav">
                            <li className="dropdown">
                                <a className="dropdown-toggle" data-toggle="dropdown"><i className="fa fa-caret-down"></i></a>
                                <ul className="dropdown-menu">
                                    {menuItems}
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div className="navbar-custom-menu">
                        <ul className="nav navbar-nav">
                            <li className="dropdown notifications-menu">
                                <a href="#" className="dropdown-toggle" data-toggle="dropdown">
                                    <i className="fa fa-bell-o"></i>
                                    <span className="label label-warning">10</span>
                                </a>
                                <ul className="dropdown-menu">
                                    <li className="header">You have 10 notifications</li>
                                    <li>
                                        <ul className="menu">
                                            <li><a href="#"><i className="fa fa-users text-aqua"></i> 5 new members joined today</a></li>
                                            <li><a href="#"><i className="fa fa-users text-aqua"></i> 5 new members joined today</a></li>
                                            <li><a href="#"><i className="fa fa-users text-aqua"></i> 5 new members joined today</a></li>
                                            <li><a href="#"><i className="fa fa-users text-aqua"></i> 5 new members joined today</a></li>
                                        </ul>
                                    </li>
                                    <li className="footer"><a href="#">View all</a></li>
                                </ul>
                            </li>
                            <li className="dropdown user user-menu">
                                <a href="#" className="dropdown-toggle" data-toggle="dropdown">
                                    <img src={user_pic} className="user-image" alt="User Image"/>
                                    <span className="hidden-xs">Souvik</span>
                                </a>
                                <ul className="dropdown-menu">
                                    <li className="user-header">
                                        <img src={user_pic} className="img-circle" alt="User Image"/>
                                        <p>
                                            sovik@sketchwebsolutions.com
                                            <small>Last Login 5th Apr 2017 at 05:03:48 PM</small>
                                        </p>
                                    </li>
                                    <li className="user-footer">
                                        <div className="pull-left">
                                            <a href="" className="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div className="pull-right">
                                            <a onClick={this.signout} className="btn btn-warning btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
        );
    }
}

DefaultHeader.propTypes = propTypes;
DefaultHeader.defaultProps = defaultProps;

export default withRouter(DefaultHeader);