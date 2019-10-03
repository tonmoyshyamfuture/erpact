import React, { Component } from 'react';
import PropTypes from 'prop-types';
import {withRouter} from "react-router-dom";

import logo_company from '../../assets/images/logo-company.jpg';

import navigation from '../../_nav';

const propTypes = {
  children: PropTypes.node,
};

const defaultProps = {};

class DefaultLeftMenu extends Component {
    constructor(props) {
        super(props);
        this.routeChange = this.routeChange.bind(this);
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
        // eslint-disable-next-line
        const { children, ...attributes } = this.props;
        const menuItems = navigation.items.map((nav, i) =>
            <li key={i} className={nav.children != undefined ? 'treeview' : ''}>
                <a href="javascript:void(0)" onClick={() => this.routeChange(nav)}>
                    <i className={nav.icon}></i>
                    <span>{nav.name}</span>
                    {
                        nav.children != undefined && (
                            <i className="fa fa-angle-left pull-right"></i>
                        )
                    }
                </a>
                {
                    nav.children != undefined && (
                        <ul className="treeview-menu">
                            {
                                nav.children.map((subnav, j) =>
                                    <li key={j} >
                                        <a href="javascript:void(0)" onClick={() => this.routeChange(subnav)}>{subnav.name}</a>
                                    </li>
                                )
                            }
                        </ul>
                    )
                }
            </li>
        )
        return (          
            <aside className="main-sidebar">
            <section className="sidebar">
                <div className="user-panel">
                    <div className="pull-left image">
                        <img src={logo_company} className="img-circle" alt="User Image"/>
                    </div>
                    <div className="pull-left info">
                        <p>Company Name</p>
                        <p ><i className="fa fa-calendar"></i> 2018 - 2019</p>
                    </div>
                </div>
                <ul className="sidebar-menu">
                    {menuItems}
                </ul>
            </section>
        </aside>
        );
    }
}

DefaultLeftMenu.propTypes = propTypes;
DefaultLeftMenu.defaultProps = defaultProps;

export default withRouter(DefaultLeftMenu);
